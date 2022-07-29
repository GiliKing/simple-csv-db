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
                   
                   $sql = "INSERT INTO `currency` (`iso_code`, `iso_numeric_code`, `common_name`, `official_name`, `symbol`) VALUES('".$data[$i][0]."','".$data[$i][1]."','".$data[$i][2]."','".$data[$i][3]."','".$data[$i][4]."')";
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