<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new memo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
    session_start();
    require_once "core/database.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST[md5("temp_id")]) && $_POST[md5("temp_id")] == md5($_SESSION['temp_id'])){
            unset($_SESSION['temp_id']);

            // check fields

            // crypto with user key

            // insert into db

            // error handling and redirect -> homepage
        }
    }
?>
    <form action="memo/add" method="POST">
        <input type="text" name="memo_title" placeholder="Title..."><br>
        <select name="memo_category">
            <option value="null">No category</option>
            <?php
                $_SESSION['temp_id'] = time() . rand();

                $user_id = $_SESSION['logged_user']["id"];
                $query = "SELECT * FROM categories WHERE cat_user_id=$user_id;";
                if ($ris = $dbc->query($query)){
                    while ($row = $ris->fetch_array()){
                        echo "<option>".$row['cat_name']."</option>";
                    }
                }
            ?>
        </select>
        <a href="category/add">New</a>
        <br>
        <input type="date" name="memo_date" id="memo_date" placeholder="Date..."><br>
        <input type="number" name="memo_time_hours" min="0" step="1" max="23" placeholder="Hour">
        <input type="number" name="memo_time_minutes" min="0" step="1" max="59" placeholder="Minutes">
        <br>
        <textarea name="memo_text" id="memo_text" placeholder="Memo text..."></textarea><br>

        <input type="submit" name="add" value="Add memo">
        <a href="home">Cancel</a>
        <input type="hidden" name="<?php echo md5("temp_id") ?>" value="<?php echo md5($_SESSION['temp_id']) ?>">
    </form>
</body>
</html>