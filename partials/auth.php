<?php
require 'dbconn.php';
session_start();

// Validate user input
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM user_login WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            // User authenticated, start session and redirect
            $_SESSION['username'] = $username;
            echo '<script>window.location.href = "/gpkarde/dashboard.php";</script>';
            exit(); // Ensure the rest of the code does not execute after redirect
        } else {
            // Authentication failed, set error flag
            $_SESSION['error'] = "Invalid username or password";
            echo '<script>';
            echo 'alert("Invalid username or password.");';
            echo 'window.location.href = "/gpkarde/index.php";';
            echo '</script>';
            exit(); // Prevent further script execution
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle error if prepared statement fails
        $_SESSION['error'] = "Database query failed";
        echo '<script>';
        echo 'alert("Database query failed.");';
        echo 'window.location.href = "/gpkarde/index.php";';
        echo '</script>';
        exit(); // Prevent further script execution
    }
}

// Close the database connection
mysqli_close($conn);
?>
