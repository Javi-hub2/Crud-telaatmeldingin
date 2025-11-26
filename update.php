<?php

require 'connect.php';

$klassen = ['3A', '3B', '3C'];
$minuten_opties = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, 105, 110, 115, 120];
$max_naam = 40;  
$max_reden = 100; 
$errors = [];     

// Controleer of er een geldig ID is meegegeven via de URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geen geldig ID opgegeven.");
}
$id = (int)$_GET['id'];

// Haal de melding op uit de database
$stmt = $conn->prepare("SELECT * FROM meldingen WHERE id = ?");
$stmt->execute([$id]);
$melding = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$melding) {
    die("Melding niet gevonden.");
}

// Als het formulier is verzonden (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal en trim de ingevulde waarden op
    $naam = trim($_POST['naam'] ? $_POST['naam'] : '');
    $klas = trim($_POST['klas'] ?? '');
    $minuten = trim($_POST['minuten'] ?? '');
    $reden = trim($_POST['reden'] ?? '');

    // Validatie van de naam
    if ($naam === '') {
        $errors[] = "Naam is verplicht.";
    } elseif (mb_strlen($naam) > $max_naam) {
        $errors[] = "Naam mag maximaal $max_naam tekens zijn.";
    }
    // Validatie van de klas
    if ($klas === '' || !in_array($klas, $klassen)) {
        $errors[] = "Klas is verplicht.";
    }
    // Validatie van de minuten te laat
    if (!is_numeric($minuten) || (int)$minuten < 0 || (int)$minuten > 120) {
        $errors[] = "Minuten te laat moet een getal tussen 0 en 120 zijn.";
    }
    // Validatie van de reden
    if ($reden === '') {
        $errors[] = "Reden is verplicht.";
    } elseif (mb_strlen($reden) > $max_reden) {
        $errors[] = "Reden mag maximaal $max_reden tekens zijn.";
    }

    // Als er geen fouten zijn, update de melding in de database
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE meldingen SET naam = ?, klas = ?, minuten_te_laat = ?, reden = ? WHERE id = ?");
        $stmt->execute([$naam, $klas, $minuten, $reden, $id]);
        // Stuur gebruiker terug naar het overzicht
        header("Location: main.php");
        exit;
    }
} else {
    // Vul het formulier vooraf in met bestaande waarden uit de database
    $_POST = $melding;
    $_POST['minuten'] = $melding['minuten_te_laat']; // Zet minuten goed in het formulier
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Melding aanpassen</title>
    <style>
        body {
            background: #fff;
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            color: #111;
        }
        .container {
            background: #fff;
            max-width: 420px;
            margin: 50px auto 0 auto;
            padding: 32px 36px 28px 36px;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.13);
            border: 2px solid #111;
        }
        h1 {
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
        button[type="submit"] {
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
        }
        button[type="submit"]:hover {
            background: #111;
            color: #fff;
        }
        ul {
            color: #d90429;
            margin-bottom: 18px;
            padding-left: 20px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #d90429;
            text-decoration: underline;
            font-size: 15px;
            background: none;
            border: none;
            font-weight: normal;
        }
        .back-link:hover {
            color: #111;
            background: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Melding aanpassen</h1>
        <?php if (!empty($errors)): ?>
            <!-- Toon foutmeldingen als die er zijn -->
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <!-- Formulier voor het aanpassen van de melding -->
        <form method="post">
            <label>Naam:
                <input type="text" name="naam" maxlength="<?= $max_naam ?>" value="<?= htmlspecialchars($_POST['naam'] ?? '') ?>">
            </label>
            <label>Klas:
                <select name="klas">
                    <option value="">-- Kies klas --</option>
                    <?php foreach ($klassen as $optie): ?>
                        <option value="<?= $optie ?>" <?= (isset($_POST['klas']) && $_POST['klas'] === $optie) ? 'selected' : '' ?>><?= $optie ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Minuten te laat:
                <select name="minuten">
                    <?php foreach ($minuten_opties as $optie): ?>
                        <option value="<?= $optie ?>" <?= (isset($_POST['minuten']) && $_POST['minuten'] == $optie) ? 'selected' : '' ?>><?= $optie ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Reden:
                <input type="text" name="reden" maxlength="<?= $max_reden ?>" value="<?= htmlspecialchars($_POST['reden'] ?? '') ?>">
            </label>
            <button type="submit">Opslaan</button>
        </form>
        <!-- Link terug naar het overzicht -->
        <a href="main.php" class="back-link">&larr; Terug naar overzicht</a>
    </div>
</body>
</html>