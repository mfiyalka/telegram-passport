<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class ResidentialAddress
 *
 * @property string     $streetLine1            First line for the address
 * @property string     $streetLine2            (Optional). Second line for the address
 * @property string     $city                   City
 * @property string     $state                  (Optional). State
 * @property string     $countryCode            ISO 3166-1 alpha-2 country code
 * @property string     $postCode               Address post code
 *
 * @link https://core.telegram.org/passport#residentialaddress
 */
class ResidentialAddress extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [];
    }
}
