<?php

session_start();


// logged in as administrator?
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Administrator'){
    header("location: login.php");
    exit;
}

include 'connect.php';
include 'sanitize_validate.php';
include 'header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Check fields
    $required_fields = ['username', 'password', 'role'];

    foreach($required_fields as $field) {
        if(empty($_POST[$field])) {
            die("Please fill all required fields. $field is missing.");
        }
    }

    // Sanitize and Validate
    $username = sanitizeString($_POST['username']);
    if (!validateUsername($username)) {
        die('Invalid username');
    }

    $password = sanitizeString($_POST['password']);
    if (!validatePassword($password)) {
        die('Invalid password');
    }

    $role = sanitizeString($_POST['role']);
    if (!validateRole($role)) {
        die('Invalid role');
    }

    // hashed password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (Username, Password, Role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, $role]);

    echo "New user created successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Center the form -->
        <div class="col-md-4 offset-md-4">
            <h1 class="text-center">Create User</h1>
            
            <form method="post" action="create_user.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control form-control-sm" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="form-control form-control-sm" id="role" name="role">
                        <option value="Field Researcher">Field Researcher</option>
                        <option value="Administrator">Administrator</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js and moar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>