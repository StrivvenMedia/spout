<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

/** The following is a test file to test
 *   new features added to this spout repository
 */
require_once 'src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

/** Setup for testing */
$json = file_get_contents('tests/dev/TestData/TestSheet2.json');
$testData =  json_decode($json, true);
$filePath = 'tests/dev/test_file.xlsx';

/** Xslx creation */
$writer = WriterEntityFactory::createXLSXWriter();
$writer->openToFile($filePath);

/** Create a base style with the StyleBuilder */
$baseStyle = (new StyleBuilder())
    ->setFontSize(11)
    ->build();

/*Todo: */
for($j = 0; $j < sizeof($testData); $j+=1){
    if ($j == 0){
        $firstSheet = $writer->getCurrentSheet();
        $firstSheet->setName($testData[$j]["title"]);
    }else{
        $newSheet = $writer->addNewSheetAndMakeItCurrent();
        $newSheet->setName($testData[$j]["title"]);
    }
    for($i = 0; $i < sizeof($testData["contents"]); $i+=1){
        //test if the rows are going to be grouped
        $sheet = $testData["contents"][$i];
        $groupingOn = $testData["grouping"][$i];
        if ($i == 0){
            $firstLineStyle = clone $baseStyle;
            $firstLineStyle->setFontBold();
            $row = WriterEntityFactory::createRowFromArray($sheet,$firstLineStyle,$groupingOn);
        }else{
            
            $row = WriterEntityFactory::createRowFromArray($sheet,$baseStyle,$groupingOn);

        }
        $writer->addRow($row);
    }
}

/** Add the row to the writer */
$writer->close();
