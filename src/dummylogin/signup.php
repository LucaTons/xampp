<?php
    $host = 'db';
    $dbname = 'root_db';
    $user = 'user';
    $password = 'user';
    $port = 3306;

    $connection = new mysqli($host, $user, $password, $dbname, $port);

    if ($connection->connect_error) {
        die("Errore di connessione: " . $connection->connect_error);
    }

    $username = $_POST['username'];
    $pass = $_POST['password'];

    $username = $connection->real_escape_string($username);
    $pass = $connection->real_escape_string($pass);

    $query = "INSERT INTO UserLogin (username, password) VALUES ('$username', '$pass')";

    if ($connection->query($query) === TRUE) 
    {
        header("Location: login.html");
        exit();
    } else {
        echo "Errore durante la registrazione: " . $connection->error;
    }

    $connection->close();
?>