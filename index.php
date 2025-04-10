<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
// Csatlakozás az adatbázishoz, ha még nem történt meg
include 'db.php';

$fejlecHatter = 'kepek/alap.png'; // Alapértelmezett háttérkép
$lekerdezes = $kapcsolat->query("SELECT ertek FROM beallitasok WHERE kulcs = 'fejléc_kep'");
if ($lekerdezes && $lekerdezes->num_rows > 0) {
    $sor = $lekerdezes->fetch_assoc();
    $fejlecHatter = $sor['ertek'];
}
?>
  <title>InnovTrade - Autókölcsönző</title>
  <?php
  // Foglalt autók kigyűjtése JavaScript változóba
  $foglaltAutok = [];
  $eredmeny = $kapcsolat->query("SELECT f.datum, fa.auto_nev FROM foglalasok f JOIN foglalas_autok fa ON f.id = fa.foglalas_id");
  if ($eredmeny && $eredmeny->num_rows > 0) {
      while ($sor = $eredmeny->fetch_assoc()) {
          $datum = $sor['datum'];
          $auto = $sor['auto_nev'];
          if (!isset($foglaltAutok[$auto]) || strtotime($datum) > strtotime($foglaltAutok[$auto])) {
              $foglaltAutok[$auto] = $datum;
          }
      }
  }
  ?>
  <script>
    window.foglaltAutok = <?php echo json_encode($foglaltAutok, JSON_UNESCAPED_UNICODE); ?>;
    document.addEventListener("DOMContentLoaded", function () {
      megjelenitResz('kezdolap');
      kategoriaMegjelenites('osszes');
    });
  </script>
  <link rel="stylesheet" href="vizsga.css">
  <script src="vizsga.js" defer></script>
  <style>
  .dropdown {
    position: relative;
    display: inline-block;
  }
    .dropdown {
      position: relative;
      display: inline-block;
    }
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background-color: #dddddd;
      min-width: 200px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 5px;
    }
    .dropdown-menu a {
      color: #000;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    .dropdown-menu a:hover {
      background-color: #ff0000;
    }
    .dropdown:hover .dropdown-menu {
      display: block;
    }
    #regisztracio, #belepes {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 90%;
      max-width: 400px;
      background-color: #fff;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      border-radius: 10px;
      z-index: 1000;
      display: none;
    }
    #felhasznalo-info {
      position: fixed;
      top: 10px;
      right: 10px;
      background-color: #2a9d8f;
      color: #fff;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 0.9rem;
    }
    #felhasznalo-info a {
      color: #fff;
      text-decoration: none;
      margin-left: 10px;
    }
    section:not(#kezdolap):not(#autok) {
      display: none;
    }
    .btn {
    background-color: white;
    color: black;
    padding: 10px 15px;
    border-radius: 5px;
    margin: 5px;
    text-decoration: none;
    font-weight: bold;
}

.btn:hover {
    background-color: #ddd;
}
  </style>
</head>
<body>
  <header style="background: url('kepek/kep1.png') no-repeat center center; background-size: cover; padding: 60px 0; text-align: center; color: white;">
    <div class="header-container">
        <img src="kepek/logo.png" alt="InnovTrade logó" style="width: 100px; border-radius: 50%;">
        <h1 style="font-size: 3em; text-shadow: 2px 2px 5px #000;">INNOVTRADE - AUTÓKÖLCSÖNZŐ</h1>
        <p style="font-size: 1.2em; text-shadow: 1px 1px 3px #000;">Minőségi autók minden kategóriában. Találd meg az igazit!</p>
        <nav>
            <a href="index.php" class="btn">Kezdőlap</a>
            <a href="#" class="btn">Autók ▼</a>
            <a href="foglalas.php" class="btn">Foglalás</a>
            <a href="kapcsolat.php" class="btn">Kapcsolat</a>
            <a href="velemenyek.php" class="btn">Vélemények</a>
        </nav>
    </div>
