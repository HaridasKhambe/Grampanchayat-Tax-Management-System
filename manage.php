<?php
require_once 'partials/session_check.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage</title>


	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<?php require 'partials/navbar.php'?>
<style>
.container {
    padding-top: 90px; /* Adjust the value based on your navbar height */
  }


 #updateCitizen
{
	z-index: 10100;

}
#addCitizenModal{
	z-index: 10100;
}

</style>

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


       // Initialize section variable from POST request
		$sectionSet = isset($_POST['sectionSet']) ? $_POST['sectionSet'] : '';
     
       
    } else {
        $sectionSet = ''; // Default to current year
    
    
    }

   

    mysqli_close($conn); 
?>


<script type="text/javascript">
	$(document).ready(function()
{
	$('.openModalBtn').click(function (e)
	{
		e.preventDefault();
		var cId = this.value;
		// console.log(cId);

		$.ajax({
        method: "POST",
        url: "editCitizen.php",
        data: {
            'click_edit_btn': true,
            'id': cId
        },
        success: function(response) {
        	var jsonData = JSON.parse(response);
           
            $.each(jsonData, function(key, value){

            	$('#HuserId').val(value['userId']);
            	$('#ed-name').val(value['name']);
			    $('#ed-section').val(value['section']); // Set the value in the dropdown
			    $('#ed-section option[value="' + value['section'] + '"]').prop('selected', true); // Ensure the correct option is selected
            	$('#ed-houseTax').val(value['houseTax']);
            	$('#ed-electricityTax').val(value['electricityTax']);
            	$('#ed-waterTax').val(value['waterTax']);
            	$('#ed-businessTax').val(value['BusinessTax']);
            	
            });

            $('#updatecust').modal('show');
	 }

    });

	});
});
</script>
<?php

require 'partials/dbconn.php'; // Database connection

if (isset($_POST['updateCitizen'])) {

    $userId = $_POST['HuserId']; // Hidden field input

    // Fetching form data
    $name = $_POST['ed-name'];
    $section = $_POST['ed-section'];
    $houseTax = $_POST['ed-houseTax'];
    $electricityTax = $_POST['ed-electricityTax'];
    $waterTax = $_POST['ed-waterTax'];
    $businessTax = $_POST['ed-businessTax'];

    // Handle empty values for integer columns
    $houseTax = empty($houseTax) ? 0 : $houseTax;
    $electricityTax = empty($electricityTax) ? 0 : $electricityTax;
    $waterTax = empty($waterTax) ? 0 : $waterTax;
    $businessTax = empty($businessTax) ? 0 : $businessTax;

    // Update query
    $query = "UPDATE maindata 
              SET name = '$name', section = '$section', 
                  houseTax = $houseTax, electricityTax = $electricityTax, 
                  waterTax = $waterTax, BusinessTax = $businessTax 
              WHERE userId = '$userId'";

    // Execute the query
    $updateQuery = mysqli_query($conn, $query);

    if (!$updateQuery) {
        // Error in query execution
        echo "<script>alert('Error in updating citizen details: " . mysqli_error($conn) . "');</script>";
    } else {
        // Successful update
        echo "<script>alert('Details for Citizen Id " . $userId . " updated successfully!');</script>";

        echo "<script> document.getElementById('useridSet').value = '$Uid';  </script>";
        //echo "<script>window.location.href = 'manage.php?userid=" . urlencode($Uid) . "';</script>";
    }
}

mysqli_close($conn); // Close the connection
?>


<?php
require 'partials/dbconn.php'; // Database connection

