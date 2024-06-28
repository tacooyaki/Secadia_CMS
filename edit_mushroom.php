<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || ($_SESSION["role"] !== 'Administrator' && $_SESSION["role"] !== 'Field Researcher')){
    header("location: login.php");
    exit;
}

if(isset($_GET['dashboard'])){
    if($_SESSION["role"] === 'Administrator'){
        header('Location: admin.php');
        exit;
    }
    elseif($_SESSION["role"] === 'Field Researcher'){
        header('Location: field_researcher_dashboard.php');
        exit;
    }
    else {
        echo "Error: Invalid user role.";
    }
}

include 'connect.php';
include 'sanitize_validate.php';
include 'image_functions.php';
include 'header.php';

if(!isset($_GET["MushroomID"])){
    header("Location: admin.php");
    exit();
}
$mushroomId = intval($_GET["MushroomID"]);

$stmt = $db->prepare("SELECT * FROM photos WHERE MushroomID = ?");
$stmt->execute([$mushroomId]);
$photo = $stmt->fetch();



if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = sanitizeString($_POST["name"]);
    if (empty($name) || strlen($name) > 255) {
        die('Mushroom name is required and should not exceed 255 characters.');
    }

    $class = sanitizeString($_POST["class"]);
    if (empty($class) || strlen($class) > 255) {
        die('Mushroom class is required and should not exceed 255 characters.');
    }

    $cap = sanitizeString($_POST["cap"]);
    if (empty($cap) || strlen($cap) > 255) {
        die('Cap description is required and should not exceed 255 characters.');
    }

    $gills = sanitizeString($_POST["gills"]);
    if (strlen($gills) > 255) {
        die('Gills description should not exceed 255 characters.');
    }

    $spore_print = sanitizeString($_POST["spore_print"]);
    if (strlen($spore_print) > 255) {
        die('Spore print description should not exceed 255 characters.');
    }

    $stalk = sanitizeString($_POST["stalk"]);
    if (strlen($stalk) > 255) {
        die('Stalk description should not exceed 255 characters.');
    }

    $flesh = sanitizeString($_POST["flesh"]);
    if (strlen($flesh) > 255) {
        die('Flesh description should not exceed 255 characters.');
    }

    $odour = sanitizeString($_POST["odour"]);
    if (strlen($odour) > 255) {
        die('Odour description should not exceed 255 characters.');
    }

    $taste = sanitizeString($_POST["taste"]);
    if (strlen($taste) > 255) {
        die('Taste description should not exceed 255 characters.');
    }

    $field_identification = sanitizeString($_POST["field_identification"]);
    if (strlen($field_identification) > 255) {
        die('Field identification should not exceed 255 characters.');
    }
    // add moar if needed.

    $stmt = $db->prepare("UPDATE mushroom_details SET Name = ?, Class = ?, Cap = ?, Gills = ?, SporePrint = ?, Stalk = ?, Flesh = ?, Odour = ?, Taste = ?, FieldIdentification = ? WHERE MushroomID = ?");
    $stmt->execute([$name, $class, $cap, $gills, $spore_print, $stalk, $flesh, $odour, $taste, $field_identification, $mushroomId]);

    // adding an image
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $photo  = $_FILES["image"];
        
        $upload_dir = 'uploads/images/';
        
        $imageFileType = strtolower(pathinfo($photo ["name"], PATHINFO_EXTENSION));
        
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }
        
        //  unique filename
        $photo_basename = sanitizeString(basename($photo['name']));
        $photo_basename_without_ext = pathinfo($photo_basename, PATHINFO_FILENAME);
        $photo_path = $upload_dir . uniqid() . '_' . $photo_basename_without_ext . '.' . $imageFileType;


        // file is an image?
        $check = getimagesize($photo ["tmp_name"]);
        if ($check !== false) {

            if (move_uploaded_file($photo["tmp_name"], $photo_path)) {
                $stmt = $db->prepare("INSERT INTO photos (MushroomID, Photo) VALUES (?, ?)");
                $stmt->execute([$mushroomId, $photo_path]);

                // Resize
                $source_path = $photo_path;
                $destination_path = $photo_path;

                resizeImage($source_path, $destination_path);
            } else {
                die("Sorry, there was an error uploading your file.");
            }
        } else {
            die("File is not an image.");
        }
    }

    if (isset($_POST["delete_image"]) && $photo) {
        $stmt = $db->prepare("DELETE FROM photos WHERE MushroomID = ?");
        $stmt->execute([$mushroomId]);

        if (file_exists($photo["Photo"])) {
            unlink($photo["Photo"]);
        }
    }
    

    if($_SESSION['role'] === 'Administrator') {
        header("Location: admin.php");
            } else if($_SESSION['role'] === 'Field Researcher') {
        header("Location: field_researcher_dashboard.php");
            }
    exit();
}

$stmt = $db->prepare("SELECT * FROM mushroom_details WHERE MushroomID = ?");
$stmt->execute([$mushroomId]);
$mushroom = $stmt->fetch();

if(!$mushroom){
    die("Mushroom not found.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Mushroom</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Edit Mushroom</h1>
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <form action="edit_mushroom.php?MushroomID=<?php echo $mushroomId; ?>" method="post" enctype="multipart/form-data" class="mt-3">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php echo $mushroom['Name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="class">Class:</label>
                    <input type="text" class="form-control form-control-sm" id="class" name="class" value="<?php echo $mushroom['Class']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="cap">Cap:</label>
                    <input type="text" class="form-control form-control-sm" id="cap" name="cap" value="<?php echo $mushroom['Cap']; ?>">
                </div>

                <div class="form-group">
                    <label for="gills">Gills:</label>
                    <input type="text" class="form-control form-control-sm" id="gills" name="gills" value="<?php echo $mushroom['Gills']; ?>">
                </div>

                <div class="form-group">
                    <label for="spore_print">Spore Print:</label>
                    <input type="text" class="form-control form-control-sm" id="spore_print" name="spore_print" value="<?php echo $mushroom['SporePrint']; ?>">
                </div>

                <div class="form-group">
                    <label for="stalk">Stalk:</label>
                    <input type="text" class="form-control form-control-sm" id="stalk" name="stalk" value="<?php echo $mushroom['Stalk']; ?>">
                </div>

                <div class="form-group">
                    <label for="flesh">Flesh:</label>
                    <input type="text" class="form-control form-control-sm" id="flesh" name="flesh" value="<?php echo $mushroom['Flesh']; ?>">
                </div>

                <div class="form-group">
                    <label for="odour">Odour:</label>
                    <input type="text" class="form-control form-control-sm" id="odour" name="odour" value="<?php echo $mushroom['Odour']; ?>">
                </div>

                <div class="form-group">
                    <label for="taste">Taste:</label>
                    <input type="text" class="form-control form-control-sm" id="taste" name="taste" value="<?php echo $mushroom['Taste']; ?>">
                </div>

                <div class="form-group">
                    <label for="field_identification">Field Identification:</label>
                    <input type="text" class="form-control form-control-sm" id="field_identification" name="field_identification" value="<?php echo $mushroom['FieldIdentification']; ?>">
                </div>

                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>

                <?php if ($photo): ?>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="delete_image" name="delete_image">
                    <label class="form-check-label" for="delete_image">Delete Image</label>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
            </form>

            <form method="get" action="" class="mt-4">
                <button type="submit" name="dashboard" class="btn btn-secondary btn-sm">Return to Dashboard</button>
            </form>
            <br>

        </div>
    </div> 
</div> 

<!-- Bootstrap JS, Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>