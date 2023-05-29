<?php

    require_once 'auth.php';
        
    if (!checkAuth()) 
    {
        echo json_encode("Access denied");
        exit;
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $src = mysqli_real_escape_string($conn, $_GET['src']);
    $categories = $_GET['categories'];
    $categories = json_decode($categories); 
    foreach ($categories as $category)
    {
        $category = mysqli_real_escape_string($conn, $category);
    }
    $address = mysqli_real_escape_string($conn, $_GET['address']);
    $city = mysqli_real_escape_string($conn, $_GET['city']);
    $country = mysqli_real_escape_string($conn, $_GET['country']);
    $phone = mysqli_real_escape_string($conn, $_GET['phone']);
    $price = mysqli_real_escape_string($conn, $_GET['price']);
    $review_count = mysqli_real_escape_string($conn, $_GET['review_count']);
    $rating = mysqli_real_escape_string($conn, $_GET['rating']);

    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    
    //controllo se esiste già questo ristorante
    $query = "SELECT * FROM ristoranti WHERE nome = '".$name."'";
    $res = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($res) > 0)
    {
        $err = "Nome del ristorante già esistente";
    }

    if(!isset($err))
    {
        $query = "INSERT INTO ristoranti VALUES ('$name', '$id', '$city', '$country', '$address', '$phone', '$price', '$review_count', '$rating', '$src', '$email')";
        include 'restaurant_insert.php';
    }
    
    //associo il ristorante all'utente
    $query = "INSERT INTO preferiti VALUES ('$email', '$name')";

    if(!mysqli_query($conn, $query))
    {
        $error = "Errore nell'inserimento in preferiti";
    }

    mysqli_close($conn);

    if(isset($error))
    {
        echo json_encode($error);
    }
    else
    {
        echo json_encode("Saved");
    }
           
?>