</header>
      <h1>InnovTrade - Autókölcsönző</h1>
    </div>
    <p class="header-description">Minőségi autók minden kategóriában. Találd meg az igazit!</p>
    <nav>
      <ul>
        <li><a href="#kezdolap" onclick="megjelenitResz('kezdolap')">Kezdőlap</a></li>
        <li class="dropdown">
          <a href="#autok">Autók ▼</a>
          <div class="dropdown-menu">
            <a href="#osszes" onclick="kategoriaMegjelenites('osszes')">Összes Autó</a>
            <a href="#gazdasagos" onclick="kategoriaMegjelenites('gazdasagos')">Gazdaságos Autók</a>
            <a href="#csaladi" onclick="kategoriaMegjelenites('csaladi')">Családi Autók</a>
            <a href="#luxus" onclick="kategoriaMegjelenites('luxus')">Luxus Autók</a>
            <a href="#sport" onclick="kategoriaMegjelenites('sport')">Sport Autók</a>
          </div>
        </li>
        <li><a href="#foglalas" onclick="megjelenitResz('foglalas')">Foglalás</a></li>
        <li><a href="#kapcsolat" onclick="megjelenitResz('kapcsolat')">Kapcsolat</a></li>
        <li><a href="#velemenyek" onclick="megjelenitResz('velemenyek')">Vélemények</a></li>
      </ul>
    </nav>
  </header>
  <script>
    function megjelenitResz(reszId) {
      document.querySelectorAll("main section").forEach(section => {
        section.style.display = "none";
      });
      const szekcio = document.getElementById(reszId);
      if (szekcio) szekcio.style.display = "block";
    }
  </script>

</header>
  <div style="text-align: center; margin: 30px 0;">
    <input type="text" id="auto-kereso" placeholder="Keresés autó név vagy márka szerint..." style="width: 60%; padding: 10px; font-size: 1rem; border-radius: 6px; border: 1px solid #ccc;">
</div>

  
  <div id="felhasznalo-info">
    <?php if(isset($_SESSION['felhasznalo'])): ?>
        <span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['felhasznalo']); ?></span>
        <a href="kijelentkezes.php" title="Kijelentkezés">
            <img src="kepek/logout_icon.png" alt="Kijelentkezés" style="width:25px; vertical-align:middle;">
        </a>
    <?php else: ?>
        <a href="#" id="belepes-gomb">Bejelentkezés</a> |
        <a href="#" id="regisztracio-gomb">Regisztráció</a>
    <?php endif; ?>
