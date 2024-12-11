
<?php

require_once 'partials/session_check.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<!-- libraries for the sheet export -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>





	<?php require 'partials/navbar.php'?>


<script type="text/javascript">


	function toggleTax(taxType, userId, year) {

    let taxField = '';
    let mainTaxField ='';
    let taxPaidColumn = '';
    let dateColumn = '';

    switch(taxType) {
        case 'houseTax':
            taxField = 'hTaxPaid';
            mainTaxField ='houseTax';
            taxPaidColumn = 'House Tax';
            dateColumn = 'hTaxDate';  // Correct date column name
            break;
        case 'electricityTax':
            taxField = 'eleTaxPaid';
            mainTaxField ='electricityTax';
            taxPaidColumn = 'Electricity Tax';
            dateColumn = 'eleTaxDate';  // Correct date column name
            break;
        case 'waterTax':
            taxField = 'wTaxPaid';
            mainTaxField ='waterTax';
            taxPaidColumn = 'Water Tax';
            dateColumn = 'wTaxDate';  // Correct date column name
            break;
        case 'businessTax':
            taxField = 'bTaxPaid';
            mainTaxField ='BusinessTax';
            taxPaidColumn = 'Business Tax';
            dateColumn = 'bTaxDate';  // Correct date column name
            break;
    }

    let userConfirmed = confirm(`Are you sure you want to toggle the status of ${taxPaidColumn} for user ID ${userId}?`);

    if (userConfirmed) {

        // Call PHP to update the database using AJAX
       $.ajax({
		    url: 'update_tax_status.php',  // PHP script URL
		    type: 'POST',
		    data: {
		        userId: userId,
		        taxField: taxField,
		        mainTaxField : mainTaxField,
		        dateColumn: dateColumn,
		        year: year,
		        //currentDate: formattedDate
		    },
		    success: function(response) {
		        // console.log('Response from PHP:', response); // Log PHP response
		        alert(`${mainTaxField} status updated successfully.`);
		        location.reload();  // Reload the page to reflect changes
		    },
		    error: function(xhr, status, error) {
		        // console.error('AJAX error:', status, error);
		        alert('Error updating status.');
		    }
		});
    } else {
        alert('Action canceled.');
    }
}

