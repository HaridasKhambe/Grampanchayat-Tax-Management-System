<?php
require_once 'partials/session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Track</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<?php require 'partials/navbar.php'?>

<style>
.container {
    padding-top: 90px; /* Adjust the value based on your navbar height */
  }
</style>

</head>
<body>

<?php 
	require 'partials/dbconn.php'; 

	$queryYear = "SELECT DISTINCT year FROM yearsdata ORDER BY year ASC";
$result = mysqli_query($conn, $queryYear);

if (!$result) {
    // Handle any query errors
    echo "<script>alert('Error fetching years: " . mysqli_error($conn) . "');</script>";
}

// Create an array to store the years
$years = [];
while ($row = mysqli_fetch_assoc($result)) {
    $years[] = $row['year'];
}

// Close the result set
mysqli_free_result($result);

				$vadiName = [
					"A" => "Danda Mohalla",
				    "B" => "Jadhavwadi Jamat Mohalla",
				    "C" => "Radhakrishna Wadi & Vishvadip Wadi",
				    "D" => "Sakade Wadi",
				    "E" => "Khambewadi",
				    "F" => "Madhaliwadi",
				    "G" => "Khairakond",
				    "H" => "Hotels"
				];


	// Process the form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $year = isset($_POST['year']) ? $_POST['year'] : '';
        $section = isset($_POST['section']) ? $_POST['section'] : '';
        $houseTaxPaid = isset($_POST['houseTaxPaid']) ? $_POST['houseTaxPaid'] : '';
        $electricityTaxPaid = isset($_POST['electricityTaxPaid']) ? $_POST['electricityTaxPaid'] : '';
        $waterTaxPaid = isset($_POST['waterTaxPaid']) ? $_POST['waterTaxPaid'] : '';
        $businessTaxPaid = isset($_POST['businessTaxPaid']) ? $_POST['businessTaxPaid'] : '';
    } else {
        $year = date('Y'); // Default to current year
        $section = '';
        $houseTaxPaid = '';
        $electricityTaxPaid = '';
        $waterTaxPaid = '';
        $businessTaxPaid = '';
    }
?>

<div class="container">
	<!-- Filter Section -->
	<div class="card mb-4">
		<div class="card-header text-white"  style="background-color:#4a6e99; color: white;">
			<h5 class="mb-0">Track the data</h5>
		</div>
		<div class="card-body">
			<form id="filter-form" class="row g-3" method="POST" action="">
				<!-- Year Filter -->
                <div class="col-md-3">
				    <label for="filterYear" class="form-label">Year</label>
				    <select id="filterYear" class="form-control" name="year">
				        <option value="">All</option>
				        <?php
				        // Loop through each year in the array and create an option
				        foreach ($years as $Lyear) {
				            echo "<option value='$Lyear' " . (($Lyear == $year) ? 'selected' : '') . ">$Lyear</option>";
				        }
				        ?>
				    </select>
				</div>
                <!-- Section Filter -->
                <div class="col-md-3">
                    <label for="filterSection" class="form-label">Ward</label>
                    <select id="filterSection" class="form-control" name="section">
                        <option value="">All</option>
                        <?php
                        foreach ($vadiName as $key => $value) {
                            echo "<option value='$key' " . ($section == $key ? 'selected' : '') . ">$value</option>";
                        }
                        ?>
                    </select>
                </div>
         
				<div class="col-md-2 mt-4 align-self-end mb-3">
					<button type="submit" class="btn btn-block" style="background-color:#2a3d66; color:white;" id="applyFilterBtn">Apply Filter</button>
				</div>
			</form>
		</div>
	</div>


<!-- tanle  -->

