<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    <?php
        $date = explode("-", $d)[2] . "/" . explode("-", $d)[1] . "/" . explode("-", $d)[0];
        $nr = $result->num_rows;
        if ($nr == 0)
            echo "Agenda | $date";
        else
            echo "Agenda | $date ($nr)";
    ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>

<?php
require "components/header.component.php";

require "components/sidebar.component.php";
echo "<br>";

if (isset($error) && $error != ""){
    echo $error;
} else {
    require_once "core/cipher.php";

    if ($result->num_rows == 0){
        echo "No records found ".(isset($_GET['d']) ? 'on date '.$date : "today").".";
    } else {
        while ($row = $result->fetch_array()){
            echo "<div style='padding: 10px'>";
            echo "<h1>".decrypt($row['memo_title'], $cipherkey)."</h1>";
            echo "<h4>Date: ".$row['memo_date']."</h4>";
            if (!empty($row['memo_time'])){
                echo "<h4>Time: ".$row['memo_time']."</h4>";
            }
            echo "<p>".decrypt($row['memo_text'], $cipherkey)."</p>";
            echo "</div>";
        }
    }
}
?>

</body>
</html>