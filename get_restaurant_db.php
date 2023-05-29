<?php

    require_once 'auth.php';
                    
    if (!checkAuth()) 
    {
        header("Location: login.php");
        exit;
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $location = mysqli_real_escape_string($conn, $_GET['location']);

    $businesses = array();

    $query = "SELECT * FROM ristoranti WHERE città = '". $location ."' XOR nome = '". $location ."'";
    include 'search_restaurant_db.php';

    if(count($businesses) > 0)
    {
        $businesses = array("businesses" => $businesses);
        echo json_encode($businesses);
    }
    else
    {
        echo json_encode(array("error" => " "));
    }
    
    mysqli_close($conn);
    mysqli_free_result($ristoranti);
    

?>