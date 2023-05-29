<?php

    require_once 'auth.php';
                    
    if (!checkAuth()) 
    {
        header("Location: login.php");
        exit;
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);

    $businesses = array();

    $query = "SELECT * FROM ristoranti WHERE proprietario = '".$email."'";
    include 'search_restaurant_db.php';

    $businesses = array("businesses" => $businesses);
    echo json_encode($businesses);
    
    mysqli_close($conn);
    mysqli_free_result($ristoranti);
    

?>