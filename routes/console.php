<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use DefStudio\Telegraph\Models\TelegraphBot;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reg', function () {
    /** @var \DefStudio\Telegraph\Models\TelegraphBot $bot
     * register actions, buttons etc for TMR_bot
     */

    $bot = TelegraphBot::find(1);

    dd($bot->registerCommands([
        'post' => 'Передать показания',
        'get' => 'Экспорт в Excel'
    ])->send());
});

Artisan::command('unreg', function () {
    /** @var \DefStudio\Telegraph\Models\TelegraphBot $bot
     * unregister all for TMR_bot
     */

    $bot = TelegraphBot::find(1);

    dd($bot->unregisterCommands()->send());
});
