<?php
session_start();
include 'header.php';

if (isset($_SESSION["success"])) {
    $success_message = $_SESSION["success"];
    unset($_SESSION["success"]);
}

$_SESSION['referrer'] = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Field Researcher') {
    header("location: login.php");
    exit;
}

include_once 'connect.php';

$allowed_orders = ['MushroomID', 'Name', 'Class', 'Locality'];
$order = 'MushroomID';

if (isset($_GET['order']) && in_array($_GET['order'], $allowed_orders)) {
    $order = $_GET['order'];
}

$stmt = $db->prepare("SELECT mushroom_details.*, locations.Locality FROM mushroom_details LEFT JOIN locations ON mushroom_details.MushroomID = locations.LocationID ORDER BY " . $order);
$stmt->execute();
$mushrooms = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Field Researcher Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.4/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.4/datatables.min.js"></script>
</head>
<body>
<?php if (isset($success_message)) : ?>
    <div class="alert alert-success" role="alert">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function(){
        setTimeout(function() {
            $(".alert").fadeOut().empty();
        }, 5000);
    });
</script>

<div class="container py-3">
    <h1 class="mb-3">Field Researcher Dashboard</h1>
    <h2 class="mb-4">Welcome, <?php echo ucfirst(strtolower(htmlspecialchars($_SESSION["username"]))); ?></h2>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card mb-4">
                <div class="card-header">Add Mushroom Details</div>
                <div class="card-body">
                    <form action="enter_mushroom.php" method="post" enctype="multipart/form-data" class="form-group">
                        <div class="form-group">
                            <label for="name">Binomial Name or Common Name</label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" required>
                            <small id="nameHelp" class="form-text text-muted">Enter the binomial name or common name of the mushroom.</small>
                        </div>
                        <div class="form-group">
                            <label for="class">Class</label>
                            <input type="text" class="form-control form-control-sm" id="class" name="class" required>
                            <small id="classHelp" class="form-text text-muted">Enter the class of the mushroom.</small>
                        </div>
                        <div class="form-group">
                            <label for="cap">Cap</label>
                            <input type="text" class="form-control form-control-sm" id="cap" name="cap" required>
                            <small id="capHelp" class="form-text text-muted">Enter the cap description of the mushroom.</small>
                        </div>
                        <div class="form-group">
                            <label for="gills">Gills</label>
                            <input type="text" class="form-control form-control-sm" id="gills" name="gills">
                            <small id="capHelp" class="form-text text-muted">Enter the type of gills.</small>
                        </div>
                        <div class="form-group">
                            <label for="spore_print">Spore Print</label>
                            <input type="text" class="form-control form-control-sm" id="spore_print" name="spore_print">
                            <small id="capHelp" class="form-text text-muted">Describe spore print if it has one.</small>
                        </div>
                        <div class="form-group">
                            <label for="stalk">Stalk</label>
                            <input type="text" class="form-control form-control-sm" id="stalk" name="stalk">
                            <small id="capHelp" class="form-text text-muted">Describe the stalk.</small>
                        </div>
                        <div class="form-group">
                            <label for="flesh">Flesh</label>
                            <input type="text" class="form-control form-control-sm" id="flesh" name="flesh">
                            <small id="capHelp" class="form-text text-muted">Describe its flesh.</small>
                        </div>
                        <div class="form-group">
                            <label for="odour">Odour</label>
                            <input type="text" class="form-control form-control-sm" id="odour" name="odour">
                            <small id="capHelp" class="form-text text-muted">Detail its odour.</small>
                        </div>
                        <div class="form-group">
                            <label for="taste">Taste</label>
                            <input type="text" class="form-control form-control-sm" id="taste" name="taste">
                            <small id="capHelp" class="form-text text-muted">Do not taste it. Fill this entry out later.</small>
                        </div>
                        <div class="form-group">
                            <label for="field_identification">Field Identification</label>
                            <input type="text" class="form-control form-control-sm" id="field_identification" name="field_identification">
                            <small id="capHelp" class="form-text text-muted">Describe how it was found.</small>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control form-control-sm" id="location" name="location">
                            <small id="capHelp" class="form-text text-muted">Give approximate location.</small>
                        </div>
                        <div class="form-group">
                            <label for="habitat">Habitat</label>
                            <input type="text" class="form-control form-control-sm" id="habitat" name="habitat">
                            <small id="capHelp" class="form-text text-muted">Describe area it was found in.</small>
                        </div>
                        <div class="form-group">
                            <label for="date">Date (yyyy-mm-dd)</label>
                            <input type="text" class="form-control form-control-sm" id="date" name="date">
                            <small id="capHelp" class="form-text text-muted">Enter the date it was found.</small>
                        </div>
                        <div class="form-group">
                            <label for="substrate">Substrate</label>
                            <input type="text" class="form-control form-control-sm" id="substrate" name="substrate">
                            <small id="capHelp" class="form-text text-muted">Describe what it was found in.</small>
                        </div>
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" class="btn btn-sm" id="photo" name="photo">
                            <small id="capHelp" class="form-text text-muted">Upload a photo of the mushroom. Only jpg, jpeg, png and gif file formats permitted.</small>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card mb-4">
                <div class="card-header">Current Mushrooms</div>
                <div class="card-body">
                    <p>This table displays all mushroom entries made into this CMS. Table data can be sorted by ID, name of the mushroom, mushroom class, or location.</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="mushroomsTable">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Class</th>
                                <th scope="col">Location</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($mushrooms as $mushroom): ?>
                                <tr>
                                    <td><a href="view_mushroom.php?MushroomID=<?php echo $mushroom['MushroomID']; ?>"><?php echo $mushroom['Name']; ?></a></td>
                                    <td><?php echo $mushroom['Class']; ?></td>
                                    <td><?php echo $mushroom['Locality']; ?></td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="edit_mushroom.php?MushroomID=<?php echo $mushroom['MushroomID']; ?>">Edit</a>
                                        <a class="btn btn-danger btn-sm" href="delete_mushroom.php?MushroomID=<?php echo $mushroom['MushroomID']; ?>" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#mushroomsTable').DataTable();
    });
</script>
</body>
</html>
