<?php
// Include the database connection
 require 'partials/dbconn.php';


// Check if all required POST parameters are set
if (isset($_POST['userId']) && isset($_POST['taxField']) && isset($_POST['mainTaxField']) && isset($_POST['dateColumn']) && isset($_POST['year'])) {
    // Sanitize input to prevent SQL injection
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $taxField = mysqli_real_escape_string($conn, $_POST['taxField']);
    $mainTaxField = mysqli_real_escape_string($conn, $_POST['mainTaxField']);
    $dateColumn = mysqli_real_escape_string($conn, $_POST['dateColumn']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    // $currentDate = mysqli_real_escape_string($conn, $_POST['currentDate']);

    // Set the default timezone to India Standard Time (IST)
    date_default_timezone_set('Asia/Kolkata');

    // Get the current date and time in the format 'YYYY-MM-DD HH:MM:SS'
    $currentDate = date('Y-m-d H:i:s');


    

    // Check if year table exists to prevent SQL errors
    $yearTable = "year" . $year; // Ensure $year is treated as an integer to avoid injection
    // $tableExistsQuery = "SHOW TABLES LIKE '$yearTable'";
    // $tableExistsResult = mysqli_query($conn, $tableExistsQuery);

    // if ($tableExistsResult && mysqli_num_rows($tableExistsResult) > 0) {
        // Fetch the current tax status for the user
        $sql = "SELECT $taxField FROM $yearTable WHERE userId = '$userId'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $currentTaxStatus = $row[$taxField];

            // Fetch the amount from mainData
            $fetchAmountQuery = "SELECT maindata.$mainTaxField 
                                 FROM maindata 
                                 INNER JOIN $yearTable 
                                 ON year$year.userId = maindata.userId 
                                 WHERE maindata.userId = '$userId'";
            $fetchAmountResult = mysqli_query($conn, $fetchAmountQuery);

            if ($fetchAmountResult) {
                $amountRow = mysqli_fetch_assoc($fetchAmountResult);
                $taxAmount = $amountRow[$mainTaxField];

                // Toggle the tax status (if 1, set to 0, if 0, set to 1)
                $newTaxStatus = ($currentTaxStatus == 1) ? 0 : 1;

                // Update the tax status and corresponding date column
                $updateSql = "UPDATE $yearTable 
                              SET $taxField = '$newTaxStatus', 
                                  $dateColumn = '$currentDate' 
                              WHERE userId = '$userId'";
                if (mysqli_query($conn, $updateSql)) {
                    // Adjust the total amount based on the new tax status
                    $adjustment = ($newTaxStatus == 1) ? $taxAmount : -$taxAmount;
                    $updateTotalSql = "UPDATE $yearTable 
                                       SET totalTax = totalTax + $adjustment 
                                       WHERE userId = '$userId'";
                    if (mysqli_query($conn, $updateTotalSql)) {
                        // echo "Tax status and total amount updated successfully.";
                    } else {
                        // echo "Error updating total tax: " . mysqli_error($conn);
                    }
                } else {
                    // echo "Error updating tax status: " . mysqli_error($conn);
                }
            } else {
                // echo "No tax amount found for the user in mainData.";
            }
        } else {
            // echo "No records found for the user in the year table.";
        }
    // } else {
    //     echo "Year table does not exist.";
    // }
} else {
    // echo "Required parameters are missing.";
}
?>
