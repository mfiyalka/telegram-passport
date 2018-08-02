<?php

namespace Mfiyalka\TelegramPassport;

use Mfiyalka\TelegramPassport\Objects\PassportData;

class PassportDecrypt
{
    private $passport_data;
    private $bot_id;
    private $bot_token;
    private $bot_public_key;
    private $bot_private_key;
    private $credentials_data;

    /**
     * Passport constructor.
     * @param $passport_data
     * @throws \Exception
     */
    public function __construct($passport_data)
    {
        $passport_data = is_object($passport_data) ? (array) $passport_data : $passport_data;
        $this->passport_data = new PassportData($passport_data);

        $this->bot_id = config('telegram_passport.bot_id');
        $this->bot_token = config('telegram_passport.bot_token');
        $this->bot_public_key = config('telegram_passport.bot_public_key');
        $this->bot_private_key = config('telegram_passport.bot_private_key');
        $this->decryptCredentialsData();
    }

    /**
     * @param string $type
     * @param string $data
     * @return array
     * @throws \Exception
     */
    public function decrypt(string $type, string $data)
    {
        if (!isset($this->credentials_data['secure_data'][$type])) {
            throw new \Exception('Not found type.');
        }

        $value_hash        = base64_decode($this->credentials_data['secure_data'][$type]['data']['data_hash']);
        $value_secret      = base64_decode($this->credentials_data['secure_data'][$type]['data']['secret']);
        $data_encrypted    = base64_decode($data);
        $value_data        = $this->decryptData($data_encrypted, $value_secret, $value_hash);

        return $value_data;
    }

    /**
     * @throws \Exception
     */
    protected function decryptCredentialsData(): void
    {
        $secret_encrypted = base64_decode($this->passport_data->credentials->secret);
        $result = openssl_private_decrypt(
            $secret_encrypted,
            $credentials_secret,
            $this->bot_private_key,
            OPENSSL_PKCS1_OAEP_PADDING
        );

        if (!$result) {
            throw new \Exception('Credential secret decryption failed.');
        }

        $credentials_data_encrypted = base64_decode($this->passport_data->credentials->data);
        $credentials_hash           = base64_decode($this->passport_data->credentials->hash);
        $credentials_data           = $this->decryptData($credentials_data_encrypted, $credentials_secret, $credentials_hash);
        $this->credentials_data     = collect($credentials_data);
    }

    /**
     * @param string $data_encrypted
     * @param string $data_secret
     * @param string $data_hash
     * @return array
     * @throws \Exception
     */
    protected function decryptData(string $data_encrypted, string $data_secret, string $data_hash): array
    {
        $data_secret_hash = hash('sha512', $data_secret.$data_hash, true);
        $data_key         = substr($data_secret_hash, 0, 32);
        $data_iv          = substr($data_secret_hash, 32, 16);
        $options          = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING;
        $data_decrypted   = openssl_decrypt($data_encrypted, 'aes-256-cbc', $data_key, $options, $data_iv);

        if (!$data_decrypted) {
            throw new \Exception('Decrypt failed.');
        }

        $data_decrypted_hash = hash('sha256', $data_decrypted, true);
        if (strcmp($data_hash, $data_decrypted_hash)) {
            throw new \Exception('Hash invalid.');
        }

        $padding_len    = ord($data_decrypted[0]);
        $data_decrypted = substr($data_decrypted, $padding_len);

        return json_decode($data_decrypted, true);
    }
}
