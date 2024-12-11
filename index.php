<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        :root {
            --bgcolor-1: #2a3d66;
            --bgcolor-2: #2a3d66;
            --maincolor: #000000;
            --btncolor: #2a3d66;
        }

        body {
            background-color: #f8f9fa;
        }

        .login1 {
            width: 100%;
            max-width: 500px;
            border: 2px solid #ccc;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            background-color: #fff;
            margin-top:30px;
        }

        h1 {
            text-align: center;
            font-family:Lato ;
            margin-top: 20px;
            font-weight:800;
            color: var(--bgcolor-1);
        }

        h2 {
            font-family: Lato;
            text-align: center;
            margin-bottom: 40px;
        }

        label {
            margin-left: 10px;
        }

        label, input {
            display: block;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #2a3d66;
            color: white;
            border: none;
            padding: 10px 10px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            border: 2px solid #ccc;
            width: 100%;
            margin: 10px 0;
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.5rem;
            }

            .login1 {
                padding: 20px;
            }

            input[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <h1>Welcome To GramPanchayat Karde</h1>
    <div class="login1">
        <h2>ADMIN LOGIN</h2>
        <form method="post" action="partials/auth.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <input type="submit" class="btn btn-primary" value="LOGIN">
        </form>
    </div>

    <!-- Add Bootstrap JS and Popper.js scripts for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
