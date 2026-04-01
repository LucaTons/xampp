<?php
    session_start();

    if(isset($_SESSION['auth']) && $_SESSION['auth'] == true && $_POST && isset($_POST['nomeGiocattolo']) && isset($_POST['nomeElfo']))
    {
        session_unset('auth');
    }
    
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

    $nomeGiocattolo = $_POST['nomeGiocattolo'];
    $nomeElfo = $_POST['nomeElfo'];

    $query = "INSERT INTO giocattoli (nomeGiocattolo, nomeElfo) VALUES ('". $nomeGiocattolo . "' . '" $nomeElfo . "'))";

    $result = $connection->query($query);

    if($result->affected_rows() > 0)
    {
        echo "Giocatollo inserito correttamente";
    }
    
    $connection->close();
    echo '<br>';
    echo '<a href="dashboard.php"> Clicca qui se vuoi tornare alla dashboard </a>';
?>