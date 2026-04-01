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

    $username = htmlspecialchars($_POST['username']);
    $pass =  htmlspecialchars($_POST['password']);

    $stmt = $connection->prepare("SELECT * FROM UserLogin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) 
    {        
        if($username == "admin" && $pass == "admin")
        {

            echo "Login effettuato correttamente! <br><br>";          
            $query = "SELECT * FROM UserLogin";
            $result = $connection->query($query);
            var_dump($result);
            echo "La tabella username ha le seguenti righe $result->num_rows:<br>";
            echo "<table border=1>";
            echo "<tr><th>Username</th><th>Password</th></tr>";
            while ($row = $result->fetch_assoc()) 
            {
                //var_dump($row);
                echo "<tr><td>" . $row['username'] . "</td><td>" . $row['password'] . "</td></tr>";
                //echo "Username: " . $row['username'] . "<br>";
                //echo "Password: " . $row['password'] . "<br>";
            }
            echo "</table>";
        }
    } 
    else 
    {
        echo "Credenziali errate!";
        echo '<a href="signup.html">Sign Up</a>';
    }

    $connection->close();
    
?>