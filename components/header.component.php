<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    <?php
        if (!isset($_GET['d'])){
            $date = date("d/m/Y");
        } else {
            $gg = explode("-", $_GET['d'])[2];
            $mm = explode("-", $_GET['d'])[1];
            $yy = explode("-", $_GET['d'])[0];
            $date = "$gg/$mm/$yy";
        }
        echo "Agenda | $date";
    ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>