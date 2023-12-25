<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('convert', function () {
    return view('convert');
});

Route::get('converterkc', function () {
    return view('covnerterkc');
});

Route::get('addbot', function () {
    return view('addbot');
});

Route::get('webhook', function () {

    try {
        Artisan::call('telegraph:set-webhook');
        return "webhook set";
    } catch (\Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
});

Route::get('migrate/fresh', function () {

    try {
        Artisan::call('migrate:fresh');
        return "fresh migration succeeded";
    } catch (\Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
});

Route::get('migrate', function () {
    try {
        Artisan::call('migrate');
        return "migration succeeded";
    } catch (\Exception $e) {
        return 'An error occurred: ' . $e->getMessage();
    }
});

Route::get('test', function () {
    return view('erkc');
});

Route::get('form', function () {
    return view('dvecForm');
});

Route::post('form', function () {
    dd($_POST);
});

//Route::get('bot', function () {
//    $phpBinaryFinder = new PhpExecutableFinder();
//    $phpBinaryPath = $phpBinaryFinder->find();
//
//    $input = new InputStream();
//    $input->write('6967319981:AAFHSiI7xCiWCr-EcwcEZInPCALYgtVDLVA');
//
////    $stream = fopen(base_path('comm.txt'), 'w+');
//
//    $process = new Process([$phpBinaryPath, base_path('artisan'), 'telegraph:new-bot']);
//    $process->setTimeout(2);
//    $process->enableOutput();
////    echo $process->getOutput();
//    try {
//        $process->setInput($input);
//        $process->start();
//
//        $input->write('TMR');
//
//        $input->close();
//        $process->wait();
//
//        echo $process->getOutput();
//    } catch (ProcessFailedException $exception) {
//        echo $exception->getMessage();
//    }
//});

//Route::get('bot', function () {
//    // Первый ввод
//    $input1 = 'первый инпут';
//    $output1 = exec("php " . base_path('artisan') . " telegraph:new-bot '$input1' 2>&1");
//
//    // Второй ввод
//    $input2 = 'второй инпут';
//    $output2 = exec("php " . base_path('artisan') . " telegraph:new-bot '$input2' 2>&1");
//
//    // Дополнительные вводы...
//
//    // Объединяем результаты
//    $result = $output1 . $output2; // Добавьте результаты дополнительных вводов, если нужно
//
//    echo $result;
//});
