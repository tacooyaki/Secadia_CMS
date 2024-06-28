<?php

/*******w******** 
    

    Date: August 10, 2023
    Description: A page where the admin must double check if they want to delete a user from the database.

****************/

require_once "connect.php";

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["UserID"]) && !empty($_POST["UserID"])){
        if (!filter_var($_POST["UserID"], FILTER_VALIDATE_INT)) {
            $_SESSION["message"] = "Invalid UserID.";
            header("location: admin.php");
            exit();
        }

        $param_id = trim($_POST["UserID"]);

        // comments table ID set to NULL
        $sql_update_comments = "UPDATE comments SET UserID = NULL WHERE UserID = :id";
        if($stmt_update = $db->prepare($sql_update_comments)){
            $stmt_update->bindParam(":id", $param_id);
            if(!$stmt_update->execute()){
                echo "Something went wrong updating comments. Please try again later.";
                exit();
            }
        }
        unset($stmt_update);

        // mushroom_details table ID set to NULL
        $sql_update_mushrooms = "UPDATE mushroom_details SET UserID = NULL WHERE UserID = :id";
        if($stmt_update_mush = $db->prepare($sql_update_mushrooms)){
            $stmt_update_mush->bindParam(":id", $param_id);
            if(!$stmt_update_mush->execute()){
                echo "Something went wrong updating mushroom details. Please try again later.";
                exit();
            }
        }
        
        $sql = "DELETE FROM Users WHERE UserID = :id";
        if($stmt = $db->prepare($sql)){
            $stmt->bindParam(":id", $param_id);
            if($stmt->execute()){
                $_SESSION["message"] = "User deleted successfully.";
                header("location: admin.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        unset($stmt);
    } else{
        if(empty(trim($_POST["UserID"]))){
            $_SESSION["message"] = "UserID not provided.";
            header("location: admin.php");
            exit();
        }
    }
} else{
    if(isset($_GET["UserID"]) && !empty(trim($_GET["UserID"]))){
        if (!filter_var($_GET["UserID"], FILTER_VALIDATE_INT)) {
            $_SESSION["message"] = "Invalid UserID.";
            header("location: admin.php");
            exit();
        }

        $sql = "SELECT * FROM Users WHERE UserID = :id";
        
        if($stmt = $db->prepare($sql)){
            $stmt->bindParam(":id", $param_id);
            
            $param_id = trim($_GET["UserID"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $username = $user["Username"];
                } else{
                    $_SESSION["message"] = "User not found.";
                    header("location: admin.php");
                    exit();
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        unset($stmt);
    }  else{
        $_SESSION["message"] = "UserID not provided.";
        header("location: admin.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Delete User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Delete User
                </div>
                <div class="card-body">
                    <p>Are you sure you want to delete this user?</p>
                    <p><strong>Username:</strong> <?php echo $username; ?></p>
                    <form action="delete_user.php" method="post">
                        <input type="hidden" name="UserID" value="<?php echo trim($_GET["UserID"]); ?>">
                        <button type="submit" class="btn btn-danger">Yes</button>
                        <a href="admin.php" class="btn btn-secondary">No</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>


