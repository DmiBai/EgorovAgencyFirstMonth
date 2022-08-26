<?php

require 'Office.php';
require $_SERVER['DOCUMENT_ROOT'] . '/excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MySpreadsheet
{
    private Writer $writer;
    private string $filePath;
    private Office $office;
    private Spreadsheet $spreadsheet;


    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->spreadsheet = IOFactory::load($this->filePath);
        $this->writer = new Writer($this->spreadsheet);
        $this->office = new Office();
    }

    public function ReadDrivers()
    {
        $this->spreadsheet->setActiveSheetIndex(0);
        $cells = $this->spreadsheet->getActiveSheet()->getCellCollection();

        for ($row = '2'; $row <= $cells->getHighestRow(); $row++) {
            $cell = $cells->get('B' . $row);
            $name = $cell->getValue();
            $cell = $cells->get('C' . $row);
            $hours = $cell->getValue();
            $cell = $cells->get('D' . $row);
            $prod = $cell->getValue();

            $this->office->addDriver(new Driver($name, $hours, $prod));
        }
        $cell = $cells->get('F2');
        $startDate = $cell->getValue();

        $this->office->setStartDate($startDate);
    }

    public function AddWaybills()
    {
        $this->spreadsheet->createSheet();
        $this->spreadsheet->setActiveSheetIndex(1);
        $this->spreadsheet->getActiveSheet()->setTitle('Waybills');
        $cells = $this->spreadsheet->getActiveSheet();

        $weeklySheet = $this->office->makeWeeklySheet();

        $driverNum = 0;
        foreach ($weeklySheet as $name => $dailySheet) {
            $newFilePath =  $_SERVER['DOCUMENT_ROOT'] . '/excel/public/files/roadbills/' . $name . '.xlsx';
            $fd = fopen($newFilePath, 'a+');
            fclose($fd);

            $secSpreadsheet = new Spreadsheet();
            $secCells = $secSpreadsheet->getActiveSheet();

            $num = 1;
            $date = new DateTime('1899-12-30');
            $date->modify('+' . $this->office->getStartDate() . 'day');

            $cells->setCellValue('A' . $num + $driverNum, $name);
            $cells->setCellValue('B' . $num + $driverNum, 'Hours');
            $cells->setCellValue('C' . $num + $driverNum, 'Date');

            $secCells->setCellValue('A' . $num , $name);
            $secCells->setCellValue('B' . $num, 'Hours');
            $secCells->setCellValue('C' . $num, 'Date');

            foreach ($dailySheet as $item) {
                $date->modify('+' . ($num - 1) . 'day');

                $cells->setCellValue('B' . ($num + $driverNum + 1), $item);
                $cells->setCellValue('C' . ($num + $driverNum + 1), $date->format('d/m/Y'));

                $secCells->setCellValue('B' . ($num + 1), $item);
                $secCells->setCellValue('C' . ($num + 1), $date->format('d/m/Y'));

                $date->modify('-' . ($num - 1) . 'day');
                $num++;
            }
            $driverNum += 10;

            $writer = new Writer($secSpreadsheet);
            $writer->save($newFilePath);
        }
        $this->writer = new Writer($this->spreadsheet);
        $this->writer->save($this->filePath);
    }
}