<?php

session_start();
include 'header.php';

$_SESSION['referrer'] = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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

include_once 'connect.php';

$searchTerm = isset($_POST['search']) ? $_POST['search'] : (isset($_SESSION['last_search']) ? $_SESSION['last_search'] : "");
$class = isset($_POST['class']) ? $_POST['class'] : (isset($_SESSION['last_class']) ? $_SESSION['last_class'] : "");

// Define results per the N
$items_per_page = 10; //  value to change for testing

$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($current_page - 1) * $items_per_page;

$_SESSION['last_search'] = $searchTerm;
$_SESSION['last_class'] = $class;

$query = "
    SELECT md.*, s.SubstrateType 
    FROM mushroom_details md 
    LEFT JOIN substrates s ON md.MushroomID = s.MushroomID
    WHERE 
        (Name LIKE :searchTerm 
        OR Class LIKE :searchTerm 
        OR Cap LIKE :searchTerm 
        OR Gills LIKE :searchTerm 
        OR SporePrint LIKE :searchTerm 
        OR Stalk LIKE :searchTerm 
        OR Flesh LIKE :searchTerm 
        OR Odour LIKE :searchTerm 
        OR Taste LIKE :searchTerm 
        OR FieldIdentification LIKE :searchTerm)
";

// Check
if (!empty($class)) {
    $query .= " AND md.Class = :class";
}

// ---------------------------

$count_query = str_replace("SELECT md.*, s.SubstrateType", "SELECT COUNT(*) as total", $query);

$count_stmt = $db->prepare($count_query);
$count_stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);

if (!empty($class)) {
    $count_stmt->bindValue(':class', $class, PDO::PARAM_STR);
}

$count_stmt->execute();
$total_results = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_results / $items_per_page);

$query .= " LIMIT $items_per_page OFFSET $offset";

// ----------------------
$stmt = $db->prepare($query);
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);

if (!empty($class)) {
    $stmt->bindValue(':class', $class, PDO::PARAM_STR);
}

$stmt->execute();
$results = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
        <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card" style="background-color: #3E721D;">
        <div class="card-header" style="background-color: #3E721D">
            <strong style="color: #F6D96E">Mushroom Search Tips</strong>
        </div>
        <div class="card-body">
            <ul>
                <li style="color: #FFE4C4"><strong>Name:</strong> Enter any part of the common name or scientific name. Ex: Amanita</li>
                <li style="color: #FFE4C4"><strong>Class:</strong> Filter by the class of the mushroom. Ex: Agaricomycetes</li>
                <li style="color: #FFE4C4"><strong>Cap & Stalk:</strong> Include details like cap shape or stalk characteristics. Ex: cylindrical</li>
                <li style="color: #FFE4C4"><strong>Substrate:</strong> Add the substrate type to your query. Ex: Wood</li>
            </ul>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <?php
        if (!empty($results)) {
            foreach ($results as $row) {
                echo '<div class="col-md-6">';
                echo '<div class="card mb-4" style="background-color: #3E721D;">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title"><a href="view_mushroom.php?MushroomID=' . htmlspecialchars($row['MushroomID'], ENT_QUOTES, 'UTF-8') . '" style="color: #F6D96E;">' . htmlspecialchars($row['Name'], ENT_QUOTES, 'UTF-8') . '</a></h5>';
                echo '<p class="card-text" style="color: #FFE4C4;">Class: ' . htmlspecialchars($row['Class'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p class="card-text" style="color: #FFE4C4;">Cap: ' . htmlspecialchars($row['Cap'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p class="card-text" style="color: #FFE4C4;">Stalk: ' . htmlspecialchars($row['Stalk'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p class="card-text" style="color: #FFE4C4;">Substrate: ' . htmlspecialchars($row['SubstrateType'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                }
            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($results)) {
                    $message = 'No results found for search term "' . htmlspecialchars($searchTerm) . '"';

                    if (!empty($class)) {
                        $message .= ' in class "' . htmlspecialchars($class) . '"';
                    }

                    $message .= ".";

                    echo '<div class="alert alert-info" role="alert">' . $message . '</div>';
                }
            }
        ?>
    </div>
</div>

<div class="container mt-4">
    <!-- The pagination of search results-->
    <?php if ($total_results > $items_per_page): ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="search.php?page=<?php echo $current_page - 1; ?>">Previous</a>
                </li>
            <?php endif; ?>

            <!-- available pages -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                    <a class="page-link" href="search.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- next page link -->
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="search.php?page=<?php echo $current_page + 1; ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>
    <!-- Bootstrap and jQuerys -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>