<!-- <div class="container"> -->
 	<div class="table-responsive">
 		<table id="mytable" class="table table-striped table-bordered text-nowrap" style="width:100%">
 			<thead>
 				<tr>
 					<th>S.No</th>
 					<th>House No.</th>
 					<th>Name</th>
 					<th>Ward</th>
 					<th>House Tax</th>
 					<th>Electricity Tax</th>
 					<th>Water Tax</th>
 					<th>Business Tax</th>
 					<!-- <th>Total</th> -->
 				</tr>

 			</thead>

 			<tbody>

 				<?php


 				$currentYear = date('Y');  // Get the current year

				// Get the filter values from the form (using POST method) and sanitize inputs
				$year = isset($_POST['year']) && !empty($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : $currentYear;  
				$section = isset($_POST['section']) && !empty($_POST['section']) ? mysqli_real_escape_string($conn, $_POST['section']) : '';  
	


				// Build the table name dynamically
				$tableName = "year" . $year;  // Create the table name dynamically (e.g., year2024)

				// Check if the table exists
				$tableCheckQuery = "SHOW TABLES LIKE '$tableName'";
				$tableCheckResult = mysqli_query($conn, $tableCheckQuery);

				if (mysqli_num_rows($tableCheckResult) == 0) {
				    // If table does not exist
				    echo "<div class='alert alert-danger' role='alert'>
				            Error: Data for '$tableName' not found. Please contact support or try again later.
				          </div>";
				}else

				{


			
				// Build the SQL query dynamically
				$sql = "SELECT * FROM maindata INNER JOIN $tableName ON $tableName.userId = maindata.userId WHERE 1";

				// Apply filters if they are set
				if ($section) {
				    $sql .= " AND section = '$section'";
				}


 					$result = mysqli_query($conn,$sql);

 					if (mysqli_num_rows($result)>0 && $result) {
 						$serialNumber = 1;
 						foreach ($result as $row) {

 							// Determine the button text based on the paid status for each tax
			                $houseTaxButtonText = $row['hTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
			                $electricityTaxButtonText = $row['eleTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
			                $waterTaxButtonText = $row['wTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
			                $businessTaxButtonText = $row['bTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
 							
 							?>

 							<tr>
 								<td><?= $serialNumber ?></td>
 								<td><?= $row['userId'] ?></td>
 								<td><?= $row['name'] ?></td>
 								<td><?= $vadiName[$row['section']] ?></td>

 								<!-- House Tax Amount -->
									<td>
									    <div style="display: flex; flex-direction: column; align-items: start; min-width: 100px;">
									        <!-- Tax Amount -->
									        <span style="color: <?= $row['hTaxPaid'] > 0 ? 'green' : 'red' ?>; font-weight: bold;">
									            <?= $row['hTaxPaid'] > 0 ? "+ ".$row['houseTax'] : "- ".$row['houseTax'] ?>
									        </span>

									        <!-- Date or Not Paid -->
									        <span style="white-space: nowrap;">
											    <?php 
											    if ($row['houseTax'] == 0) {
											        // Amount is 0, display "-"
											        echo '-';
											    } else {


											        // Amount is non-zero, check if paid
											        if ($row['hTaxPaid']) {
											            // Paid: Display date
											            echo date('D, g:i A', strtotime($row['hTaxDate'])) . "<br>" . date('d/m/Y', strtotime($row['hTaxDate']));
											        } else {
											            // Unpaid: Display "Not Paid"
											            echo '<span style="color: red;">Not Paid</span>';
											        }
											    }
											    ?>
											</span>

									    </div>
									</td>
									<!-- Electricity Tax Amount -->
									<td>
									    <div style="display: flex; flex-direction: column; align-items: start; min-width: 100px;">
									        <!-- Tax Amount -->
									        <span style="color: <?= $row['eleTaxPaid'] > 0 ? 'green' : 'red' ?>; font-weight: bold;">
									            <?= $row['eleTaxPaid'] > 0 ? "+ ".$row['electricityTax'] : "- ".$row['electricityTax'] ?>
									        </span>

									        <!-- Date or Not Paid -->
									        <span style="white-space: nowrap;">
									        	<?php
									        	if($row['electricityTax']==0)
									        	{
									        		echo '-';
									        	}else{

									        		if($row['eleTaxPaid']){
									        			echo date('D, g:i A', strtotime($row['eleTaxDate'])). "<br>".date('d/m/Y', strtotime($row['eleTaxDate']));
									        		}else{
									        			 echo '<span style="color: red;">Not Paid</span>';
									        		}

									        	}
									           ?>
									        </span>
									    </div>
									</td>

									<!-- Water Tax Amount -->
										<td>
										    <div style="display: flex; flex-direction: column; align-items: start; min-width: 100px;">
										        <!-- Tax Amount -->
										        <span style="color: <?= $row['wTaxPaid'] > 0 ? 'green' : 'red' ?>; font-weight: bold;">
										            <?= $row['wTaxPaid'] > 0 ? "+ ".$row['waterTax'] : "- ".$row['waterTax'] ?>
										        </span>

										        <span style="white-space: nowrap;">
												    <?php 
												    if ($row['waterTax'] == 0) {
												        // Amount is 0, display "-"
												        echo '-';
												    } else {
												        // Amount is non-zero, check if paid
												        if ($row['wTaxPaid']) {
												            // Paid: Display date
												            echo date('D, g:i A', strtotime($row['wTaxDate'])) . "<br>" . date('d/m/Y', strtotime($row['wTaxDate']));
												        } else {
												            // Unpaid: Display "Not Paid"
												            echo '<span style="color: red;">Not Paid</span>';
												        }
												    }
												    ?>
												</span>
										    </div>
										</td>

										<!-- Business Tax Amount -->
										<td>
										    <div style="display: flex; flex-direction: column; align-items: start; min-width: 100px;">
										        <!-- Tax Amount -->
										        <span style="color: <?= $row['bTaxPaid'] > 0 ? 'green' : 'red' ?>; font-weight: bold;">
										            <?= $row['bTaxPaid'] > 0 ? "+ ".$row['BusinessTax'] : "- ".$row['BusinessTax'] ?>
										        </span>

										        <!-- Date or Not Paid -->
										        <span style="white-space: nowrap;">
												    <?php 
												    if ($row['BusinessTax'] == 0) {
												        // Amount is 0, display "-"
												        echo '-';
												    } else {
												        // Amount is non-zero, check if paid
												        if ($row['bTaxPaid']) {
												            // Paid: Display date
												            echo date('D, g:i A', strtotime($row['bTaxDate'])) . "<br>" . date('d/m/Y', strtotime($row['bTaxDate']));
												        } else {
												            // Unpaid: Display "Not Paid"
												            echo '<span style="color: red;">Not Paid</span>';
												        }
												    }
												    ?>
												</span>
										    </div>
										</td>
 								 <!-- Total Tax -->
				        
 						
 									<form method="POST" action="" name="action-form" id="action-form">
 							
									<input type="hidden" class="confirmationInput" name="confirmation" value="">
									</form>

 								</td>

 							</tr>

 							<?php
 							$serialNumber++;
 						}

 					}else
 					{
						 // Example of handling a "table not found" error with Bootstrap
						echo "<div class='alert alert-primary' role='alert'>
						     Record not found for the selected Filter.
						</div>";


 						?>


 						<?php
 					}

 			}

 				?>
 				
 			</tbody>

 			<tfoot>
 				<tr>
 					<th>S.No</th>
 					<th>House No.</th>
 					<th>Name</th>
 					<th>Ward</th>
 					<th>House Tax</th>
 					<th>Electricity Tax</th>
 					<th>Water Tax</th>
 					<th>Business Tax</th>
 					<!-- <th>Total</th> -->
 				</tr>
 			</tfoot>


 			
 		</table>
 	</div>
 </div>


<!-- for table -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>

<script type="text/javascript">
	new DataTable('#mytable');
</script>


</body>

</html>
	
</body>
</html>