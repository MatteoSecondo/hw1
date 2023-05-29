<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatti</title>
    <link rel="stylesheet" href="style.css">
    <script src="hamburger_menu.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="space"></div>
    <div class="other-pages">
        <div class="overlay">
            <form action="https://formspree.io/f/mgebqljd" method="POST">
                <h1>Contattaci</h1>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="message" id="message" placeholder="Inserisci il messaggio che vuoi inviarci" required></textarea>
                <input type="submit" value="Invia" class="submit">
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>