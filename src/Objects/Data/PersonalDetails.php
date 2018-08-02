<?php

namespace Mfiyalka\TelegramPassport\Objects\Data;

use Mfiyalka\TelegramPassport\Objects\BaseObject;

/**
 * Class PersonalDetails
 *
 * @property string     $firstName              First Name
 * @property string     $lastName               Last Name
 * @property string     $birthDate              Date of birth in DD.MM.YYYY format
 * @property string     $gender                 Gender, male or female
 * @property string     $countryCode            Citizenship (ISO 3166-1 alpha-2 country code)
 * @property string     $residenceCountryCode   Country of residence (ISO 3166-1 alpha-2 country code)
 *
 * @link https://core.telegram.org/passport#personaldetails
 */
class PersonalDetails extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [];
    }
}
