<?php
    session_start();
    require_once "core/database.php";
    require_once "core/cipher.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        insert_memo($dbc);
        return;
    } else {
        $_SESSION['nonce'] = time() . rand();
    }
?>
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
    if (isset($_SESSION['error'])){
        echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
        unset($_SESSION['error']);
    } else if (isset($_SESSION['success'])){
        echo "<div class='alert alert-success' role='alert'>".$_SESSION['success']."</div>";
        unset($_SESSION['success']);
    }
?>
    <form action="memo?add" method="POST">
        <input type="text" name="memo_title" placeholder="Title..." required><br>
        <select name="memo_category" required>
            <option value="null">No category</option>
            <?php
                $user_id = $_SESSION['logged_user']["id"];
                $query = "SELECT * FROM categories WHERE cat_user_id=$user_id;";
                if ($ris = $dbc->query($query)){
                    while ($row = $ris->fetch_array()){
                        echo "<option value=".$row['cat_id'].">".$row['cat_name']."</option>";
                    }
                }
            ?>
        </select>
        <a href="category?add">New</a>
        <br>
        <input type="date" name="memo_date" id="memo_date" placeholder="Date..." required><br>
        <input type="number" name="memo_time_hours" min="0" step="1" max="23" placeholder="Hour">
        <input type="number" name="memo_time_minutes" min="0" step="1" max="59" placeholder="Minutes">
        <br>
        <textarea name="memo_text" id="memo_text" placeholder="Memo text..." required></textarea><br>

        <input type="submit" name="add" value="Add memo">
        <a href="home">Cancel</a>
        <input type="hidden" name="nonce" value="<?php echo md5($_SESSION['nonce']) ?>">
    </form>
</body>
</html>

<?php

function insert_memo($dbc){
    if (isset($_POST["nonce"]) && $_POST["nonce"] == md5($_SESSION['nonce'])){
        unset($_SESSION['nonce']);

        // check fields
        if (isset($_POST['memo_title']) && !empty($_POST['memo_title']) &&
            isset($_POST['memo_category']) && !empty($_POST['memo_category']) &&
            isset($_POST['memo_date']) && !empty($_POST['memo_date']) &&
            isset($_POST['memo_text']) && !empty($_POST['memo_text'])
        ) {
            if (!is_valid_date(trim($_POST['memo_date']))){
                echo "formato data non valido<br>";
                return;
            }
            if (strtotime($_POST['memo_date']) < time()){
                echo "data prima di oggi<br>";
                return;
            }

            if (!empty($_POST['memo_time_hours']) && !empty($_POST['memo_time_minutes'])){
                if (intval($_POST['memo_time_hours']) >= 0 && intval($_POST['memo_time_hours']) < 24 &&
                    intval($_POST['memo_time_minutes']) >= 0 && intval($_POST['memo_time_minutes']) < 60
                ){
                    $time = format_time($_POST['memo_time_hours'], $_POST['memo_time_minutes']);
                }
            } else {
                $time = NULL;
            }

            // insert into db
            $user_id = intval($_SESSION['logged_user']["id"]);
            $cipherkey = $_SESSION['logged_user']["cipher_key"];
            $datenow = date('Y-m-d H:i:s');

            $sql = "INSERT INTO memos (memo_title, memo_date, memo_time, memo_text, memo_creation_date, memo_user_id, memo_cat_id)
                    VALUES (?,?,?,?, '$datenow', $user_id, ?);";
            if ($stmt = $dbc->prepare($sql)){
                $date = $_POST['memo_date'];
                $cat_id = $_POST['memo_category'] == "null" ? NULL : $_POST['memo_category'];
                $title = encrypt(trim($_POST['memo_title']), $cipherkey);
                $text = encrypt(trim($_POST['memo_text']), $cipherkey);

                if ($stmt->bind_param("ssssi", $title, $date, $time, $text, $cat_id) && $stmt->execute()){
                    $id = $dbc->insert_id;

                    $_SESSION['success'] = "Memo ".$id." successfully added.";
                    header("Location: home");
                } else {
                    $_SESSION['error'] = "Internal server error: ".$dbc->error;
                    header("Location: memo?add");
                }
            } else {
                $_SESSION['error'] = "Internal server error: ".$dbc->error;
                header("Location: memo?add");
            }
        } else {
            $_SESSION['error'] = "Incorrect data sent. Check error messages";
            header("Location: memo?add");
        }
    }
}

function format_time($hour, $minute){
    if (intval($hour) < 10){
        $h = "0".$hour;
    } else {
        $h = $hour;
    }
    if (intval($minute) < 10){
        $m = "0".$minute;
    } else {
        $m = $minute;
    }

    return $h.":".$m;
}

function is_valid_date($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>