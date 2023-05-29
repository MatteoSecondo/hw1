<?php

    require_once 'auth.php';

    if (!checkAuth()) {
        header("Location: login.php");
        exit;
    }
    
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $query = "SELECT * FROM json";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res))
    {
        $entry = mysqli_fetch_assoc($res);
        $json = $entry['json'];
        echo $json;
    }
    else
    {
        echo json_encode('');
    }

?>