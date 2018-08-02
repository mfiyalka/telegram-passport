<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;
use Mfiyalka\TelegramPassport\PassportDecrypt;

/**
 * Class InternalPassport
 *
 * @property string             $type
 * @property string             $data
 * @property PassportFile       $frontSide
 * @property PassportFile       $selfie
 *
 * @link https://core.telegram.org/passport#fields
 */
class InternalPassport extends BaseObject
{
    private $credentials;

    public function __construct($data, array $credentials)
    {
        parent::__construct($data);
        $this->credentials = $credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [
            'front_side' => PassportFile::class,
            'selfie' => PassportFile::class
        ];
    }

    /**
     * @throws \Exception
     */
    public function idDocumentData()
    {
        $decrypt = new PassportDecrypt($this->credentials);
        $data = $decrypt->decrypt('internal_passport', $this->data);
        return new IdDocumentData($data);
    }
}
