<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    <script src="index.js" defer></script>
    <script src="general.js" defer></script>
    <script src="hamburger_menu.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="space"></div>
    <div class="main-pages">
        <div class="overlay">
            <div class="start-page">
                <h1>Restaurant Compare</h1> 
                <p>
                    Benvenuti nel nostro sito web dedicato alla ricerca di ristoranti! Siamo qui per semplificare la vostra esperienza culinaria offrendovi un'ampia selezione di ristoranti da esplorare. Che siate alla ricerca di una cucina tradizionale, internazionale o gourmet, il nostro sito vi fornirà un elenco dettagliato di opzioni culinarie che soddisferanno i vostri gusti. Potrete filtrare le ricerche in base al nome, alla posizione, alla categoria.
                </p>

                <p>
                    Inoltre è possibile scrivere recensioni da parte degli utenti, garantendovi di trovare il luogo perfetto per una cena speciale o un pranzo veloce. Esplorate il nostro sito web e lasciatevi ispirare dalla varietà di gustosi piatti offerti dai migliori ristoranti. Siamo qui per rendere la vostra ricerca facile, veloce e appagante!
                </p>
            </div>
                
            <form method="get" action="search_restaurant.php">
                <h1>Cerca tra i migliori ristoranti:</h1>
                <input type="text" name="location" id="location" placeholder="Nome o luogo (obbligatorio)" required>
                <input type="text" name="categories" id="categories" title="Inserisci le categorie nel formato 'categoria1,categoria2,categoria3'" placeholder="Categorie (opzionale)">
                <span class="hidden" id="e_categories">Inserisci le categorie nel formato 'cat1,cat2,cat3'</span>
                <input type="submit" value="Cerca" class="submit">
            </form>
        </div>
    </div>
        
    <div class="hidden" id="results"></div>
    <div id="modal" class="hidden"></div>

    <?php include 'footer.php'; ?>
</body>
</html>