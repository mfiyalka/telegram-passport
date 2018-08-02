<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class PassportFile
 *
 * @property string     $fileId
 * @property int        $fileSize
 * @property int        $fileDate
 *
 * @link https://core.telegram.org/bots/api#passportfile
 */
class PassportFile extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [];
    }
}
