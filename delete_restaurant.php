<?php

    require_once 'auth.php';

    if (!checkAuth()) {
        header("Location: login.php");
        exit;
    } 

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

    $query = "SELECT immagine FROM ristoranti WHERE nome = '".mysqli_real_escape_string($conn, $_GET['name'])."'";
    $res = mysqli_query($conn, $query);

    $entry = mysqli_fetch_assoc($res);
    $path = $_SERVER['DOCUMENT_ROOT'] . '/webprogramming/hw1/' . $entry['immagine'];

    mysqli_free_result($res);

    $query = "DELETE FROM ristoranti WHERE nome = '".mysqli_real_escape_string($conn, $_GET['name'])."'";

    if(mysqli_query($conn, $query))
    {
        if(file_exists($path))
        {
            unlink($path);
        }
        
        echo json_encode('Deleted');
    }
    else
    {
        echo json_encode('');
    }

    mysqli_close($conn);

?>