</div>


  
  <!-- Regisztráció és Belépés gombok (ha nincs bejelentkezve) -->
  <?php if(!isset($_SESSION['felhasznalo'])): ?>
  <?php endif; ?>
  
  <div id="overlay"></div>
  
  <!-- Regisztrációs űrlap -->
  <section id="regisztracio">
    <h2>Regisztráció</h2>
    <form id="regisztracio-urlap" action="regisztracio.php" method="POST">
      <label for="felhasznalonev">Felhasználónév:</label>
      <input type="text" id="felhasznalonev" name="felhasznalonev" required placeholder="Írd be a felhasználóneved">
      <label for="email">Email cím:</label>
      <input type="email" id="email" name="email" required placeholder="Írd be az email címed">
      <label for="jelszo">Jelszó:</label>
      <input type="password" id="jelszo" name="jelszo" required placeholder="Írd be a jelszavad">
      <label for="jelszo_megerositese">Jelszó megerősítése:</label>
      <input type="password" id="jelszo_megerositese" name="jelszo_megerositese" required placeholder="Erősítsd meg a jelszavad">
      <button type="submit">Regisztrálok</button>
    </form>
    <button class="bezaras-gomb" onclick="zarasFelulet()">Bezárás</button>
  </section>
  
  <!-- Belépési űrlap -->
  <section id="belepes">
    <h2>Belépés</h2>
    <form id="belepes-urlap" action="bejelentkezes.php" method="POST">
      <label for="felhasznalonev">Felhasználónév:</label>
      <input type="text" id="felhasznalonev" name="felhasznalonev" required placeholder="Írd be a felhasználóneved">
      <label for="jelszo">Jelszó:</label>
      <input type="password" id="jelszo" name="jelszo" required placeholder="Írd be a jelszavad">
      <button type="submit">Belépek</button>
    </form>
    <button class="bezaras-gomb" onclick="zarasFelulet()">Bezárás</button>
  </section>
  
  <main>
    <!-- Kezdőlap -->
    <section id="kezdolap">
      <h2>Üdvözlünk az InnovTrade Autókölcsönzőnél!</h2>
      <p>Találd meg a tökéletes autót bármilyen alkalomra. Foglalj egyszerűen és gyorsan!</p>
    </section>
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
  <input type="text" id="kereso-input" placeholder="Keresés név vagy leírás alapján..." style="flex: 1; padding: 8px; font-size: 1rem;">
  <select id="szuro-select" style="padding: 8px; font-size: 1rem;">
    <option value="">Rendezés nélkül</option>
    <option value="abc_az">ABC szerint (A → Z)</option>
    <option value="abc_za">ABC szerint (Z → A)</option>
    <option value="ar_nov">Ár szerint növekvő</option>
    <option value="ar_csok">Ár szerint csökkenő</option>
  </select>
  <select id="kategoriak-select" style="padding: 8px; font-size: 1rem;">
    <option value="osszes">Összes kategória</option>
    <option value="gazdasagos">Gazdaságos</option>
    <option value="csaladi">Családi</option>
    <option value="luxus">Luxus</option>
    <option value="sport">Sport</option>
  </select>
</div>

    <section id="autok">
  <h2>Bérelhető Autóink</h2>
  <?php
// Autók lekérdezése kategóriánként
$kategoriaLista = [
  'gazdasagos' => 'Gazdaságos Autók',
  'csaladi' => 'Családi Autók',
  'luxus' => 'Luxus Autók',
  'sport' => 'Sport Autók'
];
?>

<div id="osszes" class="kategoria" style="display: block;">
  <h3>Összes Autó</h3>
  <div class="autok-halo">
    <?php
    $sql = "SELECT * FROM autok";
    $eredmeny = $kapcsolat->query($sql);

    if ($eredmeny && $eredmeny->num_rows > 0) {
        while ($auto = $eredmeny->fetch_assoc()) {
            $kep = htmlspecialchars($auto['kep']);
            $nev = htmlspecialchars($auto['nev']);
            $ar = number_format($auto['ar'], 0, ',', ' ');
            $leiras = htmlspecialchars($auto['leiras']);
            $kategoria = htmlspecialchars($auto['kategoria']);

            echo "
            <div class=\"auto $kategoria\">
              <img src=\"kepek/$kep\" alt=\"$nev\">
              <h3>$nev</h3>
              <p>Napi díj: $ar Ft</p>
              <p>$leiras</p>
              <button onclick=\"hozzaadKosarhoz('$nev', {$auto['ar']})\">Kosárba</button>
            </div>
            ";
        }
    } else {
        echo "<p>Jelenleg nincs elérhető autó.</p>";
    }
    ?>
  </div>
</div>

  </div>
</div>
</header>
  <div style="text-align: center; margin: 30px 0;">
    <input type="text" id="auto-kereso" placeholder="Keresés autó név vagy márka szerint..." style="width: 60%; padding: 10px; font-size: 1rem; border-radius: 6px; border: 1px solid #ccc;">
</div>

  
  <div id="felhasznalo-info">
    <?php if(isset($_SESSION['felhasznalo'])): ?>
        <span>Bejelentkezve: <?php echo htmlspecialchars($_SESSION['felhasznalo']); ?></span>
        <a href="kijelentkezes.php" title="Kijelentkezés">
            <img src="kepek/logout_icon.png" alt="Kijelentkezés" style="width:25px; vertical-align:middle;">
        </a>
    <?php else: ?>
        <a href="#" id="belepes-gomb">Bejelentkezés</a> |
        <a href="#" id="regisztracio-gomb">Regisztráció</a>
    <?php endif; ?>
