<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;
use Mfiyalka\TelegramPassport\PassportDecrypt;

/**
 * Class DriverLicense
 *
 * @property string             $type
 * @property string             $data
 * @property PassportFile       $frontSide
 * @property PassportFile       $reverseSide
 * @property PassportFile       $selfie
 *
 * @link https://core.telegram.org/passport#fields
 */
class DriverLicense extends BaseObject
{
    private $credentials;
    private $decrypt;
    private $type = 'driver_license';

    /**
     * DriverLicense constructor.
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
            'front_side' => PassportFile::class,
            'reverse_side' => PassportFile::class,
            'selfie' => PassportFile::class
        ];
    }

    /**
     * @throws \Exception
     */
    public function idDocumentData()
    {
        $decrypt = new PassportDecrypt($this->credentials);
        $data = $decrypt->decrypt($this->type, $this->data);
        return new IdDocumentData($data);
    }

    /**
     * @param string $toURL
     * @return string
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function getFrontSide(string $toURL)
    {
        $file_data = $this->frontSide;
        $file_local_path = $this->decrypt->decryptFile($file_data, $this->type, 'front_side', $toURL);
        return $file_local_path;
    }

    /**
     * @param string $toURL
     * @return string
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function getReverseSide(string $toURL)
    {
        $file_data = $this->reverseSide;
        $file_local_path = $this->decrypt->decryptFile($file_data, $this->type, 'reverse_side', $toURL);
        return $file_local_path;
    }

    /**
     * @param string $toURL
     * @return string
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function getSelfie(string $toURL)
    {
        $file_data = $this->selfie;
        $file_local_path = $this->decrypt->decryptFile($file_data, $this->type, 'selfie', $toURL);
        return $file_local_path;
    }
}
