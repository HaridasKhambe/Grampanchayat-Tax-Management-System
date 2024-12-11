<?php

require 'partials/dbconn.php'; // Database connection
	

if (isset($_POST['addYear']) && isset($_POST['newYear'])) {

	$newYear = mysqli_real_escape_string($conn, $_POST['newYear']);

	if(!preg_match('/^\d{4}$/', $newYear))
	{
		echo "<script>alert('Error: Please enter a valid 4-digit year.');</script>";
		echo "<script>window.location.href = 'manage.php';</script>";
        exit();

	
	}
	$yearTable = "year$newYear";
	$checkYearQ = "SHOW TABLES LIKE '$yearTable'";
	$yearExist = mysqli_query($conn, $checkYearQ);

	if($yearExist && mysqli_num_rows($yearExist)>0){
		echo "<script>alert('Error: The table for the year $newYear already exists!');</script>";
		// Redirect back to the manage.php page and terminate the script
        echo "<script>window.location.href = 'manage.php';</script>";
        exit(); // Stop further execution

	}else
	{
		$fkConstraintName = "fk_userId_$newYear";

		$createTableQ = "

			CREATE TABLE $yearTable(
				userId VARCHAR(255) NOT NULL,
				hTaxPaid INT DEFAULT 0,
				hTaxDate DATETIME DEFAULT NULL,
				eleTaxPaid INT DEFAULT 0,
		        eleTaxDate DATE DEFAULT NULL,
		        wTaxPaid INT DEFAULT 0,
		        wTaxDate DATE DEFAULT NULL,
		        bTaxPaid INT DEFAULT 0,
		        bTaxDate DATE DEFAULT NULL,
		        totalTax INT DEFAULT 0,
		        PRIMARY KEY (userId),
		        CONSTRAINT $fkConstraintName FOREIGN KEY (userId) REFERENCES maindata (userId)
				)";

		if(mysqli_query($conn, $createTableQ)){

			$insertQuery = "INSERT INTO $yearTable(userId, hTaxPaid, hTaxDate, eleTaxPaid, eleTaxDate, wTaxPaid, wTaxDate, bTaxPaid, bTaxDate, totalTax) SELECT userId, 0, NULL,0, NULL,0, NULL,0, NULL,0 FROM maindata";

			if (mysqli_query($conn, $insertQuery)) {

				$insertYearDataQuery = "INSERT INTO yearsdata (year) VALUES ('$newYear')";

				if (mysqli_query($conn, $insertYearDataQuery)) {

                echo "<script>alert('Year $newYear added successfully, and data from maindata has been copied to it.');</script>";
                echo "<script>window.location.href = 'manage.php';</script>";
        		exit();

        		}else{

        			echo "<script>alert('Error adding year $newYear to yearsData table: " . mysqli_error($conn) . "');</script>";
        			echo "<script>window.location.href = 'manage.php';</script>";
        			exit();

        		}
            } else {
                echo "<script>alert('Error copying data from maindata to $yearTable: " . mysqli_error($conn) . "');</script>";
                echo "<script>window.location.href = 'manage.php';</script>";
        		exit();
            }

         }else
		{
		 echo "<script>alert('Error creating table for year $newYear: " . mysqli_error($conn) . "');</script>";
		 echo "<script>window.location.href = 'manage.php';</script>";
        exit();
		}

	


	// Close the database connection
    mysqli_close($conn);
	
}

}

?>