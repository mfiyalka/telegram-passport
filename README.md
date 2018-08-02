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

$passport = new TelegramPassport($passportData);

$phoneNumber = $passport->getPhoneNumber()->phoneNumber;
$email = $passport->getEmail()->email;

// Get personal details
$personalDetails = $passport->getPersonalDetails();
$firstName = $personalDetails->firstName;
$lastName = $personalDetails->lastName;
$birthDate = $personalDetails->birthDate;
$gender = $personalDetails->gender;
$countryCode = $personalDetails->countryCode;
$residenceCountryCode = $personalDetails->residenceCountryCode;

// Get Passport
$passport = $passport->getPassport();
$documentNo = $passport->idDocumentData()->documentNo;
$expiryDate = $passport->idDocumentData()->expiryDate;
$frontSide = $passport->frontSide;
$fileId = $passport->frontSide->fileId;
$fileSize = $passport->frontSide->fileSize;
$fileDate = $passport->frontSide->fileDate;

// Get Utility bill
$utilityBill = $passport->getUtilityBill();
$files = $utilityBill->files;
```