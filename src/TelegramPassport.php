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
        if ($data = $this->getItem('personal_details')) {
            $data = $this->decrypt('personal_details', $data['data']);
            return $data ? new PersonalDetails($data) : false;
        }
        return false;
    }

    /**
     * @return Passport|TelegramPassport
     * @throws \Exception
     */
    public function getPassport()
    {
        $data = $this->getItem('passport');
        return $data ? new Passport($data, ['credentials' => $this->getCredentials()]) : $this;
    }

    /**
     * @return InternalPassport|TelegramPassport
     * @throws \Exception
     */
    public function getInternalPassport()
    {
        $data = $this->getItem('internal_passport');
        return $data ? new InternalPassport($data, ['credentials' => $this->getCredentials()]) : $this;
    }

    /**
     * @return DriverLicense|TelegramPassport
     * @throws \Exception
     */
    public function getDriveLicense()
    {
        $data = $this->getItem('driver_license');
        return $data ? new DriverLicense($data, ['credentials' => $this->getCredentials()]) : $this;
    }

    /**
     * @return IdentityCard|TelegramPassport
     * @throws \Exception
     */
    public function getIdentityCard()
    {
        $data = $this->getItem('identity_card');
        return $data ? new IdentityCard($data, ['credentials' => $this->getCredentials()]) : $this;
    }

    /**
     * @return \Mfiyalka\TelegramPassport\Objects\Data\ResidentialAddress|bool
     * @throws \Exception
     */
    public function getAddress()
    {
        if ($data = $this->getItem('address')) {
            $data = $this->decrypt('address', $data['data']);
            return $data ? new ResidentialAddress($data) : false;
        }
        return false;
    }

    /**
     * @return bool|UtilityBill
     * @throws \Exception
     */
    public function getUtilityBill()
    {
        $data = $this->getItem('utility_bill');
        return $data ? new UtilityBill($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return bool|BankStatement
     * @throws \Exception
     */
    public function getBankStatement()
    {
        $data = $this->getItem('bank_statement');
        return $data ? new BankStatement($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return bool|RentalAgreement
     * @throws \Exception
     */
    public function getRentalAgreement()
    {
        $data = $this->getItem('rental_agreement');
        return $data ? new RentalAgreement($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return bool|PassportRegistration
     * @throws \Exception
     */
    public function getPassportRegistration()
    {
        $data = $this->getItem('passport_registration');
        return $data ? new PassportRegistration($data, ['credentials' => $this->getCredentials()]) : false;
    }

    /**
     * @return bool|TemporaryRegistration
     * @throws \Exception
     */
    public function getTemporaryRegistration()
    {
        $data = $this->getItem('temporary_registration');
        return $data ? new TemporaryRegistration($data, ['credentials' => $this->getCredentials()]) : false;
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
     * @return mixed
     * @throws \Exception
     */
    public function getPayload()
    {
        $decrypt = new PassportDecrypt(['credentials' => $this->getCredentials()]);
        return $decrypt->getPayload();
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
