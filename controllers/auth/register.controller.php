<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register a new account</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
    if (isset($_SESSION['error'])){
        echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
        unset($_SESSION['error']);
    }
?>
    <form action="register" method="POST">
        <input type="email" name="email" placeholder="Email address...">
        <input type="text" name="username" placeholder="Choose a username...">
        <input type="password" name="password" placeholder="Password...">
        <input type="password" name="password1" placeholder="Repeat password...">

        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>

<?php
    require_once "core/database.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['email']) && $_POST['email'] != "" &&
            isset($_POST['username']) && $_POST['username'] != "" &&
            isset($_POST['password']) && $_POST['password'] != "" &&
            isset($_POST['password1']) && $_POST['password1'] != ""
        ){
            // Check if username or mail already exists
            $us_hash = openssl_digest($_POST['username'], "SHA256");
            $email = $_POST['email'];
            $query = "SELECT * FROM users WHERE username_hash = '$us_hash' OR email = '$email';";
            $res = $dbc->query($query);
            if ($res != false && $res->num_rows == 0){
                if ($_POST['password'] == $_POST['password1']){
                    $salt = md5(rand());
                    $pw_hash = openssl_digest($salt . $_POST['password'], "SHA256");
                    $private_key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

                    $ins_query = "INSERT INTO users (username_hash, password_hash, salt, email, user_private_key) VALUES (?,?,?,?,?);";
                    if ($stmt = $dbc->prepare($ins_query)){
                        if ($stmt->bind_param("sssss", $us_hash, $pw_hash, $salt, $email, $private_key) && $stmt->execute()){
                            $_SESSION['success'] = "Account successfully added";
                            header("Location: home");
                        } else {
                            $_SESSION['error'] = "Cannot add the account";
                            header("Location: register");
                        }
                        $stmt->close();
                    } else {
                        $_SESSION['error'] = "Error: $dbc->error";
                        header("Location: register");
                    }
                } else {
                    $_SESSION['error'] = "Passwords don't match";
                    header("Location: register");
                }
            } else {
                $_SESSION['error'] = "Username or email already exists";
                header("Location: register");
            }
        }
    }
?>