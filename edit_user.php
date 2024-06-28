<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if($_SESSION["role"] !== "Administrator"){
    die("Access denied. You don't have permission to access this page.");
}

require_once 'connect.php';
include 'header.php';

$username = $password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

if(isset($_GET['UserID']) && is_numeric($_GET['UserID'])){
    $id = (int) $_GET['UserID'];

    $sql = "SELECT * FROM Users WHERE UserID = :id";
    if($stmt = $db->prepare($sql)){
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $username = $row["Username"];
                $email = $row["Email"];
            }
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["id"]) && is_numeric($_POST["id"])) {
        $id = (int) $_POST["id"];
    } else {
        die("Invalid ID provided.");
    }
    
    $new_username = trim(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
    $new_email = trim(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL));

    if($new_email === false) {
        $email_err = "Invalid email format.";
    }

    if(!empty(trim($_POST["password"]))){
        $new_password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if(strlen($new_password) < 8) {
            $password_err = "Password must be at least 8 characters.";
        } else if($new_password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        } else {
            $password = password_hash($new_password, PASSWORD_DEFAULT);
        }
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        $sql = "UPDATE Users SET Username = :username, ".(!empty($password) ? "Password = :password, " : "")."Email = :email WHERE UserID = :id";
        
        if($stmt = $db->prepare($sql)){
            $stmt->bindParam(":username", $new_username, PDO::PARAM_STR);
            if(!empty($password)) $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $new_email, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            
            if($stmt->execute()){
                header("location: admin.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
    }
    unset($pdo);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Update User</h2>
            <p>Please fill this form to update an existing account. Leave the password field empty if you don't want to change the password.</p>
            <form action="edit_user.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and some jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
