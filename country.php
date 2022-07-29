<?php

include_once "../config/Database.php";


class Upload {

    public function storeItem($conn) {

        if(isset($_POST["Import"])){

            // Allowed mime types
            $fileMimes = array(
               'text/x-comma-separated-values',
               'text/comma-separated-values',
               'application/octet-stream',
               'application/vnd.ms-excel',
               'application/x-csv',
               'text/x-csv',
               'text/csv',
               'application/csv',
               'application/excel',
               'application/vnd.msexcel',
               'text/plain'
           );
           
           $filename = $_FILES["file"]["tmp_name"];
       
           if($_FILES["file"]["size"] > 0 && !empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)){ 
       
               require '../config/Database.php';
       
               $file = fopen($filename, "r");
       
               $data = [];
       
               while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
       
       
                   array_push($data, $getData);
       
               };

       
               for($i = 1; $i < count($data); $i++) {
                   
                   $sql = "INSERT INTO `country` (`continent`, `currency_code`, `iso2_code`, `iso3_code`, `iso_numeric_code`, `fips_code`, `calling_code`, `common_name`, `official_name`, `endonym`, `demonym`) VALUES('".$data[$i][0]."','".$data[$i][1]."','".$data[$i][2]."','".$data[$i][3]."','".$data[$i][4]."', '".$data[$i][5]."', '".$data[$i][6]."', '".$data[$i][7]."', '".$data[$i][8]."', '".$data[$i][9]."', '".$data[$i][10]."')";
                   $result = mysqli_query($conn, $sql);
       
                   if(!isset($result)){
                    echo "<script type='text/javascript'>
                        alert('Invalid File:Please Upload CSV File.');
                        window.location.href = 'first.php';
                        </script>";   
                    }
                    else {
                        echo "<script type='text/javascript'>
                        alert('CSV File has been successfully Imported.');
                        window.location.href = 'first.php';
                        </script>";
                    }
               } 
               
            } else {
               echo "Not the The required file type";
           }  
       }

    }

}



$upload_to_db = new Upload();

$upload_to_db->storeItem($conn);


?>