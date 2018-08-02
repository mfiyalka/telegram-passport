<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class IdDocumentData
 *
 * @property    string      $documentNo     Document number
 * @property    string      $expiryDate     (Optional). Date of expiry, in DD.MM.YYYY format
 *
 * @link https://core.telegram.org/passport#iddocumentdata
 */
class IdDocumentData extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [];
    }
}
