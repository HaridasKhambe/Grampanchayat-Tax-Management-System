<?php
require 'partials/dbconn.php'; // Database connection

// Check if the form is submitted with POST data
if (isset($_POST['DuserId']) && isset($_POST['DuserName'])) {


    // Get the userId and userName from the form submission
    $userId = mysqli_real_escape_string($conn, $_POST['DuserId']);
    $userName = mysqli_real_escape_string($conn, $_POST['DuserName']);

 //echo "<script>alert('Record of $userName with ID $userId has been deleted successfully!');</script>";

    // Step 1: Check and delete from each year table
    $checkYearTablesQuery = "SELECT year FROM yearsdata"; // Get all years from yearsData table
    $yearTablesResult = mysqli_query($conn, $checkYearTablesQuery);

    if ($yearTablesResult && mysqli_num_rows($yearTablesResult) > 0) {
        while ($row = mysqli_fetch_assoc($yearTablesResult)) {
            $year = $row['year'];
            $yearTable = "year$year"; // Get the table name (e.g., year2024)

            // Check if the user exists in the year table and delete the record if found
            $deleteFromYearTableQuery = "DELETE FROM $yearTable WHERE userId = '$userId'";
            mysqli_query($conn, $deleteFromYearTableQuery);
        }
    }


    // Step 3: Delete from the mainData table
    $deleteFromMainDataQuery = "DELETE FROM maindata WHERE userId = '$userId'";
    
    if (mysqli_query($conn, $deleteFromMainDataQuery)) {
        // Step 4: Success message and redirect
        echo "<script>alert('Record of $userName with ID $userId has been deleted successfully!');</script>";
        echo "<script>window.location.href = 'manage.php';</script>";
        exit();
    } else {
        // Error handling if the deletion fails
        echo "<script>alert('Error deleting the record: " . mysqli_error($conn) . "');</script>";
        echo "<script>window.location.href = 'manage.php';</script>";
        exit();
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If no POST data is received
    echo "<script>alert('Invalid request.');</script>";
    echo "<script>window.location.href = 'manage.php';</script>";
    exit();
}
?>
