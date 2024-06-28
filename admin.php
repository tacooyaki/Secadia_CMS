<?php
session_start();
include 'header.php';

if (isset($_SESSION["success"])) {
    $success_message = $_SESSION["success"];
    unset($_SESSION["success"]);
}

$_SESSION['referrer'] = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Administrator') {
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
    <title>Admin Dashboard</title>
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

<div class="container py-2">
    <h1 class="mb-3">Admin Dashboard</h1>
    <h2 class="mb-4">Welcome, <?php echo ucfirst(strtolower(htmlspecialchars($_SESSION["username"]))); ?></h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">User Administration</div>
                <div class="card-body">
                    <a href="create_user.php" class="btn btn-primary mb-2">Create New User</a>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="usersTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM Users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user):
                                ?>
                                <tr>
                                    <td><?php echo $user['UserID']; ?></td>
                                    <td><?php echo $user['Username']; ?></td>
                                    <td><?php echo $user['Email']; ?></td>
                                    <td><?php echo $user['Role']; ?></td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="edit_user.php?UserID=<?php echo $user['UserID']; ?>">Edit</a>
                                        <a class="btn btn-danger btn-sm" href="delete_user.php?UserID=<?php echo $user['UserID']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Quick Actions</div>
                <div class="card-body">
                    <a href="enter_mushroom.php" class="btn btn-success mb-2">Enter Mushroom</a>
                    <a href="moderate_comments.php" class="btn btn-warning mb-2">Moderate Comments</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Comment Moderation</div>
                <div class="card-body">
                    <a href="moderate_comments.php" class="btn btn-primary mb-2">Moderate Comments</a>
                    <p>This link takes you to a page where comments can be deleted, disemvoweled, hidden, and unhidden.</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Recent Activity</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">auri added a new mushroom</li>
                        <li class="list-group-item">pman edited a mushroom entry</li>
                        <li class="list-group-item">serveruser deleted a user</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">Mushroom Information</div>
                <div class="card-body">
                    <h4 class="card-title mb-1">Current Mushrooms</h4>
                    <p>This table displays all mushroom entries made into this CMS. Table data can be sorted by ID, name of the mushroom, mushroom class, or location.</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm" id="mushroomsTable">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Class</th>
                                <th scope="col">Location</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($mushrooms as $mushroom): ?>
                                <tr>
                                    <td><?php echo $mushroom['MushroomID']; ?></td>
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
        $('#usersTable').DataTable();
    });
</script>

<?php require_once 'footer.php'; ?>

</body>
</html>
