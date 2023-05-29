<?php

    require_once 'auth.php';
                
    if (!checkAuth()) 
    {
        header("Location: login.php");
        exit;
    }

    $ristoranti = mysqli_query($conn, $query);

    if(mysqli_num_rows($ristoranti) > 0)
    {
        
        while($row_r = mysqli_fetch_assoc($ristoranti))
        {
            $query = "SELECT * FROM ristoranti_categorie WHERE ristorante = '".mysqli_real_escape_string($conn, $row_r['nome'])."'";
            $categorie = mysqli_query($conn, $query);

            $categories = array();

            while($row_c = mysqli_fetch_assoc($categorie)) 
            {
                $categories[] = array('title' => $row_c['categoria']);
            }

            $location = array('city' => $row_r['città'], 'country' => $row_r['paese'],
                              'address1' => $row_r['indirizzo']);

            $prezzo = '';
                    
            for ($i = 0; $i < $row_r['prezzo']; $i++)
            {
                $prezzo .= '$'; 
            }

            $ristorante = array('name' => $row_r['nome'], 'id' => $row_r['id'], 'location' => $location, 'categories' => $categories, 
                                'phone' => $row_r['telefono'], 'image_url' => $row_r['immagine'],
                                'price' => $prezzo, 'review_count' => $row_r['n_recensioni'],
                                'rating' => $row_r['valutazione']);

            $businesses[] = $ristorante;
        }

    }

?>