</div>


  
  <!-- Regisztráció és Belépés gombok (ha nincs bejelentkezve) -->
  <?php if(!isset($_SESSION['felhasznalo'])): ?>
  <?php endif; ?>
  
  <div id="overlay"></div>
  
  <!-- Regisztrációs űrlap -->
  <section id="regisztracio">
    <h2>Regisztráció</h2>
    <form id="regisztracio-urlap" action="regisztracio.php" method="POST">
      <label for="felhasznalonev">Felhasználónév:</label>
      <input type="text" id="felhasznalonev" name="felhasznalonev" required placeholder="Írd be a felhasználóneved">
      <label for="email">Email cím:</label>
      <input type="email" id="email" name="email" required placeholder="Írd be az email címed">
      <label for="jelszo">Jelszó:</label>
      <input type="password" id="jelszo" name="jelszo" required placeholder="Írd be a jelszavad">
      <label for="jelszo_megerositese">Jelszó megerősítése:</label>
      <input type="password" id="jelszo_megerositese" name="jelszo_megerositese" required placeholder="Erősítsd meg a jelszavad">
      <button type="submit">Regisztrálok</button>
    </form>
    <button class="bezaras-gomb" onclick="zarasFelulet()">Bezárás</button>
  </section>
  
  <!-- Belépési űrlap -->
  <section id="belepes">
    <h2>Belépés</h2>
    <form id="belepes-urlap" action="bejelentkezes.php" method="POST">
      <label for="felhasznalonev">Felhasználónév:</label>
      <input type="text" id="felhasznalonev" name="felhasznalonev" required placeholder="Írd be a felhasználóneved">
      <label for="jelszo">Jelszó:</label>
      <input type="password" id="jelszo" name="jelszo" required placeholder="Írd be a jelszavad">
      <button type="submit">Belépek</button>
    </form>
    <button class="bezaras-gomb" onclick="zarasFelulet()">Bezárás</button>
  </section>
  
  <main>
    <!-- Kezdőlap -->
    <section id="kezdolap">
      <h2>Üdvözlünk az InnovTrade Autókölcsönzőnél!</h2>
      <p>Találd meg a tökéletes autót bármilyen alkalomra. Foglalj egyszerűen és gyorsan!</p>
    </section>
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
  <input type="text" id="kereso-input" placeholder="Keresés név vagy leírás alapján..." style="flex: 1; padding: 8px; font-size: 1rem;">
  <select id="szuro-select" style="padding: 8px; font-size: 1rem;">
    <option value="">Rendezés nélkül</option>
    <option value="abc_az">ABC szerint (A → Z)</option>
    <option value="abc_za">ABC szerint (Z → A)</option>
    <option value="ar_nov">Ár szerint növekvő</option>
    <option value="ar_csok">Ár szerint csökkenő</option>
  </select>
  <select id="kategoriak-select" style="padding: 8px; font-size: 1rem;">
    <option value="osszes">Összes kategória</option>
    <option value="gazdasagos">Gazdaságos</option>
    <option value="csaladi">Családi</option>
    <option value="luxus">Luxus</option>
    <option value="sport">Sport</option>
  </select>
