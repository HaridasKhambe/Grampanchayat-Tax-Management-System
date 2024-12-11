
<?php
require_once 'partials/session_check.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php require 'partials/navbar.php'?>

<style type="text/css">


        /* Alert container adjustment */
        .alert-container {
            padding-top: 100px; /* Adjust this as per your requirement */
        }
</style>

</head>
<body>

   
    <!-- Alert message -->
    <div class="container alert-container">
        <div class="alert alert-dismissible fade show"  style="background-color:#4a6e99; color: white;"role="alert">
            <strong>Notice!</strong> This module is currently under development. Please check back later.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
