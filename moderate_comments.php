<?php

session_start();
require_once 'connect.php';
include 'header.php';


// Ensure admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Administrator'){
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['comment_id'])) {
    $action = $_POST['action'];
    $comment_id = $_POST['comment_id'];

    if ($action == 'delete') {
        // Delete comment
        $stmt = $db->prepare("DELETE FROM comments WHERE CommentID = ?");
        $stmt->execute([$comment_id]);
    } elseif ($action == 'hide') {
        // Hide comment
        $stmt = $db->prepare("UPDATE comments SET is_hidden = 1 WHERE CommentID = ?");
        $stmt->execute([$comment_id]);
    } elseif ($action == 'disemvowel') {
        //  disemvowel comment
        $stmt = $db->prepare("UPDATE comments SET CommentText = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CommentText, 'a', ''), 'e', ''), 'i', ''), 'o', ''), 'u', '') WHERE CommentID = ?");
        $stmt->execute([$comment_id]);
    } elseif ($action === 'unhide') {
        $stmt = $db->prepare("UPDATE comments SET is_hidden = FALSE WHERE CommentID = ?");
        $stmt->execute([$comment_id]);
    }
}

$stmt = $db->prepare("SELECT comments.*, Users.Username, mushroom_details.Name AS MushroomName FROM comments JOIN Users ON comments.UserID = Users.UserID JOIN mushroom_details ON comments.MushroomID = mushroom_details.MushroomID ORDER BY CommentTimestamp DESC");
$stmt->execute();
$comments = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Moderate Comments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Moderate Comments</h1>

        <?php foreach ($comments as $comment): ?>
            <div class="card mb-3">
                <div class="card-body" style="background-color: #3E721D;">
                    <h5 class="card-title" style="color: #F6D96E;">On Mushroom: <?php echo htmlspecialchars($comment['MushroomName'], ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p class="card-text" style="color: #FFE4C4;"><strong>Commenter:</strong> <?php echo htmlspecialchars($comment['Username'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="card-text" style="color: #FFE4C4;"><strong>Comment:</strong> <?php echo htmlspecialchars($comment['CommentText'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="card-text"><small style="color: #000;"><strong>Posted on:</strong> <?php echo htmlspecialchars($comment['CommentTimestamp'], ENT_QUOTES, 'UTF-8'); ?></small></p>

                    <form method="post">
                        <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                        <button type="submit" name="action" value="disemvowel" class="btn btn-warning btn-sm">Disemvowel</button>
                        <button type="submit" name="action" value="hide" class="btn btn-secondary btn-sm">Hide</button>
                        <button type="submit" name="action" value="unhide" class="btn btn-info btn-sm">Unhide</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <!-- Bootstrap JS and scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
