<?php

session_start();
include_once 'connect.php';
include 'header.php';

if (!isset($_GET['MushroomID'])) {
    die('Invalid ID provided');
}
$mushroom_id = $_GET['MushroomID'];


if (!ctype_digit($mushroom_id)) {
    die('Invalid ID provided');
}

$stmt = $db->prepare("SELECT * FROM mushroom_details WHERE MushroomID = ?");
$stmt->execute([$mushroom_id]);
$mushroom = $stmt->fetch();

if (!$mushroom) {
    die('No mushroom found with that ID');
}

// Query db for photos to a specific mushroom
$stmt = $db->prepare("SELECT * FROM photos WHERE MushroomID = ?");
$stmt->execute([$mushroom_id]);
$photo = $stmt->fetch();

// Query db for substrate to a specific mushroom
$stmt = $db->prepare("SELECT * FROM substrates WHERE MushroomID = ?");
$stmt->execute([$mushroom_id]);
$substrate = $stmt->fetch();

// Query db for location to a specific mushroom
$stmt = $db->prepare("SELECT * FROM locations WHERE MushroomID = ?");
$stmt->execute([$mushroom_id]);
$location = $stmt->fetch();

// Qury db for comments to a specific mushroom
$stmt = $db->prepare("SELECT comments.*, Users.Username FROM comments JOIN Users ON comments.UserID = Users.UserID WHERE comments.MushroomID = ? AND is_hidden = FALSE ORDER BY CommentTimestamp DESC");
$stmt->execute([$mushroom_id]);
$comments = $stmt->fetchAll();

ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($mushroom['Name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h1><?php echo htmlspecialchars($mushroom['Name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php 
        if ($photo && file_exists($photo['Photo'])): ?>
            <img class="img-fluid mb-4" src="<?php echo $photo['Photo']; ?>" alt="<?php echo htmlspecialchars($mushroom['Name'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-9">
                <!-- mushroom details -->
                <p><strong>Class:</strong> <?php echo htmlspecialchars($mushroom['Class'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Cap:</strong> <?php echo htmlspecialchars($mushroom['Cap'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Gills:</strong> <?php echo htmlspecialchars($mushroom['Gills'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Spore Print:</strong> <?php echo htmlspecialchars($mushroom['SporePrint'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Stalk:</strong> <?php echo htmlspecialchars($mushroom['Stalk'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Flesh:</strong> <?php echo htmlspecialchars($mushroom['Flesh'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Odour:</strong> <?php echo htmlspecialchars($mushroom['Odour'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Taste:</strong> <?php echo htmlspecialchars($mushroom['Taste'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Field Identification:</strong> <?php echo htmlspecialchars($mushroom['FieldIdentification'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Substrate:</strong> <?php echo htmlspecialchars($substrate['SubstrateType'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($location['Locality'], ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($location['Habitat'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Date Found:</strong> <?php echo htmlspecialchars($location['Date'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php
                    $lastSearch = isset($_SESSION['last_search']) ? urlencode($_SESSION['last_search']) : '';
                    $lastClass = isset($_SESSION['last_class']) ? urlencode($_SESSION['last_class']) : '';
                    $returnLink = 'search.php?search=' . $lastSearch . '&class=' . $lastClass;

                    if($_SESSION["role"] === 'Administrator'){
                        echo '<a href="'. $returnLink .'" class="btn btn-primary">Return to Search Results</a>';
                    }
                    elseif($_SESSION["role"] === 'Field Researcher'){
                        echo '<a href="'. $returnLink .'" class="btn btn-primary">Return to Search Results</a>';
                    }
                    else {
                        echo "Error: Invalid user role.";
                    }
                ?>
            </div>
        </div>

        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
        <h2 class="mt-4">Post a comment:</h2>
        <form action="post_comment.php" method="post">
            <div class="form-group">
                <textarea class="form-control" name="CommentText" rows="5"></textarea>
                <input type="hidden" name="MushroomID" value="<?php echo $mushroom_id; ?>">
                <input type="submit" value="Submit comment" class="btn btn-primary mt-2">
            </div>
        </form>

        <?php endif; ?>
        
        <h2 class="mt-4">Comments:</h2>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <p><strong>Commenter:</strong> <?php echo htmlspecialchars($comment['Username'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Comment:</strong> <?php echo htmlspecialchars($comment['CommentText'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Posted on:</strong> <?php echo htmlspecialchars($comment['CommentTimestamp'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>