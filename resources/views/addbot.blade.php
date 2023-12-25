<?php

use DefStudio\Telegraph\Models\TelegraphBot;

$token = "6967319981:AAFHSiI7xCiWCr-EcwcEZInPCALYgtVDLVA";
$name = "TMR";

$bot = TelegraphBot::create([
    'token' => $token,
    'name' => $name,
]);

print "Added $bot";
