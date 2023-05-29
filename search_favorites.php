<?php

    require_once 'auth.php';
                
    if (!checkAuth()) 
    {
        header("Location: login.php");
        exit;
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);

    $query = "SELECT ristorante FROM preferiti WHERE utente = '".$email."'";
    $preferiti = mysqli_query($conn, $query);

    if(mysqli_num_rows($preferiti) > 0) 
    {

        $businesses = array();

        while($row_p = mysqli_fetch_assoc($preferiti)) 
        {
            $query = "SELECT * FROM ristoranti WHERE nome = '".mysqli_real_escape_string($conn, $row_p['ristorante'])."'";
            include 'search_restaurant_db.php';
        }
        mysqli_close($conn);
        mysqli_free_result($ristoranti);
        mysqli_free_result($preferiti);
        $businesses = array("businesses" => $businesses);
        echo json_encode($businesses);
    }
    else
    {
        echo json_encode("Nessun preferito");
    }

?>