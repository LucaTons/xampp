<?php
session_start();

if(isset($_SESSION['auth']) && $_SESSION['auth'])
{
    $host = 'db';
    $dbname = 'babbonatale'; 
    $user = 'user';
    $password = 'user';
    $port = 3306;

    $connection = new mysqli($host, $user, $password, $dbname, $port);

    if ($connection->connect_error) 
    {
        die("Errore di connessione: " . $connection->connect_error);
    }

    //stampo tutta la tabella
    //la query raggruppa per nomeGiocattolo e conta il numero di righe corrispondenti
    $query = "SELECT nomeGiocattolo, COUNT(*) AS numeroUnità FROM giocattoli GROUP BY nomeGiocattolo";

    $result = $connection->query($query);

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            echo ($row['nomeGiocattolo']) . ":";
            echo ($row['numeroUnità']) . '<br>';
        }
    }

    $connection->close();
    echo '<br>';
    echo '<a href="dashboard.php"> Clicca qui se vuoi tornare alla dashboard </a>';
}
?>