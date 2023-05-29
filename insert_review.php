<?php

    require_once 'auth.php';

    if (!checkAuth()) {
        header("Location: login.php");
        exit;
    } 

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

    $text= mysqli_real_escape_string($conn, $_GET['text']);
    $review_rating = mysqli_real_escape_string($conn, $_GET['review_rating']);
    $date =  date("Y-m-d");
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);

    $name = mysqli_real_escape_string($conn, $_GET['name']);
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

    $query = "SELECT * FROM ristoranti WHERE nome = '". $name ."'";
    $res = mysqli_query($conn, $query);

    if(!mysqli_num_rows($res))
    {
        $query = "INSERT INTO ristoranti VALUES ('$name', '$city', '$country', '$address', '$phone', '$price', '$review_count', '$rating', '$src', '$email')";
        include 'restaurant_insert.php';
    }

    $query = "SELECT nome, cognome FROM utenti WHERE email = '". $email ."'";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) > 0)
    {
        $entry = mysqli_fetch_assoc($res);

        $user = $entry['nome'];
        $surname= $entry['cognome'];
        $surname = substr($surname, 0, 1);
        $user = $user . ' ' . $surname . '.';

        $query = "INSERT INTO recensioni (testo, valutazione, data, utente, ristorante) VALUES ('$text', '$review_rating', '$date', '$email', '$name')";
        if(mysqli_query($conn, $query))
        {
            $user = array('name' => $user);
            $review = array('rating' => $review_rating, 'text' => $text, 'time_created' => $date, 'user' => $user);
            $reviews = array($review);
            $json = array('reviews' => $reviews);
            echo json_encode($json);
        }
    }

    mysqli_free_result($res);
    mysqli_close($conn);
?>