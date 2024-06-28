<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || ($_SESSION["role"] !== 'Administrator' && $_SESSION["role"] !== 'Field Researcher')){
    header("location: login.php");
    exit;
}

include 'connect.php';

if(!isset($_GET["MushroomID"])){
    header("Location: admin.php");
    exit();
}

$mushroomId = intval($_GET["MushroomID"]);

try {
    $db->beginTransaction();

    $stmt = $db->prepare("DELETE FROM locations WHERE MushroomID = ?");
    $stmt->execute([$mushroomId]);

    // Before deleting the mushroom's photo entry, retrieve the file path
    $stmt = $db->prepare("SELECT Photo FROM photos WHERE MushroomID = ?");
    $stmt->execute([$mushroomId]);
    $photoData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($photoData && isset($photoData['Photo'])) {
        $photoPath = $photoData['Photo'];
        
        if (file_exists($photoPath)) {
            unlink($photoPath);  // delete the physical file
        }
    }

    // Now proceed with deleting the photo reference from the database
    $stmt = $db->prepare("DELETE FROM photos WHERE MushroomID = ?");
    $stmt->execute([$mushroomId]);

    $stmt = $db->prepare("DELETE FROM substrates WHERE MushroomID = ?");
    $stmt->execute([$mushroomId]);

    $stmt = $db->prepare("DELETE FROM mushroom_details WHERE MushroomID = ?");
    $stmt->execute([$mushroomId]);

    $db->commit();

} catch(PDOException $e) {
    $db->rollback();
    throw $e;
}

if (isset($_SESSION['referrer'])) {
    header("Location: " . $_SESSION['referrer']);
} else {
    header("Location: login.php");
}
exit();

?>
