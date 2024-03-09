<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log into your account</title>

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
    <form action="login" method="POST">
        <input type="text" name="username" placeholder="Username...">
        <input type="password" name="password" placeholder="Password...">

        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>

<?php
    require_once "core/database.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['username']) && $_POST['username'] != "" &&
            isset($_POST['password']) && $_POST['password'] != ""
        ){
            $us_hash = openssl_digest(trim($_POST['username']), "SHA256");
            $pw = trim($_POST['password']);

            $query = "SELECT * FROM users WHERE username_hash = ? AND password_hash = SHA2(CONCAT(salt, ?), 256);";

            if ($stmt = $dbc->prepare($query)){
                if ($stmt->bind_param("ss", $us_hash, $pw) && $stmt->execute()){
                    $res = $stmt->get_result();
                    if ($res->num_rows == 1){
                        $r = $res->fetch_array();
                        $_SESSION['logged_user'] = array("id" => $r['user_id'], "username" => trim($_POST['username']), "cypher_key" => $r['user_private_key']);

                        $_SESSION['success'] = "User ".trim($_POST['username'])." authenticated";
                        header("Location: home");
                    } else {
                        $_SESSION['error'] = "Username or password incorrect";
                        header("Location: login");
                    }
                } else {
                    http_response_code(500);
                    $_SESSION['error'] = "Cannot check credentials";
                    header("Location: login");
                }
            } else {
                http_response_code(500);
                $_SESSION['error'] = "Internal server error";
                header("Location: login");
            }
        }
    }
?>