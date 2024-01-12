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
    session_start();
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
            $query = "SELECT * FROM users WHERE username = '$us_hash' OR email = '$email';";
            $res = $dbc->query($query);
            if ($res != false && $res->num_rows == 0){
                if ($_POST['password'] == $_POST['password1']){
                    $pw_hash = openssl_digest($_POST['password'], "SHA256");
                    $private_key = md5(rand() . time());
                    
                    $ins_query = "INSERT INTO users (username, password, email, user_private_key) VALUES (?,?,?,?);";
                    if ($stmt = $dbc->prepare($ins_query)){
                        $stmt->bind_param("ssss", $us_hash, $pw_hash, $email, $private_key);

                        if ($stmt->execute()){
                            echo "<div class='alert alert-success' role='alert'>Account successfully added</div>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Cannot add the account</div>";
                        }
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Error: $dbc->error</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Passwords don't match</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>Username already exists</div>";
            }
        }
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