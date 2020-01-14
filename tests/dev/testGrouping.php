<?php
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
$firstLineStyle = (new StyleBuilder())
    ->setFontBold()
    ->setFontSize(11)
    ->build();

    $dataLineStyle = (new StyleBuilder())
    ->setFontSize(11)
    ->build();

//echo var_dump($testData["contents"]);
//echo var_dump($testData["grouping"]);

/*Todo: change to foreach*/
for($i = 0; $i < sizeof($testData["contents"]); $i+=1){
    //test if the rows are going to be grouped
    $sheet = $testData["contents"][$i];
    $groupingOn = $testData["grouping"][$i];
    if ($i == 0){

        $row = WriterEntityFactory::createRowFromArray($sheet,$firstLineStyle,$groupingOn);

    }else{
        $row = WriterEntityFactory::createRowFromArray($sheet,$dataLineStyle,$groupingOn);

    }
    $writer->addRow($row);

}

/** Add the row to the writer */
$writer->close();
