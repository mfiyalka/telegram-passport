<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class PassportRegistration
 *
 * @property string         $type
 * @property PassportFile   $files
 *
 * @link https://core.telegram.org/passport#fields
 */
class PassportRegistration extends BaseObject
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
