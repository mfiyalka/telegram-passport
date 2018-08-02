<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class PhoneNumber
 *
 * @property string     $phoneNumber              Phone number
 *
 * @link https://core.telegram.org/passport#fields
 */
class PhoneNumber extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [];
    }
}
