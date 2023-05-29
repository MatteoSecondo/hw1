<?php

    require_once 'auth.php';

    if (checkAuth()) {
        header("Location: index.php");
        exit;
    }   

    //verifico l'esistenza di dati POST
    if (!empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['pass_confirm']))
    {
        $errors = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        //controllo email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email non valida";
        } 
        else 
        {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM utenti WHERE email = '".$email."'");
            if (mysqli_num_rows($res) > 0) 
            {
                $errors[] = "Email già utilizzata";
            }
        }

        //controllo password
        
        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@#$!%*?&]{8,16}$/', $_POST['password'])) 
        {
            $errors[] = "Password non valida";
        }

        //conferma password
        if (strcmp($_POST["password"], $_POST["pass_confirm"]) != 0) {
            $errors[] = "Le password non coincidono";
        }

        //registrazione nel db
        if (count($errors) == 0) 
        {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO utenti VALUES('$email', '$password', '$name', '$surname')";
            
            if (mysqli_query($conn, $query))
            {
                $_SESSION["email"] = $_POST["email"];
                //alternativa:  $_SESSION["id"] = mysqli_insert_id($conn);
                mysqli_free_result($res);
                mysqli_close($conn);
                header("Location: index.php");
                exit;
            } 
            else 
            {
                $errors[] = "Errore di connessione al Database";
            }
        }
        mysqli_close($conn);
    }
    else
    {
        $errors = array("*Riempi tutti i campi");
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="style.css">
    <script src="signup.js" defer></script>
    <script src="hamburger_menu.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="space"></div>
    <div class="other-pages">
        <div class="overlay">
            <form method="post">
                <h1>Registrazione</h1>
                <input type="text" name="name" placeholder="Nome" required <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?> >
                <input type="text" name="surname" placeholder="Cognome" required <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?> >  
                <input type="email" name="email" id="email" placeholder="Email" title="Inserisci una mail valida." required <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>>            
                <span class="hidden" id="e_email">Email già registrata</span>                                     
                <input type="password" name="password" id="password" placeholder="Password" title="Inserisci una password che contiene almeno 1 lettera minuscola, una maiuscola, un numero e deve essere compresa tra 8 e 16 caratteri." required>   
                <span class="hidden" id="e_password">Password non valida</span>  
                <span class="hidden" id="e2_password">Inserisci una password che contiene almeno 1 lettera minuscola, una maiuscola, un numero e deve essere compresa tra 8 e 16 caratteri</span>                                             
                <input type="password" name="pass_confirm" id="pass_confirm" placeholder="Conferma password" required>
                <span class="hidden" id="e_pass_confirm">Le password non coincidono</span>         
                <?php
                if(isset($errors)) 
                    {
                        foreach($errors as $err) 
                        {
                            echo "<span class='server-error'>".$err."</span>";
                        }
                    } 
                ?>

                <input type="submit" value="Registrati" class="submit">
                <p>Hai già un account?<a href="login.php"> Accedi</a></p>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>