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

// Per controllare che venga fatto il login tramite POST
if ($_POST && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password_input = $_POST['password']; 
    
    // Query per prendere solo l'utente specifico
    $stmt = $connection->prepare("SELECT * FROM Utenti WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();    
    
    if ($result && $result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        $hashed_password = $row['Password'];
        
        // Verifica la password
        if ($hashed_password !== null && password_verify($password_input, $hashed_password)) {
            $_SESSION['auth'] = true;
            $_SESSION['username'] = $username;
            $stmt->close();
            $connection->close();
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Username o password non corretti.";
        }
    } else {
        $error_message = "Username o password non corretti.";
    }
    $stmt->close();
    $connection->close(); 
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chatrooms</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Accedi</h2>
            
            <?php if ($error_message): ?>
                <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Accedi</button>
            </form>
            
            <div class="auth-link">
                Non hai un account? <a href="register.php">Registrati</a>
            </div>
        </div>
    </div>
</body>
</html>