<?php
$currentClass = isset($_POST['class']) ? $_POST['class'] : '';
?>

<form action="search.php" method="post" class="form-inline">
    <input type="text" name="search" class="form-control form-control-sm mr-3" placeholder="Mushroom details...">
    <select name="class" class="form-control form-control-sm mr-2">
        <option value="">All Classes</option>
        <?php
        include_once 'connect.php';

        $stmt = $db->prepare("SELECT DISTINCT Class FROM mushroom_details");
        $stmt->execute();

        $classes = $stmt->fetchAll();

        foreach ($classes as $class): 
            $selected = ($class['Class'] == $currentClass) ? 'selected' : '';
        ?>
            <option value="<?php echo htmlspecialchars($class['Class'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($class['Class'], ENT_QUOTES, 'UTF-8'); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Search" class="btn btn-outline-secondary btn-sm">
</form>
