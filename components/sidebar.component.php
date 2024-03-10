<?php
    require_once "core/database.php";

    $query = "SELECT DISTINCT memo_date FROM memos;";

    if ($ris = $dbc->query($query)){
        if ($ris->num_rows > 0){
            while ($dt = $ris->fetch_array()){
                echo "<a href='home?d=".$dt['memo_date']."'>".$dt['memo_date']."</a>" . "<br>";
            }
        } else {
            echo "There are no memos";
        }

        echo "<br>";
        echo "<a href='memo?add'>Add memo</a><br>";
        echo "<a href='category?add'>Add category</a>";
    } else {
        $error = "Impossible to fetch data.";
    }