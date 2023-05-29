<?php
    require_once 'auth.php';

    if (checkAuth()) {
        header("Location: index.php");
        exit;
    }  

    if (!empty($_POST["email"]) && !empty($_POST["password"]) )
    {
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        $email = mysqli_real_escape_string($conn, $_POST['email']);   
        $query = "SELECT * FROM utenti WHERE email = '".$email."'";
        $res = mysqli_query($conn, $query);
        mysqli_close($conn);

        if (mysqli_num_rows($res) > 0) 
        {
            $entry = mysqli_fetch_assoc($res);
            mysqli_free_result($res);

            if (password_verify($_POST['password'], $entry['password'])) 
            {
                // Imposto una sessione dell'utente
                $_SESSION["email"] = $entry['email'];
                header("Location: index.php");
                exit;
            }
        }
        else
        {
            mysqli_free_result($res);
        }

        // Se l'utente non è stato trovato o la password non ha passato la verifica
        $error = "Email e/o password errati.";
    }
    else
    {
        $error = "*Inserisci email e password.";
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso</title>
    <link rel="stylesheet" href="style.css">
    <script src="login.js" defer></script>
    <script src="hamburger_menu.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="space"></div>
    <div class="other-pages">
        <div class="overlay">
            <form method="post">
                <h1>Accedi</h1>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <?php
                if(isset($error)) 
                    {
                        echo "<span class='error'>".$error."</span>";
                    } 
                ?>

                <input type="submit" value="Accedi" class="submit">
                <p>Non hai ancora un account?<a href="signup.php"> Registrati</a></p>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>