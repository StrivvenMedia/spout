<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
echo "test";
/**
*   Excel Builder
*/

$testArray = [];
export($testArray);

$filename = "export";
$fileExtension = "xlsx";

function export($testArray){
    echo "function is run";
    $spout = build($testArray);
    $spout->close();
    
}

function build($info){
    echo "build is run";
    
    $writer = WriterEntityFactory::createXLSXWriter();
    $filename = $filename . "_" . date("m_d_Y") . "_" . substr(md5(mt_rand()), 0, 6) . "." . $fileExtension;
    echo $filename;
    $firstLineStyle = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(11)
        ->build();

    $dataLineStyle = (new StyleBuilder())
        ->setFontSize(11)
        ->build();
    
    $writer->openToBrowser($filename);

    for($j = 0; $j < sizeof($info); $j+=1){
        if ($j == 0){
            $firstSheet = $writer->getCurrentSheet();
            $firstSheet->setName($info[$j]["title"]);
        }else{
            $newSheet = $writer->addNewSheetAndMakeItCurrent();
            $newSheet->setName($info[$j]["title"]);
        }

        for($i = 0; $i < sizeof($info[$j]["contents"]); $i+=1){
            //test if the rows are going to be grouped
            $sheet = $info[$j]["contents"][$i];
            $groupingOn = null;
            
            
            if(isset($info[$j]["grouping"])){
                $groupingOn = $info[$j]["grouping"][$i];
            }
            if ($i == 0){
                $row = WriterEntityFactory::createRowFromArray($sheet,$firstLineStyle,$groupingOn);
            }else{
                $row = WriterEntityFactory::createRowFromArray($sheet,$dataLineStyle,$groupingOn);
            }
            $writer->addRow($row);
        

        }
        
    }
    
    return $writer;
}

