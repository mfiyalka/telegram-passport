<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class Email
 *
 * @property string     $email             Email
 *
 * @link https://core.telegram.org/passport#fields
 */
class Email extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [];
    }
}
