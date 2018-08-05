<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;
use Mfiyalka\TelegramPassport\PassportDecrypt;

/**
 * Class UtilityBill
 *
 * @property string         $type
 * @property PassportFile   $files
 *
 * @link https://core.telegram.org/passport#fields
 */
class UtilityBill extends BaseObject
{
    private $credentials;
    private $decrypt;
    private $type = 'utility_bill';

    /**
     * UtilityBill constructor.
     * @param $data
     * @param array $credentials
     * @throws \Exception
     */
    public function __construct($data, array $credentials)
    {
        parent::__construct($data);
        $this->credentials = $credentials;
        $this->decrypt = new PassportDecrypt($this->credentials);
    }

    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [
            'files' => PassportFile::class
        ];
    }

    /**
     * @param $key
     * @param string $toURL
     * @return string
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function getFile($key, string $toURL)
    {
        $file_data = $this->files[$key];
        $file_local_path = $this->decrypt->decryptFile($file_data, $this->type, 'files', $toURL, $key);
        return $file_local_path;
    }
}
