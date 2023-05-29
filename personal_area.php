<?php

require_once 'auth.php';

    if (!checkAuth()) {
        header("Location: login.php");
        exit;
    }  

    //verifico l'esistenza di dati POST
    if ($check = (!empty($_POST['name']) && !empty($_POST['address']) && !empty($_POST['country']) && !empty($_POST['prefix']) && !empty($_POST['phone']) && !empty($_POST['categories']) && !empty($_POST['price']) && !empty($_FILES['photo'])))
    {
        $errors = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        //controllo il nome
        $query = "SELECT * FROM ristoranti WHERE nome = '".mysqli_real_escape_string($conn, $_POST['name'])."'";
        $res = mysqli_query($conn, $query);

        if(mysqli_num_rows($res) > 0)
        {
            $errors[] = "Nome del ristorante già esistente";
        }

        if (strlen($_POST['name']) > 30)
        {
            $errors[] = 'Nome troppo lungo';
        }

        //divido e verifico l'indirizzo
        $full_address = preg_split('/, /', $_POST['address']);

        if(count($full_address) != 2)
        {
            $errors[] = 'Indirizzo non valido';
        }
        else
        {
            $address = $full_address[0];
            $city = $full_address[1];
        }

        //verifico il telefono
        if(!preg_match('/[0-9]{8,15}/', $_POST['phone']))
        {
            $errors[] = 'Numero di telefono non valido';
        }

        $phone = $_POST['prefix'] . $_POST['phone'];

        //divido le categorie e controllo se ci sono doppioni
        $categories = preg_split('/, /', $_POST['categories']);

        if(count($categories) > 3 || $categories === 0)
        {
            $errors[] = 'Categorie non valide';
        }

        $categories = array_unique($categories);


        //verifico il prezzo
        if(!preg_match('/[1-5]{1}/', $_POST['price']))
        {
            $errors[] = 'Prezzo non valido';
        }

        //verifico la foto
        $file = $_FILES['photo'];
        $type = exif_imagetype($file['tmp_name']);
        $allowedExt = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_GIF => 'gif');
        if (isset($allowedExt[$type])) 
        {
            if ($file['error'] === 0) 
            {
                if ($file['size'] < 7000000) 
                {
                    $newFileName = uniqid('', true).".".$allowedExt[$type];
                    $src = 'db_images/' . $newFileName;
                } 
                else 
                {
                    $errors[] = "L'immagine non deve avere dimensioni maggiori di 7MB";
                }
            } 
            else 
                {
                    $errors[] = "Errore nel carimento del file";
                }
        } 
        else 
        {
            $errors[] = "I formati consentiti sono .png, .jpeg, .jpg e .gif";
        }
    }
    else
    {
        $errors = array("*Riempi tutti i campi ed aggiungi una foto");
    }

    //inserimento nel db
    if(count($errors) == 0)
    {
        foreach($categories as $element)
        {
            $element = mysqli_real_escape_string($conn, $element);
        }

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $address = mysqli_real_escape_string($conn, $address);
        $city = mysqli_real_escape_string($conn, $city);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $phone = mysqli_real_escape_string($conn, $phone);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $email = mysqli_real_escape_string($conn, $_SESSION['email']);

        $query = "INSERT INTO ristoranti (nome, città, paese, indirizzo, telefono, prezzo, immagine, proprietario) VALUES ('$name', '$city', '$country', '$address', '$phone', '$price', '$src', '$email')";
        include 'restaurant_insert.php';

        move_uploaded_file($file['tmp_name'], $src);

        mysqli_close($conn);
        
    }

    if($check && count($errors) != 0)
    {
        mysqli_close($conn);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Personale</title>
    <link rel="stylesheet" href="style.css">
    <script src="general.js" defer></script>
    <script src="personal_area.js" defer></script>
    <script src="hamburger_menu.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="space"></div>
    <div class="main-pages">
        <div class="overlay">
            <div class="welcome-personal_area">
                <h1>Benvenuto nella tua area personale</h1>
                <p>La prima sezione mostra i preferiti che hai aggiunto in precedenza. In questo modo è più facile accedere ai contenuti che ti piacciono di più e visualizarne le informazioni.</p>
                <p>La seconda sezione permette di poter aggiungere o eliminare ristoranti dalla piattaforma. Quest'area è dedicata ai proprietari di ristoranti che vogliono far conoscere le loro arrività ed avere dei feedback significativi grazie alle recensioni.</p> 
            </div>
        </div>
    </div>


    <h1 class="title" id="upper-title">I tuoi preferiti</h1>
    <div class="results" id="favorites"></div>
    <div id="modal" class="hidden"></div>

    <h1 class="title">I tuoi ristoranti</h1>
    <div class="results" id="owners_restaurant">
        <div class="restaurant">
        <h1>Inserisci un ristorante</h1>  
            <div class="img-container">
                <img src='./images/add_restaurant.png' class="start-upload">
            </div>
            <span class="hidden" id="e1_img">Inserisci una immagine</span>
            <span class="hidden" id="e2_img">Le dimensioni del file superano 7 MB</span>
            <span class="hidden" id="e3_img">Le estensioni consentite sono .jpeg, .jpg, .png e .gif</span>
            <div class="insert">
                <form method="post" enctype="multipart/form-data">
                    <div class="inputs">
                        <input type="file" name="photo" id="upload" data-selector="upload" accept=".jpg, .jpeg, image/gif, image/png">
                        <input type="text" name ="name" data-selector="name" placeholder="Nome" required>  
                        <div class="combo" data-selector="location">
                            <input type="text" name ="address" id="address" placeholder="Indirizzo" title="Inserisci l'indirizzo nel formato 'indirizzo, città'" required>
                            <select name="country" class="country" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <span class="hidden" id="e_address">Inserisci l'indirizzo nel formato 'indirizzo, città'</span>
                        <div class="combo" data-selector="phone"> 
                            <select name="prefix" class="prefix"required>
                                <option value=""></option>
                            </select>
                            <input type="tel" id="phone" name ="phone" placeholder="Telefono" title="Il numero di telefono può avere al massimo 15 cifre" pattern="[0-9]{8,15}" required>
                        </div>
                        <input type="text" name ="categories" id="categories" data-selector="category" placeholder="Categorie (almeno 1, massimo 3)" title="Inserisci le categorie nel formato 'categoria1, categoria2, categoria3'" required>   
                        <span class="hidden" id="e_categories">Inserisci le categorie nel formato 'cat1, cat2, cat3'</span>
                        <input type="number" name ="price" data-selector="price" min="1" max="5" placeholder="Prezzo" required>
                    </div>

                    <?php
                        if(isset($errors)) 
                        {
                            foreach($errors as $err) 
                            {
                                echo "<span class='server-error'>".$err."</span>";
                            }
                        } 
                    ?>

                    <input type="submit" value="Inserisci" class="submit">
                </form>
            </div>
            
        </div>
    </div>

    <div id="modal" class="hidden"></div>

    <?php include 'footer.php'; ?>
</body>
</html>