</div>

    <section id="autok">
  <h2>Bérelhető Autóink</h2>
    <?php
  // Autók lekérdezése kategóriánként
  $kategoriaLista = [
      'gazdasagos' => 'Gazdaságos Autók',
      'csaladi' => 'Családi Autók',
      'luxus' => 'Luxus Autók',
      'sport' => 'Sport Autók'
  ];

  foreach ($kategoriaLista as $kategoria => $cimke) {
      echo "<div id=\"$kategoria\" class=\"kategoria\" style=\"display:none;\">";
      echo "<h3>$cimke</h3>";
      echo "<div class=\"autok-halo\">";

      $sql = "SELECT * FROM autok WHERE kategoria = '" . $kapcsolat->real_escape_string($kategoria) . "'";
      $eredmeny = $kapcsolat->query($sql);

      if ($eredmeny && $eredmeny->num_rows > 0) {
          while ($auto = $eredmeny->fetch_assoc()) {
              $kep = htmlspecialchars($auto['kep']);
              $nev = htmlspecialchars($auto['nev']);
              $ar = number_format($auto['ar'], 0, ',', ' ');
              $leiras = htmlspecialchars($auto['leiras']);

              echo "
              <div class=\"auto $kategoria\">
                <img src=\"kepek/$kep\" alt=\"$nev\">
                <h3>$nev</h3>
                <p>Napi díj: $ar Ft</p>
                <p>$leiras</p>
                <button onclick=\"hozzaadKosarhoz('$nev', {$auto['ar']})\">Kosárba</button>
              </div>
              ";
          }
      } else {
          echo "<p>Nincs elérhető autó ebben a kategóriában.</p>";
      }

      echo "</div></div>";
  }
  ?>
</section>

    
    <!-- Foglalás űrlap -->
    <section id="foglalas">
      <h2>Foglalás</h2>
      <div id="kosar-tartalom">
        <label for="kuponkod">Kuponkód:</label>
