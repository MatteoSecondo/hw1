<?php

    require_once 'dbconfig.php';

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

    $name = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM recensioni WHERE ristorante = '". $name ."'";
    $res = mysqli_query($conn, $query);

    if(mysqli_num_rows($res) > 0)
    {
        $reviews = array();

        while($row = mysqli_fetch_assoc($res))
        {
            $email = mysqli_real_escape_string($conn, $row['utente']);

            $query = "SELECT nome, cognome FROM utenti WHERE email = '". $email ."'";
            $res2 = mysqli_query($conn, $query);

            if(mysqli_num_rows($res2))
            {
                $entry = mysqli_fetch_assoc($res2);

                $user = $entry['nome'];
                $surname= $entry['cognome'];
                $surname = substr($surname, 0, 1);
                $user = $user . ' ' . $surname . '.';

                $user = array('name' => $user);
                $review = array('rating' => $row['valutazione'], 'text' => $row['testo'], 'time_created' => $row['data'], 'user' => $user);
                $reviews[] = $review;
            }
        }

        $json = array('reviews' => $reviews);
        echo json_encode($json);
        mysqli_free_result($res2);

    }
    else
    {
        echo json_encode('');
    }

    mysqli_free_result($res);
    mysqli_close($conn);

?>