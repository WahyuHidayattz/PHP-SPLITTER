<?php

function readCSV($path)
{
    $rows = array_map('str_getcsv', file($path));
    $header = array_shift($rows);
    $csv = [];
    foreach ($rows as $row) {
        $csv[] = array_combine($header, $row);
    }
    return $csv;
}

function writeCSV($array, $targetfilename)
{
    $file = fopen($targetfilename, "w");
    foreach ($array as $data) {
        fputcsv($file, $data);
    }

    fclose($file);
}

echo "PHP SPLITTING V1\n";
echo "Created by : Wahyu Hidayat\n";
echo "Telegram   : @wahyu135\n";
echo "==========================\n";
echo "Choose Menu : \n";
echo "1 - Split CSV File\n";
echo "2 - Combine CSV Split\n";
echo "==========================\n";
echo "Your choice :";

$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

switch ($choice) {
    case 1:
        echo "SPLIT CSV FILE\n";
        echo "----------------------\n";

        echo "Enter file name : ";
        $handle = fopen("php://stdin", "r");
        $file = trim(fgets($handle));

        echo "Enter column name : ";
        $handle = fopen("php://stdin", "r");
        $column = trim(fgets($handle));

        $data = readCSV($file);
        $data_col = array_unique(array_column($data, $column));

        $folder = str_replace(" ", "_", strtoupper($column)) . "_SPLITTED";

        if (!file_exists($folder)) {
            mkdir($folder);
        }

        echo "\nBeginning...\n";
        echo "----------------------\n";

        foreach ($data_col as $col) {
            $export = [];
            foreach ($data as $value) {
                if ($value[$column] == $col) {
                    $export[] = $value;
                }
            }
            $header = array_keys($export[0]);
            array_unshift($export, $header);
            writeCSV($export, $folder . "/" . $col . "_SPLIT.csv");
            echo $col . " Has done exported to csv.\n";
        }
        echo "----------------------\n";
        echo "All files has done exported.\n";
        echo "----------------------\n";
        echo "Saved in folder : \n" . $folder;
        break;
    case 2:
        echo "COMBINE CSV (SPLITTED FILE)\n";
        echo "----------------------\n";

        echo "Enter folder name : ";
        $handle = fopen("php://stdin", "r");
        $folder = trim(fgets($handle));
        $files = glob($folder . "/*.csv");
        $data = [];
        echo "\nBeginning...\n";
        echo "----------------------\n";
        foreach ($files as $file) {
            $t_file = readCSV($file);
            foreach ($t_file as $value) {
                $data[] = $value;
            }
            echo substr($file, strlen($folder) + 1) . " done.\n";
        }
        echo "----------------------\n";
        echo "Writting into CSV single file...\n";
        $header = array_keys($data[0]);
        array_unshift($data, $header);
        writeCSV($data, $folder . ".csv");
        echo "----------------------\n";
        echo "All file has done combining.\n";
        echo "File name : \n" . $folder . ".csv";
        break;
    default:
        echo "Wrong choice!\n";
}
