<?php

    require_once 'auth.php';

    if (checkAuth()) {
        header("Location: index.php");
        exit;
    }   

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $query = "SELECT * FROM utenti WHERE email = '".$email."'"; 

    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) > 0)
    {
        echo json_encode('Exists');
    }
    else
    {
        echo json_encode('');
    }

?>