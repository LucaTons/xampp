<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esempio</title>
</head>
<body>
    <h1> Hello World! </h1>
    <?php
        $host = 'db'; 
        $dbname = 'root_db'; 
        $user = 'user';
        $password = 'user';
        $port = 3306;
        
        $connection = new mysqli($host, $user, $password, $dbname, $port);
        
        if ($connection->connect_error) 
        {
            die("Errore di connessione: " . $connection->connect_error);
        }
        
        echo "Connessione al database riuscita con mysqli!";
        
        $email = "luca@gmail.it";
        $password = "ciaociao";
        
        $query = "SELECT * FROM Users WHERE email = '$email' AND password = '$password'";
    
        echo "<br>";
        echo $query ;

        
        $result = $connection->query($query);

        //var_dump($result);

        echo "<br>";
        if($result->num_rows >0)
        {
            echo "Login effettuato correttamente!";
        }
        else
        {
            echo "Login non effettuato!";
        }
        
        $connection->close();
    ?>
</body>
</html>