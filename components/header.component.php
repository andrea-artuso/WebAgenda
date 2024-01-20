<header>
    <?php
        echo "@".$_SESSION['logged_user']["username"];
        echo "<a href='logout'>Logout</a>";
    ?>
</header>