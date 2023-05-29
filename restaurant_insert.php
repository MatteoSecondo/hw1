<?php

    //inserimento del ristorante
    $res = mysqli_query($conn, $query);

    if(!$res)
    {
        $error = "Errore nell'inserimento in ristoranti";
        exit;
    }

    //inserimento delle categorie
    $query = "SELECT * FROM categorie";
    $res = mysqli_query($conn, $query);
    $n_rows = mysqli_num_rows($res);

    if($n_rows > 0)
    {
        foreach($categories as $category)
        {
            $counter = 0;
            $res = mysqli_query($conn, "SELECT * FROM categorie");
            $n_rows = mysqli_num_rows($res);

            while($row = mysqli_fetch_assoc($res)) 
            {
                if($category != $row['nome'])
                {
                    $counter += 1;
                }

                //non la ha trovata nel db, quindi la aggiunge
                if($counter === $n_rows)
                {
                    $query = "INSERT INTO categorie VALUES ('$category')"; 

                    if(!mysqli_query($conn, $query))
                    {
                        $error = "Errore nell'inserimento in categorie";
                    }
                }
            }
        }
    }
    else
    {
        foreach($categories as $category)
            {
                $query = "INSERT INTO categorie VALUES ('$category')";

                if(!mysqli_query($conn, $query))
                {
                    $error = "Errore nell'inserimento in categorie";
                }
            }   
    }

    mysqli_free_result($res);

    //associo la categoria al ristorante
    foreach($categories as $category)
    {
        $query = "INSERT INTO ristoranti_categorie VALUES ('$name', '$category')";

        if(!mysqli_query($conn, $query))
        {
            $error = "Errore nell'inserimento in ristoranti_categorie";
        }
    }

?>