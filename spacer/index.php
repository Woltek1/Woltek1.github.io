<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "szkola";

# do kazdewgo linku musi byc dopisane index.php?sala=(nrsali)
$sala = isset($_GET['sala']) ? intval($_GET['sala']) : 0; 

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}

// pobieranie przedmiotow
$zapytanie_sala = "SELECT GROUP_CONCAT(DISTINCT przedmiot SEPARATOR ', ') as przedmioty FROM nauczyciele WHERE sala = $sala";
$wynik_sala = mysqli_query($conn, $zapytanie_sala);

if ($wynik_sala && mysqli_num_rows($wynik_sala) > 0) {
    $row_sala = mysqli_fetch_assoc($wynik_sala);
    $przedmioty = $row_sala['przedmioty'] ?? "Brak danych";
} else {
    $przedmioty = "Brak danych";
}

// pobieranie nauczycieli
$zapytanie_nauczyciele = "SELECT DISTINCT CONCAT(imie, ' ', nazwisko) AS imie_nazwisko, opis FROM nauczyciele WHERE sala = $sala";
$wynik_nauczyciele = mysqli_query($conn, $zapytanie_nauczyciele);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala <?php echo $sala; ?></title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    
    <header>
        <h1>SALA <?php echo $sala; ?></h1>
        <h2><?php echo $przedmioty; ?></h2>
    </header>

    <?php
    #zdjecie sali jezeli puste to da ci ten takiego blanka
        if (file_exists("zdjecia sal/$sala.jpg")) {
            echo '<img class="classroom-image" src="zdjecia sal/' . $sala . '.jpg" alt="Sala ' . $sala . '">';
        } else {
            echo '<div class="classroom-image" style="background-color: #ccac;"></div>';
        }
    ?>



    <div class="teacher-list">
        <h2>Lista nauczycieli</h2>

        <?php
        if ($wynik_nauczyciele && mysqli_num_rows($wynik_nauczyciele) > 0) {
            while ($row = mysqli_fetch_assoc($wynik_nauczyciele)) {
                echo '<div class="teacher">
                        <div class="teacher-info">
                            <h3>' . $row["imie_nazwisko"] . '</h3>
                            <p>' . $row["opis"] . '</p>
                        </div>
                        <img class="teacher-image" src="avatar.jpg" alt="avatar">
                      </div>';
            }
        } else {
            echo "<br><br><p>Brak nauczycieli przypisanych do tej sali.</p><br><br>";
        }
        ?>

    </div>

    <footer>
        <a href="https://zsl.waw.pl/">
            Zespół Szkół Łączności w Warszawie
        </a>
    </footer>

    <?php mysqli_close($conn); ?>
</body>
</html>
