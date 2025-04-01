<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bankkártyás fizetés - InnovTrade</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      padding: 20px;
    }
    .kartya-box {
      max-width: 500px;
      margin: 40px auto;
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .kartya-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    input[type="text"], input[type="number"], input[type="submit"] {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    input[type="submit"] {
      background-color: #27ae60;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    input[type="submit"]:hover {
      background-color: #219150;
    }
    .logo {
      display: block;
      margin: 0 auto 20px;
      max-width: 100px;
    }
  </style>
</head>
<body>
  <div class="kartya-box">
    <img src="kepek/logo.png" alt="InnovTrade logó" class="logo">
    <h2>Bankkártyás Fizetés</h2>
    <form action="fizetesfeldolgozas.php" method="post">
      <div class="form-group">
        <label for="kartyaszam">Kártyaszám</label>
        <input type="text" id="kartyaszam" name="kartyaszam" placeholder="1234 5678 9012 3456" required>
      </div>
      <div class="form-group">
        <label for="nev">Kártyatulajdonos neve</label>
        <input type="text" id="nev" name="nev" placeholder="Teljes név" required>
      </div>
      <div class="form-group">
        <label for="lejarat">Lejárati dátum</label>
        <input type="text" id="lejarat" name="lejarat" placeholder="MM/YY" required>
      </div>
      <div class="form-group">
        <label for="cvv">CVV</label>
        <input type="number" id="cvv" name="cvv" placeholder="123" required>
      </div>
      <input type="submit" value="Fizetés elküldése">
    </form>
  </div>
</body>
</html>
