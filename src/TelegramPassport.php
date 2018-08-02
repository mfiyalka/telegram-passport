<?php

namespace Mfiyalka\TelegramPassport;

use Mfiyalka\TelegramPassport\Objects\BaseObject;
use Mfiyalka\TelegramPassport\Objects\Data\BankStatement;
use Mfiyalka\TelegramPassport\Objects\Data\DriverLicense;
use Mfiyalka\TelegramPassport\Objects\Data\Email;
use Mfiyalka\TelegramPassport\Objects\Data\IdentityCard;
use Mfiyalka\TelegramPassport\Objects\Data\InternalPassport;
use Mfiyalka\TelegramPassport\Objects\Data\Passport;
use Mfiyalka\TelegramPassport\Objects\Data\PassportRegistration;
use Mfiyalka\TelegramPassport\Objects\Data\PersonalDetails;
use Mfiyalka\TelegramPassport\Objects\Data\PhoneNumber;
use Mfiyalka\TelegramPassport\Objects\Data\RentalAgreement;
use Mfiyalka\TelegramPassport\Objects\Data\ResidentialAddress;
use Mfiyalka\TelegramPassport\Objects\Data\TemporaryRegistration;
use Mfiyalka\TelegramPassport\Objects\Data\UtilityBill;
use Mfiyalka\TelegramPassport\Objects\PassportData;

/**
 * Class TelegramPassport
 *
 * @property PassportData   $passportData        (Optional). Telegram Passport data
 *
 * @link https://core.telegram.org/bots/api#message
 */
class TelegramPassport extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [
            'passport_data' => PassportData::class,
        ];
    }

    /**
     * @return PersonalDetails|bool
     * @throws \Exception
     */
    public function getPersonalDetails()
    {
        $data = $this->getItem('personal_details');
        $data = $this->decrypt('personal_details', $data['data']);
        return $data ? new PersonalDetails($data) : false;
    }

    /**
     * @return \Mfiyalka\TelegramPassport\Objects\Data\Passport|bool
     */
    public function getPassport()
    {
        $data = $this->getItem('passport');
        return $data ? new Passport($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return \Mfiyalka\TelegramPassport\Objects\Data\InternalPassport|bool
     */
    public function getInternalPassport()
    {
        $data = $this->getItem('internal_passport');
        return $data ? new InternalPassport($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return DriverLicense|bool
     */
    public function getDriveLicense()
    {
        $data = $this->getItem('driver_license');
        return $data ? new DriverLicense($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return IdentityCard|bool
     */
    public function getIdentityCard()
    {
        $data = $this->getItem('identity_card');
        return $data ? new IdentityCard($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return \Mfiyalka\TelegramPassport\Objects\Data\ResidentialAddress|bool
     * @throws \Exception
     */
    public function getAddress()
    {
        $data = $this->getItem('address');
        $data = $this->decrypt('address', $data['data']);
        return $data ? new ResidentialAddress($data) : false;
    }

    /**
     * @return bool|UtilityBill
     */
    public function getUtilityBill()
    {
        $data = $this->getItem('utility_bill');
        return $data ? new UtilityBill($data) : false;
    }

    /**
     * @return bool|BankStatement
     */
    public function getBankStatement()
    {
        $data = $this->getItem('bank_statement');
        return $data ? new BankStatement($data) : false;
    }

    /**
     * @return bool|RentalAgreement
     */
    public function getRentalAgreement()
    {
        $data = $this->getItem('rental_agreement');
        return $data ? new RentalAgreement($data) : false;
    }

    /**
     * @return bool|PassportRegistration
     */
    public function getPassportRegistration()
    {
        $data = $this->getItem('passport_registration');
        return $data ? new PassportRegistration($data) : false;
    }

    /**
     * @return bool|TemporaryRegistration
     */
    public function getTemporaryRegistration()
    {
        $data = $this->getItem('temporary_registration');
        return $data ? new TemporaryRegistration($data) : false;
    }

    /**
     * @return \Mfiyalka\TelegramPassport\Objects\Data\PhoneNumber|bool
     */
    public function getPhoneNumber()
    {
        $data = $this->getItem('phone_number');
        return $data ? new PhoneNumber($data) : false;
    }

    /**
     * @return Email|bool
     */
    public function getEmail()
    {
        $data = $this->getItem('email');
        return $data ? new Email($data) : false;
    }

    /**
     * @param string $type
     * @return bool|array
     */
    private function getItem(string $type)
    {
        $data = $this->passportData->data;
        $key = null;
        $data->each(function ($i, $k) use (&$key, $type) {
            if ($i['type'] == $type) {
                $key = $k;
            }
        });

        return is_null($key) ? false :  $data[$key];
    }

    /**
     * @param string $type
     * @param string $data
     * @return array
     * @throws \Exception
     */
    private function decrypt(string $type, string $data)
    {
        $decrypt = new PassportDecrypt(['credentials' => $this->getCredentials()]);
        return $decrypt->decrypt($type, $data);
    }

    private function getCredentials()
    {
        return $this->passportData['credentials'];
    }
}
