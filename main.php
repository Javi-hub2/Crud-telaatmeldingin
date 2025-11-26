<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Challenge - Te Laat Meldingen</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background: #fff;
    padding: 40px;
    color: #111;
}
table {
    border-collapse: collapse;
    width: 60%;
    margin: 0 auto;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.13);
    border: 2px solid #111;
}
th, td {
    border: 1px solid #111;
    padding: 12px 16px;
    text-align: left;
}
th {
    background: #d90429;
    color: #fff;
    font-weight: bold;
}
tr:nth-child(even) {
    background: #f8f8f8;
}
tr:hover {
    background: #111;
    color: #fff;
}
h2 {
    text-align: center;
    color: #d90429;
}
.verwijder-btn {
    background: #d90429;
    color: #fff;
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.2s, color 0.2s;
    margin-right: 6px;
    display: inline-block;
}
.verwijder-btn:hover {
    background: #111;
    color: #fff;
}
.update-btn {
    background: #111;
    color: #fff;
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.2s, color 0.2s;
    display: inline-block;
}
.update-btn:hover {
    background: #d90429;
    color: #fff;
}
.toevoegen-btn {
    background: #d90429;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    margin-bottom: 20px;
    display: inline-block;
    transition: background 0.2s, color 0.2s;
    cursor: pointer;
}
.toevoegen-btn:hover {
    background: #111;
    color: #fff;
}
.top-bar {
    width: 60%;
    margin: 0 auto 20px auto;
    text-align: right;
}
.stats-table {
    margin-top: 30px;
    width: 60%;
    margin-left: auto;
    margin-right: auto;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.13);
    border-collapse: collapse;
    border: 2px solid #111;
}
.stats-table th, .stats-table td {
    border: 1px solid #111;
    padding: 12px 16px;
    text-align: left;
}
.stats-table th {
    background: #d90429;
    color: #fff;
    text-align: center;
}
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="create.php" class="toevoegen-btn">Weer eentje te laat!</a>
    </div>
    <?php
    require 'connect.php';

    // Haal alle meldingen op uit de database
    $sql = "SELECT * FROM meldingen";
    $result = $conn->query($sql);

    echo "<table border='0'>";
    echo "<tr><th>Naam</th><th>Klas</th><th>Minuten te laat</th><th>Reden</th><th>Datum</th><th>Acties</th></tr>";
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . ($row['naam']) . "</td>";
        echo "<td>" . ($row['klas']) . "</td>";
        echo "<td>" . ($row['minuten_te_laat']) . "</td>";
        echo "<td>" . ($row['reden']) . "</td>";
        echo "<td>" . ($row['datum']) . "</td>";
        echo "<td>
            <a class='verwijder-btn' href='delete.php?id=" . ($row['id']) . "'>Verwijderen</a>
            <a class='update-btn' href='update.php?id=" . ($row['id']) . "'>Updaten</a>
        </td>";
        echo "</tr>";
    }
    echo "</table>";

    // Haal statistieken op: hoogste, gemiddelde en totaal aantal minuten te laat
    $statStmt = $conn->query("SELECT 
        MAX(minuten_te_laat) AS max_minuten, 
        AVG(minuten_te_laat) AS avg_minuten, 
        SUM(minuten_te_laat) AS totaal_minuten 
        FROM meldingen");
    $stats = $statStmt->fetch(PDO::FETCH_ASSOC);

    echo "<table class='stats-table'>";
    echo "<tr><th colspan='2'>Statistieken</th></tr>";
    echo "<tr><td>Hoogste aantal minuten te laat</td><td>" . $stats['max_minuten'] . "</td></tr>";
    echo "<tr><td>Gemiddeld aantal minuten te laat</td><td>" . (is_null($stats['avg_minuten']) ? 0 : round($stats['avg_minuten'], 1)) . "</td></tr>";
    echo "<tr><td>Totaal aantal minuten te laat</td><td>" . $stats['totaal_minuten'] . "</td></tr>";
    echo "</table>";
    ?>
</body>
</html>
