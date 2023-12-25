<?php
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Путь к текстовому файлу
//$filePath = Storage::path('dvecElectricity.txt');
$filePath = Storage::path('dvecHotWater.txt');

// Создание нового объекта Spreadsheet
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Получение активного листа
$sheet = $spreadsheet->getActiveSheet();

// Чтение данных из текстового файла
$data = file($filePath);

// Импорт данных в ячейки Excel
$row = 1;
foreach ($data as $line) {
    $values = explode(',', $line);
    $col = 1;
    foreach ($values as $value) {
        $trimmedValue = trim($value); // Удаление лишних пробелов
        $sheet->setCellValueByColumnAndRow($col, $row, $trimmedValue);
        $col++;
    }

    // Вычитание значений колонки B из колонки C и помещение результата в колонку D
    $colCValue = $sheet->getCellByColumnAndRow(3, $row)->getValue();
    $colBValue = $sheet->getCellByColumnAndRow(2, $row)->getValue();

    // Проверка наличия значений в колонках C и B перед вычитанием
    if (!empty($colCValue) && !empty($colBValue)) {
        // Проверка на отрицательное значение
        $result = max(0, $colCValue - $colBValue);

        // Явная замена запятой на точку
        $result = str_replace(',', '.', $result);

        $sheet->setCellValueByColumnAndRow(4, $row, $result);
    }

    $row++;
}

// Сохранение файла Excel
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save(Storage::path('importDvecHotWater.xlsx'));
//$writer->save(Storage::path('importDvecElectricity.xlsx'));
print 'чекай';
