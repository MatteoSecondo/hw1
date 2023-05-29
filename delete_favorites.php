<?php

    require_once 'auth.php';
                
    if (!checkAuth()) 
    {
        echo json_encode("");
        exit;
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);

    $query = "DELETE FROM preferiti WHERE utente = '".$email."' AND ristorante = '".$name."'";
    
    if(!mysqli_query($conn, $query)) 
    {
        echo json_encode("Saved");
    }
    else
    {   
        echo json_encode("");
    }

    mysqli_close($conn);
?>