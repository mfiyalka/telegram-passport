<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

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
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [
            'files' => PassportFile::class
        ];
    }
}
