<?php

require_once 'connect.php';
session_start();

// ensure form submitted and fields set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MushroomID'], $_POST['CommentText'])) {

    $sql = "INSERT INTO comments (MushroomID, UserID, CommentText) VALUES (:mushroom_id, :user_id, :comment_text)";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bindParam(':mushroom_id', $param_mushroom_id);
        $stmt->bindParam(':user_id', $param_user_id);
        $stmt->bindParam(':comment_text', $param_comment_text);

        $param_mushroom_id = trim($_POST['MushroomID']);
        $param_user_id = $_SESSION['id'];
        $param_comment_text = trim($_POST['CommentText']);

        if ($stmt->execute()) {
            header("location: view_mushroom.php?MushroomID=" . $param_mushroom_id);
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        unset($stmt);
    }

} else {
    header("location: error.php");
    exit();
}
?>