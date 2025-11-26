<?php
require 'connect.php';

// Controleerc of er een geldig ID is 
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geen geldig ID opgegeven.");
}
$id = (int)$_GET['id'];

// Als het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controleer of de gebruiker bevestigt dat hij wil verwijderen
    if (isset($_POST['bevestigen']) && $_POST['bevestigen'] === 'ja') {
        // Verwijder de melding uit de database
        $stmt = $conn->prepare("DELETE FROM meldingen WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: main.php");
        exit;
    } else {
        header("Location: main.php");
        exit;
    }
}

// Haal de melding op uit de database om te tonen aan de gebruiker
$stmt = $conn->prepare("SELECT * FROM meldingen WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$melding = $stmt->fetch(PDO::FETCH_ASSOC);

// Als de melding niet bestaat, toon foutmelding
if (!$melding) {
    die("Melding niet gevonden.");
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Verwijderen bevestigen</title>
    <style>
       /* Stijlen voor de pagina en het formulier */
       body {
           background: #fff;
           font-family: Arial, sans-serif;
           padding: 0;
           margin: 0;
           color: #111;
       }
       .container {
           background: #fff;
           max-width: 440px;
           margin: 50px auto 0 auto;
           padding: 32px 36px 28px 36px;
           border-radius: 10px;
           box-shadow: 0 4px 24px rgba(0,0,0,0.13);
           border: 2px solid #111;
       }
       h1, h2 {
           text-align: center;
           color: #d90429;
           margin-bottom: 28px;
           letter-spacing: 1px;
       }
       form label {
           display: block;
           margin-bottom: 12px;
           color: #111;
           font-weight: bold;
       }
       input[type="text"], select, input[type="number"] {
           width: 100%;
           padding: 9px 10px;
           margin-top: 4px;
           margin-bottom: 18px;
           border: 1px solid #111;
           border-radius: 5px;
           font-size: 15px;
           background: #fff;
           color: #111;
           box-sizing: border-box;
       }
       button[type="submit"], .btn, .back-link {
           background: #d90429;
           color: #fff;
           border: none;
           padding: 12px 0;
           width: 100%;
           border-radius: 5px;
           font-size: 17px;
           font-weight: bold;
           cursor: pointer;
           transition: background 0.2s, color 0.2s;
           margin-top: 10px;
           text-decoration: none;
           display: block;
           text-align: center;
       }
       button[type="submit"]:hover, .btn:hover, .back-link:hover {
           background: #111;
           color: #fff;
       }
       ul {
           color: #d90429;
           margin-bottom: 18px;
           padding-left: 20px;
       }
       .back-link {
           background: none;
           color: #d90429;
           border: none;
           margin-top: 18px;
           font-size: 15px;
           text-decoration: underline;
           font-weight: normal;
           width: auto;
       }
       .back-link:hover {
           color: #111;
           background: none;
       }
    </style>
</head>
<body>
    <div class="container">
        <!-- Vraag de gebruiker om bevestiging voor verwijderen -->
        <h2>Weet je zeker dat je deze melding wilt verwijderen?</h2>
        <p>
            <strong><?= ($melding['naam']) ?></strong> uit <strong><?= ($melding['klas']) ?></strong><br>
            Minuten te laat: <?= ($melding['minuten_te_laat']) ?><br>
            Reden: <?= ($melding['reden']) ?>
        </p>
        <!-- Formulier met twee knoppen: Ja (verwijderen) of Nee (terug) -->
        <form method="post">
            <button type="submit" name="bevestigen" value="ja" class="btn btn-yes">Ja, verwijderen</button>
            <button type="submit" name="bevestigen" value="nee" class="btn btn-no">Nee, terug</button>
        </form>
    </div>
</body>
</html>