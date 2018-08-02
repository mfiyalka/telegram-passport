<?php

return [
    'bot_id' => env('TELEGRAM_BOT_ID'),
    'bot_username' => env('TELEGRAM_BOT_USERNAME'),
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'bot_public_key' => str_replace("\n", "\\n", file_get_contents(__DIR__ . '/telegram/public.key')),
    'bot_private_key' => file_get_contents(__DIR__ . '/telegram/private.key')
];
