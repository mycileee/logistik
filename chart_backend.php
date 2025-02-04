<?php

require 'function.php';
require 'cek.php';


    $functionName = htmlspecialchars($_GET['functionName']);

    switch ($functionName) {
        case 'getDataStock':
            getDataStock();
            break;

        case 'getDataLainnya':
            getDataLainnya();
            break;

        default:

            break;
    }

    function getDataStock()
    {
        global $conn;

        $data = [];
        $query = mysqli_query($conn, "SELECT * FROM stock");

        while($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        
        echo json_encode($data);
    }   


   