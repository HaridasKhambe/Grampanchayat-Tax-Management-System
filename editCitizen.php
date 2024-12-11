<?php

require 'partials/dbconn.php';

if ($_POST['click_edit_btn']) {

    // echo "alert('hello');";
    
    $id = $_POST['id'];
    $arrayresult = [];


    //Read data from database
    $q3 = "SELECT * FROM maindata WHERE userId = '$id'";
    $fetch_q = mysqli_query($conn, $q3);

    if ($fetch_q && mysqli_num_rows($fetch_q) > 0) {
        // Fetch data as an associative array
        $row = mysqli_fetch_assoc($fetch_q);

        array_push($arrayresult, $row);

        header('content-type: application/josn');
        echo json_encode($arrayresult);

   }

}






?>