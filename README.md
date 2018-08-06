# Telegram Passport

https://core.telegram.org/passport

## Install

```php
composer require mfiyalka/telegram-passport "*"
```

```php
...
"require": {
        "mfiyalka/telegram-passport": "*"
    }
...
```

Генеруємо приватний ключ, який поміщаємо в директорію config/telegram
```bash
openssl genrsa 2048 > private.key
```

Тепер генеруємо публічний ключ, який також поміщаємо в директорію config/telegram під іменем public.key
```bash
openssl rsa -in private.key -pubout
```

Use the **/setpublickey** command with @BotFather to connect this public key with your bot.

Скопіюйте конфігураційний файл /vendor/mfiyalka/telegram-passport/config/telegram_passport.php в директорію /config


## Examples:

```json

{
    "passport_data": {
        "data": [
            {
                "type": "personal_details",
                "data": "..."
            },
            {
                "type": "passport",
                 "data": "...",
                "front_side": {
                    "file_id": "...",
                    "file_size": 1690656,
                    "file_date": 1532965670
                }
            },
            {
                "type": "driver_license",
                "data": "",
                "front_side": {
                    "file_id": "...",
                    "file_date": 1533211580
                },
                "reverse_side": {
                    "file_id": "...",
                    "file_date": 1533211580
                }
            },
            {
                "type": "address",
                "data": "..."
            },
            {
                "type": "utility_bill",
                "files": [
                    {
                        "file_id": "...",
                        "file_date": 1532965834
                    },
                    {
                        "file_id": "...",
                        "file_date": 1533213517
                    }
                ]
            },
            {
                "type": "rental_agreement",
                "files": [
                    {
                        "file_id": "...",
                        "file_date": 1533213500
                    }
                ]
            },
            {
                "type": "phone_number",
                "phone_number": "..."
            },
            {
                "type": "email",
                "email": "..."
            }
        ],
        "credentials": {
            "data": "...",
            "hash": "...",
            "secret": "..."
        }
    }
}

```


```php
<?php

use Mfiyalka\TelegramPassport\TelegramPassport;

$passportData = new TelegramPassport($passportData);

// Identity //
// Personal Details
$personalDetails = $passportData->getPersonalDetails();
$firstName = $personalDetails->firstName ?? null;
$lastName = $personalDetails->lastName ?? null;
$birthDate = $personalDetails->birthDate ?? null;
$gender = $personalDetails->gender ?? null;
$countryCode = $personalDetails->countryCode ?? null;
$residenceCountryCode = $personalDetails->residenceCountryCode ?? null;

// Passport
$passport = $passportData->getPassport();
$passportDocumentNo = $passport->idDocumentData()->documentNo ?? null;
$passportExpiryDate = $passport->idDocumentData()->expiryDate ?? null;
$passportFrontSide = null;
if ($passport->frontSide) {
    $toURL = __DIR__ . '/../../../public/img/';
    $passportFrontSide = $passport->getFrontSide($toURL);
}
$passportSelfie = null;
if ($passport->selfie) {
    $toURL = __DIR__ . '/../../../public/img/';
    $passportSelfie = $passport->getSelfie($toURL);
}

// Drive License
$driveLicense = $passportData->getDriveLicense();
$driveLicenseDocumentNo = $driveLicense->idDocumentData()->documentNo ?? null;
$driveLicenseExpiryDate = $driveLicense->idDocumentData()->expiryDate ?? null;
$driveLicenseFrontSide = null;
if ($driveLicense->frontSide) {
    $toURL = __DIR__ . '/../../../public/img/';
    $driveLicenseFrontSide = $driveLicense->getFrontSide($toURL);
}
$driveLicenseReverseSide = null;
if ($driveLicense->reverseSide) {
    $toURL = __DIR__ . '/../../../public/img/';
    $driveLicenseReverseSide = $driveLicense->getReverseSide($toURL);
}
$driveLicenseSelfie = null;
if ($driveLicense->selfie) {
    $toURL = __DIR__ . '/../../../public/img/';
    $driveLicenseSelfie = $driveLicense->getSelfie($toURL);
}

// Identity Card
$identityCard = $passportData->getIdentityCard();
$identityCardDocumentNo = $identityCard->idDocumentData()->documentNo ?? null;
$identityCardExpiryDate = $identityCard->idDocumentData()->expiryDate ?? null;
$identityCardFrontSide = null;
if ($identityCard->frontSide) {
    $toURL = __DIR__ . '/../../../public/img/';
    $identityCardFrontSide = $identityCard->getFrontSide($toURL);
}
$identityCardReverseSide = null;
if ($identityCard->reverseSide) {
    $toURL = __DIR__ . '/../../../public/img/';
    $identityCardReverseSide = $identityCard->getReverseSide($toURL);
}
$identityCardSelfie = null;
if ($identityCard->selfie) {
    $toURL = __DIR__ . '/../../../public/img/';
    $identityCardSelfie = $identityCard->getSelfie($toURL);
}

// Internal Passport
$internalPassportCard = $passportData->getInternalPassport();
$internalPassportDocumentNo = $internalPassportCard->idDocumentData()->documentNo ?? null;
$internalPassportExpiryDate = $internalPassportCard->idDocumentData()->expiryDate ?? null;
$internalPassportFrontSide = null;
if ($internalPassportCard->frontSide) {
    $toURL = __DIR__ . '/../../../public/img/';
    $internalPassportFrontSide = $internalPassportCard->getFrontSide($toURL);
}
$internalPassportSelfie = null;
if ($internalPassportCard->selfie) {
    $toURL = __DIR__ . '/../../../public/img/';
    $internalPassportSelfie = $internalPassportCard->getSelfie($toURL);
}

// Address //
// Residential Address
$residentialAddress = $passportData->getAddress();
$streetLine1 = $residentialAddress->streetLine1 ?? null;
$streetLine2 = $residentialAddress->streetLine2 ?? null;
$city = $residentialAddress->city ?? null;
$state = $residentialAddress->state ?? null;
$addresCountryCode = $residentialAddress->countryCode ?? null;
$postCode = $residentialAddress->postCode ?? null;

// Utility Bill
$utilityBill = $passportData->getUtilityBill();
$utilityBillFiles = null;
if (!is_null($utilityBill->files)) {
    $files = $utilityBill->files;
    $toURL = __DIR__ . '/../../../public/img/';

    $utilityBillFiles = [];
    foreach ($files as $key => $item) {
        $utilityBillFiles[] = $utilityBill->getFile($key, $toURL);
    }
}

// Contact //
$phoneNumber = $passportData->getPhoneNumber()->phoneNumber ?? null;
$email = $passportData->getEmail()->email ?? null;
```