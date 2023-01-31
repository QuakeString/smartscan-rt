<?php
    include("dbCon.php");
    session_start();
  
    $bid = 0;
    if(!empty($_GET['bid']) && $_GET['bid'] > 0)
    {
        $bid = $_GET['bid'];
        $sql = "CALL sp_DeleteBatch (" . $bid . ");";
        $conn->query($sql);
    }

    header("Location: reports.php");
?>