<input type="text" id="kuponkod" name="kuponkod" placeholder="Pl.: INNOV10">

        <h3>Kosár tartalma</h3>
        <ul id="kosar-lista">
            <?php
            if (isset($_SESSION['kosar']) && count($_SESSION['kosar']) > 0) {
            foreach ($_SESSION['kosar'] as $auto) {
                echo "<li>" . htmlspecialchars($auto['nev']) . " - " . htmlspecialchars($auto['ar']) . " Ft/nap</li>";
            }
            } else {
                echo "<li>A kosár üres.</li>";
            }
            ?>
        </ul>
       


      </div>
      <form id="foglalas-urlap" action="foglalas.php" method="POST">
        <!-- Rejtett mező, amelybe a kosárból automatikusan beíródnak az autók nevei -->
        <input type="hidden" id="foglalas-autonev-hidden" name="autonev">
    
        <label for="foglalas-datum">Foglalás dátuma:</label>
        <input type="date" id="foglalas-datum" name="datum" required>
    
        <label for="foglalas-ido">Foglalás időtartalma (napok):</label>
        <input type="number" id="foglalas-ido" name="ido" min="1" required>
    
        <label for="foglalas-nev">Név:</label>
        <input type="text" id="foglalas-nev" name="nev" required value="<?php echo isset($_SESSION['felhasznalo']) ? htmlspecialchars($_SESSION['felhasznalo']) : ''; ?>" readonly>

        <label for="foglalas-email">Email:</label>
        <input type="email" id="foglalas-email" name="email" required value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" readonly>
    
        <label for="foglalas-email">Email:</label>
        <input type="email" id="foglalas-email" name="email" required value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
        <p>Fizetési mód:</p>
        <label><input type="radio" name="fizetes" value="Készpénz"> Készpénz</label>
        <label><input type="radio" name="fizetes" value="Átutalás"> Átutalás</label>

        <button type="submit">Foglalás küldése</button>
      </form>
    </section>
    <div id="vegosszeg-kijelzes" style="
    margin-top: 12px;
    padding: 12px 20px;
    background: linear-gradient(90deg, #dff0d8, #c8e5bc);
    border: 1px solid #b2d8a8;
    border-radius: 8px;
    font-weight: bold;
    font-size: 1.1rem;
    color: #2d572c;
    text-align: left;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    ">
    Végösszeg: 0 Ft
    </div>
    <?php
if (isset($_SESSION['szamla_elkeszult']) && isset($_SESSION['szamla_fajl']) && file_exists($_SESSION['szamla_fajl']))
{
    echo '<div style="text-align: center; margin-top: 20px;">
            <a href="szamla_megjelenites.php" class="stilusos-gomb">Számla megtekintése</a>
          </div>';
}
?>

    
    <!-- Vélemények -->
    <section id="velemenyek">
      <h2>Vásárlói Vélemények</h2>
      <div id="velemenyek-lista">
        <?php
          $sqlVelemenyek = "SELECT v.auto_nev, v.szoveg, f.felhasznalonev 
                             FROM velemenyek v 
                             JOIN felhasznalok f ON v.felhasznalo_id = f.id 
                             ORDER BY v.letrehozas_datum DESC";
          $eredmenyVelemenyek = $kapcsolat->query($sqlVelemenyek);
          if ($eredmenyVelemenyek && $eredmenyVelemenyek->num_rows > 0) {
            while ($sor = $eredmenyVelemenyek->fetch_assoc()) {
              echo "<p><strong>" . htmlspecialchars($sor['felhasznalonev']) . " (" . htmlspecialchars($sor['auto_nev']) . "):</strong> " . htmlspecialchars($sor['szoveg']) . "</p>";
            }
          } else {
            echo "<p>Még nincs vélemény. Légy te az első!</p>";
          }
        ?>
      </div>
      <form id="velemeny-urlap">
        <label for="velemeny-autonev">Autó neve:</label>
        <input type="text" id="velemeny-autonev" placeholder="Pl.: Fiat 500" required>
        <label for="velemeny-szoveg">Véleményed:</label>
        <textarea id="velemeny-szoveg" rows="4" placeholder="Írd meg a véleményed!" required></textarea>
        <button type="button" onclick="velemenyHozzaadasa()">Vélemény Beküldése</button>
      </form>
    </section>
    
    <!-- Kapcsolat -->
    <section id="kapcsolat">
      <h2>Kapcsolat</h2>
      <p>Ügyfélszolgálatunk 0-24 elérhető</p>
      <form id="kapcsolat-urlap">
        <label for="kapcsolat-nev">Név:</label>
        <input type="text" id="kapcsolat-nev" required value="<?php echo isset($_SESSION['felhasznalo']) ? htmlspecialchars($_SESSION['felhasznalo']) : ''; ?>">
        <label for="kapcsolat-email">Email:</label>
        <input type="email" id="kapcsolat-email" required value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
        <label for="kapcsolat-uzenet">Üzenet:</label>
        <textarea id="kapcsolat-uzenet" rows="5" required></textarea>
        <button type="button" onclick="kapcsolatHozzaadasa()">Üzenet Küldése</button>
      </form>
    </section>
  </main>
  
  <footer>
        <p2>&copy; 2024 InnovTrade Autókölcsönző. Minden jog fenntartva.</p2>
        <p>Fedezz fel minket Instagramon és Facebookon!</p>
        <a href="https://www.instagram.com/innov_trade" target="_blank">
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" width="40" height="40">
        
            <a href="https://www.facebook.com/yourusername" target="_blank">
          <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook" width="40" height="40">
        </a>
    </footer>
     
  <?php
      if (!isset($_SESSION['kosar'])) {
          $_SESSION['kosar'] = []; // Ha még nincs kosár, inicializáljuk
      }
    // Visszajelzés URL paraméterek alapján
    if (isset($_GET['regisztracio']) && $_GET['regisztracio'] == 1) {
      echo "<p style='text-align:center; color:green;'>Sikeres regisztráció!</p>";
    }
    if (isset($_GET['bejelentkezes']) && $_GET['bejelentkezes'] == 1) {
      echo "<p style='text-align:center; color:green;'>Sikeres bejelentkezés!</p>";
    }
    if (isset($_GET['kijelentkezes']) && $_GET['kijelentkezes'] == 1) {
      echo "<p style='text-align:center; color:green;'>Sikeres kijelentkezés!</p>";
    }
  ?>
</body>
</html>
