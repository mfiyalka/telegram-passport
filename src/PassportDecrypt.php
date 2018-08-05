<?php

namespace Mfiyalka\TelegramPassport;

use Exception;
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
     * PassportDecrypt constructor.
     * @param $passport_data
     * @throws Exception
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
        $value_data        = json_decode($value_data, true);

        return $value_data;
    }

    /**
     * @param $file_data
     * @param $type
     * @param $file_type
     * @param $toURL
     * @param null $key
     * @return string
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function decryptFile($file_data, $type, $file_type, $toURL, $key = null)
    {
        if (!isset($this->credentials_data['secure_data'][$type][$file_type])) {
            throw new \Exception('Not found type.');
        }

        $file_id   = $file_data['file_id'];
        $file = \Telegram::bot()->getFile(['file_id' => $file_id]);
        $file_encrypted = $this->botApiGetFileContents($file);

        if (is_null($key)) {
            $file_credentials = $this->credentials_data['secure_data'][$type][$file_type];
        } else {
            $file_credentials = $this->credentials_data['secure_data'][$type][$file_type][$key];
        }

        $file_hash        = base64_decode($file_credentials['file_hash']);
        $file_secret      = base64_decode($file_credentials['secret']);
        $file_content     = $this->decryptData($file_encrypted, $file_secret, $file_hash);

        $file_local_path  = md5($file_id).'.jpg';
        $toURL = $toURL . $file_local_path;
        file_put_contents($toURL, $file_content);

        return $file_local_path;
    }

    public function getPayload()
    {
        return $this->credentials_data['payload'];
    }

    /**
     * @throws Exception
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
        $credentials_data           = json_decode($credentials_data, true);
        $this->credentials_data     = collect($credentials_data);
    }

    /**
     * @param string $data_encrypted
     * @param string $data_secret
     * @param string $data_hash
     * @return bool|string
     * @throws \Exception
     */
    protected function decryptData(string $data_encrypted, string $data_secret, string $data_hash)
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

        return $data_decrypted;
    }

    /**
     * @param $file
     * @return mixed
     * @throws Exception
     */
    private function botApiGetFileContents($file) {
        $api_url = 'https://api.telegram.org/file/bot'.config('telegram_passport.bot_token');
        $file_path = $file['file_path'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url.'/'.$file_path);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        $http_code = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($http_code >= 400) {
            if ($http_code == 401) {
                throw new Exception('ACCESS_TOKEN_INVALID');
            }
            throw new Exception('HTTP_ERROR_'.$http_code);
        }
        if ($errno) {
            $error = curl_error($curl);
            throw new Exception('CURL_ERROR: '.$error);
        }
        return $response;
    }
}