if (isset($_POST['addCitizenModal'])) {
    // Get the form data
    $yearToAdd = mysqli_real_escape_string($conn, $_POST['yearValue']);
    $CId = mysqli_real_escape_string($conn, $_POST['ad-citizenId']);
    $name = mysqli_real_escape_string($conn, $_POST['ad-name']);
    $section = mysqli_real_escape_string($conn, $_POST['ad-section']);
    $houseTax = !empty($_POST['ad-houseTax']) ? mysqli_real_escape_string($conn, $_POST['ad-houseTax']) : 0;
    $electricityTax = !empty($_POST['ad-electricityTax']) ? mysqli_real_escape_string($conn, $_POST['ad-electricityTax']) : 0;
    $waterTax = !empty($_POST['ad-waterTax']) ? mysqli_real_escape_string($conn, $_POST['ad-waterTax']) : 0;
    $businessTax = !empty($_POST['ad-businessTax']) ? mysqli_real_escape_string($conn, $_POST['ad-businessTax']) : 0;

    // Validate year input
    if (empty($yearToAdd) || !ctype_digit($yearToAdd)) {
        echo "<script>alert('Invalid year selected.');</script>";
        exit();
    }

    // Check if a citizen with the same ID already exists
    $checkQuery = "SELECT * FROM maindata WHERE userId= '$CId'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Error: Citizen with the ID $CId and name $name already exists!');</script>";
    } else {
        // Insert into mainData
        $sqladd = "INSERT INTO maindata (userId, name, section, houseTax, electricityTax, waterTax, BusinessTax) 
                   VALUES ('$CId', '$name', '$section', '$houseTax', '$electricityTax', '$waterTax', '$businessTax')";

        if (mysqli_query($conn, $sqladd)) {
            // Check if year table exists
            $yearTable = "year$yearToAdd";
            $checkYearTableQuery = "SHOW TABLES LIKE '$yearTable'";
            $yearTableExists = mysqli_query($conn, $checkYearTableQuery);

            if ($yearTableExists && mysqli_num_rows($yearTableExists) == 1) {
                // Insert into year table
                $insertYearDataQuery = "
                    INSERT INTO $yearTable (userId, hTaxPaid, hTaxDate, eleTaxPaid, eleTaxDate, wTaxPaid, wTaxDate, bTaxPaid, bTaxDate, totalTax) 
                    VALUES ('$CId', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0)";
                if (!mysqli_query($conn, $insertYearDataQuery)) {
                    echo "<script>alert('Error adding citizen to year table $yearTable: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Selected year table $yearTable is not present in the database!');</script>";
            }

            // Show success alert
            echo "<script>alert('Citizen added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding citizen: " . mysqli_error($conn) . "');</script>";
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>



</head>
<body>


<div class="container">
	<!-- Filter Section -->
	<div class="card mb-4">
		<div class="card-header text-white"  style="background-color:#4a6e99; color: white;">
			<h5 class="mb-0">Seach User By Ward or House No.</h5>
		</div>
		<div class="card-body">
			<form id="filter-form" class="row g-3" method="POST" action="">
		
                <!-- userid filed -->
                <div class="col-md-3 mb-3">
    
					    <select id="sectionSet" class="form-control" name="sectionSet">
				            <option value="">All</option>
				            <?php
				            // Assuming $vadiName is an array with section mappings
				            foreach ($vadiName as $key => $value) {
				                // Check if the current section is selected
				                echo "<option value='$key' " . ($sectionSet == $key ? 'selected' : '') . ">$value</option>";
				            }
				            ?>
				        </select>

                   <!-- <input type="text" class="form-control" value = '' name="userid" id="userid" placeholder="enter userId"> -->     
                </div>

         
				<div class="col-md-2 align-self-end mb-3">
					<button type="submit" class="btn btn-block" style="background-color:#2a3d66; color:white;" id="applyFilterBtn">Seach Citizen</button>
				</div>

				<div class="col-md-2 align-self-end mb-3">
					<button type="button" name="add-btn" class="btn btn-success btn-block" data-toggle="modal" data-target="#addCitizenModal" id="add-btn" value="<?=$data['userId'] ?>">Add Citizen</button>
				</div>

			</form>
		</div>
	</div>

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
 					<th>Action</th>
 				</tr>

 			</thead>

 			<tbody>
					<?php
						require 'partials/dbconn.php';

						// Initialize variables to store form inputs
						$section = isset($_POST['sectionSet']) ? $_POST['sectionSet'] : '';
						$result = [];

						if (!empty($section)) {
						    // Escape user input to prevent SQL injection
						    $section = mysqli_real_escape_string($conn, $section);

						    $sql = "SELECT userId,name,section,houseTax,houseTax,electricityTax,waterTax,BusinessTax FROM maindata WHERE section = '$section'";

						    // Execute the query
						    $queryResult = mysqli_query($conn, $sql);

						    

						    if ($queryResult && mysqli_num_rows($queryResult) > 0) {
						        // Fetch all rows into an associative array
						        $result = mysqli_fetch_all($queryResult, MYSQLI_ASSOC);

						        
						    } else {
						        echo "<div class='alert alert-warning'>No data found for the given filters.</div>";
						    }
						} else {
						    echo "<div class='alert alert-warning'>Please Select The Section</div>";
						}

						// Check if the result contains data
						if (!empty($result)) {
						    $serialNumber = 1; // Initialize serial number before the loop
						    foreach ($result as $row) {
						        ?>
						        <tr>
						        

						            <td><?= $serialNumber++; ?></td> <!-- Increment the serial number for each row -->
						            <td><?= $row['userId']; ?></td>
						            <td><?= $row['name']; ?></td>
						            <td><?= $vadiName[$row['section']]; ?></td> <!-- Fallback to section if $vadiName is not set -->
						            <td><?= $row['houseTax']; ?></td>
						            <td><?= $row['electricityTax']; ?></td>
						            <td><?= $row['waterTax']; ?></td>
						            <td><?= $row['BusinessTax']; ?></td>
						            <td>
						                <!-- Edit Button -->
						                <button type="submit" name="edit-btn" class="btn btn-warning btn-sm openModalBtn" data-toggle="modal" data-target="#updateCitizen" id="update-btn" value="<?= $row['userId'] ?>">Edit</button>
						                
						                <!-- Delete Button -->
						                <form action="delete.php" method="POST" style="display:inline;">
										    <!-- Include the userId and name as hidden fields -->
										    <input type="hidden" name="DuserId" value="<?php echo $row['userId']; ?>">
										    <input type="hidden" name="DuserName" value="<?php echo $row['name']; ?>">
										    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete the record of <?php echo $row['userId']; ?> with ID <?php echo $row['name']; ?>?');">Delete</button>
										</form>
						            </td>
						        </tr>
						        <?php
						    }
} else {
    ?>
    <tr>
        <td colspan="9" class="text-center">No data found for the given filters.</td>
    </tr>
    <?php
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
 					<th>Action</th>
 				</tr>
 			</tfoot>


 			
 		</table>

 	</div>
 </div>

 <div class="container">
	<!-- adding new year -->
	<div class="card mb-4">
		<div class="card-header text-white"  style="background-color:#4a6e99; color: white;">
			<h5 class="mb-0">Add New Year</h5>
		</div>
		<div class="card-body">
			<form id="filter-form" class="row g-3" method="POST" action="addNewYear.php">
			
               
                <!-- userid filed -->
                <div class="col-md-3 mb-3">
                    <label for="newYear" class="form-label">Enter New Year</label>
                   <input type="text" class="form-control" name="newYear" id="newYear" placeholder="YYYY">
                </div>

         
				<div class="col-md-2 align-self-end mb-3">
					<button type="submit" name="addYear" class="btn btn-success btn-block" id="addYearButton">Add Year</button>
				</div>

			</form>
		</div>
	</div>


<!-- Modal for Update Citizen -->
<div class="modal fade" id="updateCitizen" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Update Citizen Details</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="$('#updateCitizen').modal('hide');">&times;</button>
            </div>

            <form method="POST" enctype="multipart/form-data" id="citizenUpdateForm">
                <div class="modal-body">
                    
                    <!-- Hidden Field for User ID -->
					<input type="hidden" name="HuserId" id="HuserId">


                    <!-- Name -->
                    <div class="form-group">
                        <label for="ed-name">Name</label>
                        <input type="text" name="ed-name" id="ed-name" class="form-control" placeholder="Enter Name" required>
                    </div>

                    <!-- Section -->
					<div class="form-group">
					    <label for="ed-section">Section</label>
					    <select name="ed-section" id="ed-section" class="form-control" required>
					        <option value="" disabled selected>Select Section</option>
					        <option value="A">Danda Mohalla</option>
					        <option value="B">Jadhavwadi Jamat Mohalla</option>
					        <option value="C">Radhakrishna Wadi & Vishvadip Wadi</option>
					        <option value="D">Sakade Wadi</option>
					        <option value="E">Khambewadi</option>
					        <option value="F">Madhaliwadi</option>
					        <option value="G">Khairakond</option>
					        <option value="H">Hotels</option>
					    </select>
					</div>


                    <!-- House Tax -->
                    <div class="form-group">
                        <label for="ed-houseTax">House Tax</label>
                        <input type="number" name="ed-houseTax" id="ed-houseTax" class="form-control" placeholder="Enter House Tax" min="0" >
                    </div>

                    <!-- Electricity Tax -->
                    <div class="form-group">
                        <label for="ed-electricityTax">Electricity Tax</label>
                        <input type="number" name="ed-electricityTax" id="ed-electricityTax" class="form-control" placeholder="Enter Electricity Tax" min="0" >
                    </div>

                    <!-- Water Tax -->
                    <div class="form-group">
                        <label for="ed-waterTax">Water Tax</label>
                        <input type="number" name="ed-waterTax" id="ed-waterTax" class="form-control" placeholder="Enter Water Tax" min="0">
                    </div>

                    <!-- Business Tax -->
                    <div class="form-group">
                        <label for="ed-businessTax">Business Tax</label>
                        <input type="number" name="ed-businessTax" id="ed-businessTax" class="form-control" placeholder="Enter Business Tax" min="0" >
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#updateCitizen').modal('hide');">Close</button>
                    <button type="submit" name="updateCitizen" class="btn" style="background-color:#2a3d66; color:white;">Update Citizen</button>
                </div>
            </form>

        </div>
    </div>
</div>



<!-- Modal for add Citizen -->
<div class="modal fade" id="addCitizenModal" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Add New Citizen Details</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="$('#updateCitizen').modal('hide');">&times;</button>
            </div>

            <form method="POST" enctype="multipart/form-data" id="citizenAddForm">
                <div class="modal-body">
                    
                    <!-- Hidden Field for User ID -->
					<!-- <input type="hidden" name="HuserId" id="HuserId"> -->

					 <!-- year value -->
                
	                <div class="form-group">
					    <label for="yearValue" class="form-label">Year</label>
					    <select id="yearValue" class="form-control" name="yearValue">
					        <option value="" disabled>from which year to add</option>
					        <?php
					        // Loop through each year in the array and create an option
					        foreach ($years as $Lyear) {
				            echo "<option value='$Lyear' " . (($Lyear == $year) ? 'selected' : '') . ">$Lyear</option>";

				        }
					        ?>
					    </select>
					</div>

					<!-- citizen id -->
					 <div class="form-group">
                        <label for="ad-citizenId">Citizen Id</label>
                        <input type="text" name="ad-citizenId" id="ad-citizenId" class="form-control" placeholder="enter citizen id" required>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label for="ad-name">Name</label>
                        <input type="text" name="ad-name" id="ad-name" class="form-control" placeholder="Enter Name" required>
                    </div>

                    <!-- Section -->
						<div class="form-group">
						    <label for="ad-section">Section</label>
						    <select name="ad-section" id="ad-section" class="form-control" required>
						        <option value="" disabled selected>Select Section</option>
						        <option value="A">Danda Mohalla</option>
						        <option value="B">Jadhavwadi Jamat Mohalla</option>
						        <option value="C">Radhakrishna Wadi & Vishvadip Wadi</option>
						        <option value="D">Sakade Wadi</option>
						        <option value="E">Khambewadi</option>
						        <option value="F">Madhaliwadi</option>
						        <option value="G">Khairakond</option>
						        <option value="H">Hotels</option>
						    </select>
						</div>

                    <!-- House Tax -->
                    <div class="form-group">
                        <label for="ad-houseTax">House Tax</label>
                        <input type="number" name="ad-houseTax" id="ad-houseTax" class="form-control" placeholder="Enter House Tax" min="0" >
                    </div>

                    <!-- Electricity Tax -->
                    <div class="form-group">
                        <label for="ad-electricityTax">Electricity Tax</label>
                        <input type="number" name="ad-electricityTax" id="ad-electricityTax" class="form-control" placeholder="Enter Electricity Tax" min="0" >
                    </div>

                    <!-- Water Tax -->
                    <div class="form-group">
                        <label for="ad-waterTax">Water Tax</label>
                        <input type="number" name="ad-waterTax" id="ad-waterTax" class="form-control" placeholder="Enter Water Tax" min="0">
                    </div>

                    <!-- Business Tax -->
                    <div class="form-group">
                        <label for="ad-businessTax">Business Tax</label>
                        <input type="number" name="ad-businessTax" id="ad-businessTax" class="form-control" placeholder="Enter Business Tax" min="0" >
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#addCitizenModal').modal('hide');">Close</button>
                    <button type="submit" name="addCitizenModal" id="addCitizenModal" class="btn" style="background-color:#2a3d66; color:white;">Add Citizen</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- for table -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>


<script type="text/javascript">

$(document).ready(function() {
    $('#mytable').DataTable({
        responsive: true, // Optional
        paging: true,
        searching: true,
        columnDefs: [
            { targets: '_all', defaultContent: '' } // Fallback for missing data
        ]
    });
});
</script>

</body>
</html>