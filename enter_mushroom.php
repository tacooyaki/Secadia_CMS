<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include 'connect.php';
include 'sanitize_validate.php';
include 'image_functions.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $required_fields = ['name', 'class', 'cap'];

    foreach($required_fields as $field) {
        if(empty($_POST[$field])) {
            die("Please fill all required fields. $field is missing.");
        }
    }

    $name = sanitizeString($_POST['name']);
    $class = sanitizeString($_POST['class']);
    $cap = sanitizeString($_POST['cap']);
    $gills = isset($_POST['gills']) ? sanitizeString($_POST['gills']) : NULL;
    $spore_print = isset($_POST['spore_print']) ? sanitizeString($_POST['spore_print']) : NULL;
    $stalk = isset($_POST['stalk']) ? sanitizeString($_POST['stalk']) : NULL;
    $flesh = isset($_POST['flesh']) ? sanitizeString($_POST['flesh']) : NULL;
    $odour = isset($_POST['odour']) ? sanitizeString($_POST['odour']) : NULL;
    $taste = isset($_POST['taste']) ? sanitizeString($_POST['taste']) : NULL;
    $field_identification = isset($_POST['field_identification']) ? sanitizeString($_POST['field_identification']) : NULL;

    $user_id = $_SESSION['id'];

    $sql = "INSERT INTO mushroom_details (UserID, Name, Class, Cap, Gills, SporePrint, Stalk, Flesh, Odour, Taste, FieldIdentification) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id, $name, $class, $cap, $gills, $spore_print, $stalk, $flesh, $odour, $taste, $field_identification]);

    $mushroomId = $db->lastInsertId();

    if(!$mushroomId) {
        die("There was an error inserting the mushroom details.");
    }

    $location = isset($_POST['location']) ? sanitizeString($_POST['location']) : NULL;
    $habitat = isset($_POST['habitat']) ? sanitizeString($_POST['habitat']) : NULL;
    $date = isset($_POST['date']) ? sanitizeString($_POST['date']) : NULL;

    $sql = "INSERT INTO locations (MushroomID, Locality, Habitat, Date) VALUES (?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([$mushroomId, $location, $habitat, $date]);

    if(!$stmt->rowCount()) {
        die("There was an error inserting the location details.");
    }

    $substrate = isset($_POST['substrate']) ? sanitizeString($_POST['substrate']) : NULL;

    $sql = "INSERT INTO substrates (MushroomID, SubstrateType) VALUES (?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([$mushroomId, $substrate]);

    if(!$stmt->rowCount()) {
        die("There was an error inserting the substrate details.");
    }

// photo upload
if(!empty($_FILES['photo']['tmp_name'])) {
    //upload directory
    $upload_dir = 'uploads/images/';

    // check of upload directory exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // V and s
    $photo = $_FILES['photo'];
    $file_type = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
    
    //  extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    //  mime types
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
    
    $file_mime_type = mime_content_type($photo['tmp_name']);
    
    // file size limit
    $max_file_size = 5000000; // This is set to a 5MB max size
    if ($photo['size'] > $max_file_size) {
        die("The uploaded file is too large. Please upload a file smaller than 5MB.");
    }
    
    // Check uploaded file if an image, valid extension, valid mime type
    if (!getimagesize($photo['tmp_name']) || 
        !in_array($file_type, $allowed_extensions) || 
        !in_array($file_mime_type, $allowed_mime_types)) {
        die("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
    }
    
    // unique name for the file
    $photo_basename = sanitizeString(basename($photo['name']));
    $photo_basename_without_ext = pathinfo($photo_basename, PATHINFO_FILENAME);
    $photo_path = $upload_dir . uniqid() . '_' . $photo_basename_without_ext . '.' . $file_type;

    if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
        resizeImage($photo_path, $photo_path, 600, 480);
        echo "The file " . sanitizeString(basename($photo['name'])) . " has been uploaded.";
    } else {
        // error moving the file
        die("There was an error uploading the file.");
    }
    
    // insert file into DB
    $sql = "INSERT INTO photos (MushroomID, Photo) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$mushroomId, $photo_path]);
    
    if(!$stmt->rowCount()) {
        die("There was an error inserting the photo details.");
    }
}


    echo "Mushroom details saved successfully. You will be redirected back to your dashboard.";

    if($_SESSION["role"] == 'Administrator'){
            header("refresh:5;url=admin.php");
        } else if($_SESSION["role"] == 'Field Researcher'){
            header("refresh:5;url=field_researcher_dashboard.php");
        } else {
            header("refresh:5;url=login.php");
        }
    exit();
}
?>
