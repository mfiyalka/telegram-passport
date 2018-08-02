<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class TemporaryRegistration
 *
 * @property string         $type
 * @property PassportFile   $files
 *
 * @link https://core.telegram.org/passport#fields
 */
class TemporaryRegistration extends BaseObject
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