</script>


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
		<div class="card-header" style="background-color:#4a6e99; color: white;" >
			<h5 class="mb-0">Search Filters</h5>
		</div>
		<div class="card-body">
			<form id="filter-form" class="row g-3" method="POST" action="">
				<!-- Year Filter -->
                <div class="col-md-3">
				    <label for="filterYear" class="form-label">Year</label>
				    <select id="filterYear" class="form-control" name="year">
				        <option value="" disabled>All</option>
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
                <!-- Checkbox Filters -->
                <div class="col-md-4">
                    <label class="form-label">Taxes Paid Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="houseTaxPaid" name="houseTaxPaid" value="1" <?php echo ($houseTaxPaid == '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="houseTaxPaid">House Tax Paid</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="electricityTaxPaid" name="electricityTaxPaid" value="1" <?php echo ($electricityTaxPaid == '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="electricityTaxPaid">Electricity Tax Paid</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="waterTaxPaid" name="waterTaxPaid" value="1" <?php echo ($waterTaxPaid == '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="waterTaxPaid">Water Tax Paid</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="businessTaxPaid" name="businessTaxPaid" value="1" <?php echo ($businessTaxPaid == '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="businessTaxPaid">Business Tax Paid</label>
                    </div>
                </div>
				<!-- Apply Filter Button -->
				<div class="col-md-2 align-self-end">
					<button type="submit" class="btn btn-block" style="background-color:#2a3d66; color:white;" id="applyFilterBtn">Apply Filter</button>
				</div>
			</form>
		</div>
	</div>


<!-- tanle  -->

<!-- <div class="container"> -->
 	<div class="table-responsive ">
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
 					<th>Total</th>
 				</tr>

 			</thead>

 			<tbody>

 				<?php


 				$currentYear = date('Y');  // Get the current year

				// Get the filter values from the form (using POST method) and sanitize inputs
				$year = isset($_POST['year']) && !empty($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : $currentYear;  
				$section = isset($_POST['section']) && !empty($_POST['section']) ? mysqli_real_escape_string($conn, $_POST['section']) : '';  
				$houseTaxPaid = isset($_POST['houseTaxPaid']) ? mysqli_real_escape_string($conn, $_POST['houseTaxPaid']) : '';
				$electricityTaxPaid = isset($_POST['electricityTaxPaid']) ? mysqli_real_escape_string($conn, $_POST['electricityTaxPaid']) : '';
				$waterTaxPaid = isset($_POST['waterTaxPaid']) ? mysqli_real_escape_string($conn, $_POST['waterTaxPaid']) : '';
				$businessTaxPaid = isset($_POST['businessTaxPaid']) ? mysqli_real_escape_string($conn, $_POST['businessTaxPaid']) : '';


				/* // Debugging: Display values

				echo "Selected Year: " . $year . "<br>";  // Display the selected year
				echo "Selected Section: " . $section . "<br>";  // Display the selected section
				echo "House Tax Paid: " . $houseTaxPaid . "<br>";  // Display house tax filter
				echo "Electricity Tax Paid: " . $electricityTaxPaid . "<br>";  // Display electricity tax filter
				echo "Water Tax Paid: " . $waterTaxPaid . "<br>";  // Display water tax filter
				echo "Business Tax Paid: " . $businessTaxPaid . "<br>";  // Display business tax filter */

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

				if ($houseTaxPaid) {
				    $sql .= " AND hTaxPaid = 1";  // Assuming 1 means paid
				}

				if ($electricityTaxPaid) {
				    $sql .= " AND eleTaxPaid = 1";  // Assuming 1 means paid
				}

				if ($waterTaxPaid) {
				    $sql .= " AND wTaxPaid = 1";  // Assuming 1 means paid
				}

				if ($businessTaxPaid) {
				    $sql .= " AND bTaxPaid = 1";  // Assuming 1 means paid
				}


 					$result = mysqli_query($conn,$sql);

 					if (mysqli_num_rows($result)>0 && $result) {
 						$serialNumber = 1;
 						foreach ($result as $row) {

 							 // Determine the button text based on the paid status for each tax
			                //$houseTaxButtonText = $row['hTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
			               // $electricityTaxButtonText = $row['eleTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
			                //$waterTaxButtonText = $row['wTaxPaid'] == 1 ? 'Unpaid' : 'Paid';
			                //$businessTaxButtonText = $row['bTaxPaid'] == 1 ? 'Unpaid' : 'Paid'; 
 							
 							?>

 							<tr>
 								<td><?= $serialNumber ?></td>
 								<td><?= $row['userId'] ?></td>
 								<td><?= $row['name'] ?></td>
 								<td><?= $vadiName[$row['section']] ?></td>

 								<!-- House Tax Amount -->
									<td>
										<span style="color: <?= $row['hTaxPaid'] > 0 ? 'green' : 'red' ?>;">
											 <?= $row['houseTax'] ?>
										</span>
										&nbsp;&nbsp;
									
										<button class="btn btn-sm" style="background-color:#4a6e99; color: white";
										 onclick="toggleTax('houseTax', '<?= $row['userId'] ?>', '<?= $year ?>')" 
										 <?= ($row['houseTax'] == 0) ? 'disabled' : '' ?>><?= $row['hTaxPaid'] == 1 ? 'Unpaid' : 'Paid' ?></button>

									</td>
									<!-- Electricity Tax Amount -->
									<td>
										<span style="color: <?= $row['eleTaxPaid'] > 0 ? 'green' : 'red' ?>;">
											<?= $row['electricityTax'] ?>
										</span>&nbsp;&nbsp;
										<button class="btn btn-sm" style="background-color:#4a6e99; color: white"; 
										onclick="toggleTax('electricityTax', '<?= $row['userId'] ?>', '<?= $year ?>') "
										<?= ($row['electricityTax'] == 0) ? 'disabled' : '' ?>><?= $row['eleTaxPaid'] == 1 ? 'Unpaid' : 'Paid' ?></button>

									</td>
									<!-- Water Tax Amount -->
									<td>
										<span style="color: <?= $row['wTaxPaid'] > 0 ? 'green' : 'red' ?>;">
											<?= $row['waterTax'] ?>
										</span>&nbsp;&nbsp;
										<button class="btn btn-sm" style="background-color:#4a6e99; color: white"; 
										onclick="toggleTax('waterTax', '<?= $row['userId'] ?>', '<?= $year ?>')"
										<?= ($row['waterTax'] == 0) ? 'disabled' : '' ?>><?= $row['wTaxPaid'] == 1 ? 'Unpaid' : 'Paid' ?></button>

									</td>
									<!-- Business Tax Amount -->
									<td>
										<span style="color: <?= $row['bTaxPaid'] > 0 ? 'green' : 'red' ?>;">
											<?= $row['BusinessTax'] ?>
										</span>&nbsp;&nbsp;

				                        <button class="btn btn-sm" style="background-color:#4a6e99; color: white";
				                        onclick="toggleTax('businessTax', '<?= $row['userId'] ?>', '<?= $year ?>')"
				                        <?= ($row['BusinessTax'] == 0) ? 'disabled' : '' ?>><?= $row['bTaxPaid'] == 1 ? 'Unpaid' : 'Paid' ?></button>
									</td>
 								 <!-- Total Tax -->
				                    <td>
				                        <span style="color: <?= $row['totalTax'] > 0 ? 'green' : 'red' ?>;">
				                            <?=  $row['totalTax']?>
				                        </span>

				                    </td>
 								
 						
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
 					<th>Total</th>
 				</tr>
 			</tfoot>


 			
 		</table>

 		<!-- Export Button aligned to the right -->
    <div class="d-flex justify-content-end">
        <button class="btn btn-success" onclick="exportToExcel()">Export to Excel</button>
    </div>

 	</div>
 </div>


<!-- for table -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>

<script type="text/javascript">

	new DataTable('#mytable');

    function exportToExcel() {
        // Get the DataTable API instance
        let table = $('#mytable').DataTable();

        // Retrieve all the data from the table, including hidden rows
        let allData = table.rows().data();

        // Create a new HTML table element to build all rows for export
        let exportTable = document.createElement('table');
        let thead = document.createElement('thead');
        let tbody = document.createElement('tbody');

        // Append the table header
        let headerRow = table.columns().header().to$().map((_, el) => `<th>${el.innerText}</th>`).get().join('');
        thead.innerHTML = `<tr>${headerRow}</tr>`;
        exportTable.appendChild(thead);

        // Append all rows from the table
        allData.each(rowData => {
            let row = document.createElement('tr');
            rowData.forEach(cellData => {
                let cell = document.createElement('td');

                // Check if the cell has a button and a span for tax status processing
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = cellData;

                let amountSpan = tempDiv.querySelector('span');
                let button = tempDiv.querySelector('button');

                if (amountSpan && button) {
                    let amount = amountSpan.innerText.trim();
                    let status = button.innerText === 'Paid' ? 'Unpaid' : 'Paid'; // Reverse status
                    cell.innerText = `${amount} - ${status}`;
                } else {
                    cell.innerHTML = cellData; // Default for other cells
                }

                row.appendChild(cell);
            });
            tbody.appendChild(row);
        });

        exportTable.appendChild(tbody);

        // Create a new workbook
        let workbook = XLSX.utils.book_new();

        // Convert the constructed table to a worksheet
        let worksheet = XLSX.utils.table_to_sheet(exportTable);

        // Append the worksheet to the workbook
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

        // Define the file name
        let fileName = `Tax_Report_${new Date().toISOString().split('T')[0]}.xlsx`;

        // Write the workbook to an Excel file
        XLSX.writeFile(workbook, fileName);
   }


// 	function exportToExcel() {
//     // Get the table element
//     let table = document.getElementById('mytable');

//     // Create a new workbook
//     let workbook = XLSX.utils.book_new();

//     // Convert the table to a worksheet
//     let worksheet = XLSX.utils.table_to_sheet(table);

//     // Append the worksheet to the workbook
//     XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

//     // Define the file name
//     let fileName = `Tax_Report_${new Date().toISOString().split('T')[0]}.xlsx`;

//     // Write the workbook to an Excel file
//     XLSX.writeFile(workbook, fileName);
// }



</script>


</body>

</html>
	
</body>
</html>