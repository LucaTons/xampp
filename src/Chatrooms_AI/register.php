<?php
session_start();

$host = 'db';
$dbname = 'Chatrooms';
$user = 'user';
$password = 'user';
$port = 3306;

$connection = new mysqli($host, $user, $password, $dbname, $port);

if ($connection->connect_error) {
    die("Errore di connessione: " . $connection->connect_error);
}

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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Crea Account</h2>
            
            <?php if ($error_message): ?>
                <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Registrati</button>
            </form>
            
            <div class="auth-link">
                Hai già un account? <a href="login.php">Accedi</a>
            </div>
        </div>
    </div>
</body>
</html>