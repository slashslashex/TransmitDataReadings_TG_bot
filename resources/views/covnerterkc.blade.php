<?php
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Путь к текстовому файлу
$filePath = Storage::path('erkc.txt');

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

    // Вычитание значений столбца B из столбца C и запись результата в столбец F
    $colCValue = $sheet->getCellByColumnAndRow(3, $row)->getValue();
    $colBValue = $sheet->getCellByColumnAndRow(2, $row)->getValue();

    if (!empty($colCValue) && !empty($colBValue)) {
        $resultF = max(0, $colCValue - $colBValue);
        $sheet->setCellValueByColumnAndRow(6, $row, $resultF);
    }

    // Вычитание значений столбца D из столбца E и запись результата в столбец G
    $colEValue = $sheet->getCellByColumnAndRow(5, $row)->getValue();
    $colDValue = $sheet->getCellByColumnAndRow(4, $row)->getValue();

    if (!empty($colEValue) && !empty($colDValue)) {
        $resultG = max(0, $colEValue - $colDValue);
        $sheet->setCellValueByColumnAndRow(7, $row, $resultG);
    }

    $row++;
}

// Сохранение файла Excel
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save(Storage::path('importErkc.xlsx'));
print 'чекай';
