<?php
session_start();
if (!isset($_SESSION['szamla_elkeszult'])) {
    header("Location: index.php");
    exit();
}
unset($_SESSION['szamla_elkeszult']);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Köszönjük a foglalást!</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #d4f9e9, #f0fff4);
            font-family: 'Roboto', sans-serif;
            text-align: center;
            padding: 60px 20px;
            color: #2c3e50;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            display: inline-block;
            max-width: 600px;
            margin-top: 50px;
            animation: fadeIn 1s ease-in-out;
        }
        .checkmark {
            font-size: 80px;
            color: #2ecc71;
            animation: pop 0.6s ease forwards;
        }
        h1 {
            color: #27ae60;
            font-size: 2.2em;
            margin-top: 10px;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 30px;
        }
        .gomb {
            display: inline-block;
            padding: 12px 24px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .gomb:hover {
            background: #219150;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pop {
            0% { transform: scale(0.6); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkmark">✔</div>
        <h1>Sikeres foglalás!</h1>
        <p>Köszönjük, hogy az <strong>InnovTrade</strong> szolgáltatását választotta.<br>
        A számlát e-mailben elküldtük Önnek, és <strong>PDF formátumban</strong> is elérhető.</p>

        <a href="index.php" class="gomb">Vissza a főoldalra</a>
        <?php if (isset($_SESSION['szamla_fajl']) && file_exists($_SESSION['szamla_fajl'])): ?>
            <br><br>
            <a href="szamla_megjelenites.php" class="gomb" style="background:#2980b9;">Számla megtekintése</a>
        <?php endif; ?>
    </div>
</body>
</html>
