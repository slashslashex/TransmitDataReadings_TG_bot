<?php


namespace App\Http\Telegraph;


use App\Models\DBRead;
use App\Models\DVECElectricityReading;
use App\Models\DVECHotWaterReading;
use App\Models\ERKCReading;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Stringable;

use App\Exports\ExportDVECElectricityReading;
use App\Imports\ImportDVECElectricityReading;
use App\Exports\ExportDVECHotWaterReading;
use App\Imports\ImportDVECHotWaterReading;
use App\Exports\ExportERKCReading;
use App\Imports\ImportERKCReading;
use Maatwebsite\Excel\Facades\Excel;

class TGHandler extends WebhookHandler
{

    public function test()
    {
        $test = new DVECElectricityReading();
        $test->testHandler($this->chat);
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->chat->html('Неизвестная команда')->send();
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->chat->html('Используйте доступные команды')->send();
    }

    public function post()
    {
        $this->chat->message('Выберите поставщика')->keyboard(
            Keyboard::make()->buttons([
                Button::make('ДВЕЦ электроэнергия')->action('dvec_electricity'),
                Button::make('ДВЕЦ горячая вода')->action('dvec_hot'),
                Button::make('ЕРКЦ холодная и горячая вода')->action('erkc')
            ])
        )->send();
    }

    public function get()
    {
        $this->chat->message('Выберите данные для экспорта')->keyboard(
            Keyboard::make()->buttons([
                Button::make('ДВЕЦ электроэнергия')->action('dvecElectrExport'),
                Button::make('ДВЕЦ горячая вода')->action('dvecHotExport'),
                Button::make('ЕРКЦ холодная и горячая вода')->action('erkcExport'),

            ]))->send();
    }

    public function dvec_electricity(): void
    {
        $handle = new DVECElectricityReading();
        $handle->buttonElectricity($this->chat);
    }

    public function de($electricity): void
    {
        $handle = new DVECElectricityReading();
        $handle->handleDataPost($electricity, $this->chat);
    }

    public function dvec_hot(): void
    {
        $handle = new DVECHotWaterReading();
        $handle->buttonHotWater($this->chat);
    }

    public function dh($hotWater): void
    {
        $handle = new DVECHotWaterReading();
        $handle->handleDataPost($hotWater, $this->chat);
    }

    public function erkc(): void
    {
        $handle = new ERKCReading();
        $handle->buttonErkc($this->chat);
    }

    public function er($readings): void
    {
        $handle = new ERKCReading();
        $handle->handleDataPost($readings, $this->chat);
    }

    public function readings(): void
    {
        $handle = new DBRead();
        $handle->buttonDBRead($this->chat);
    }

    public function r($date): void
    {
        $handle = new DBRead();
        $handle->handleDataRead($date, $this->chat);
    }

    public function dvecElectrExport()
    {
        $handle = new ExportDVECElectricityReading();
        $handle->handleExportElectr($this->chat);
    }

    public function dvecHotExport()
    {
        $handle = new ExportDVECHotWaterReading();
        $handle->handleExportHotWater($this->chat);
    }

    public function erkcExport()
    {
        $handle = new ExportERKCReading();
        $handle->handleExportErkc($this->chat);
    }

//    public function imp()
//    {
//        Excel::import(new ImportDVECHotWaterReading(), 'importDvecHotWater.xlsx');
//        $this->chat->message('dvecHot')->send();
//    }
//        public function imp()
//    {
//        Excel::import(new ImportERKCReading(), 'importErkc.xlsx');
//        $this->chat->message('erkc')->send();
//
//        Excel::import(new ImportDVECElectricityReading(), 'importDvecElectricity.xlsx');
//        $this->chat->message('dvecElectr')->send();
//
//        Excel::import(new ImportDVECHotWaterReading(), 'importDvecHotWater.xlsx');
//        $this->chat->message('dvecHot')->send();
//    }
}
