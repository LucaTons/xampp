<?php
session_start();

require_once "db.php";

$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) 
{
    $username = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_stmt = $connection->prepare("SELECT Username FROM Utenti WHERE Username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error_message = "Username già esistente. Scegline un altro.";
        $check_stmt->close();
    } else {
        $check_stmt->close();
        
        $stmt = $connection->prepare("INSERT INTO Utenti (Username, Password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $pass);
        
        if ($stmt->execute()) {
            $stmt->close();
            $connection->close();
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Errore durante la registrazione. Riprova.";
        }
        $stmt->close();
    }        
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - Chatrooms</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Crea Account</h2>
    
    <?php 
        if ($error_message)
        {
            echo htmlspecialchars($error_message);
        }
    ?>
    
    <?php 
        if ($success_message)
        {
            echo htmlspecialchars($success_message);
        }
    ?>
    
    <form action="register.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" >
        <br>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        </br>
        <br>
        <button type="submit" class="btn-primary">Registrati</button>
    </form>    
    <br><br><div class="auth-footer">Hai già un account? <a href="login.php">Accedi</a></div></br></br>
</body>
</html>