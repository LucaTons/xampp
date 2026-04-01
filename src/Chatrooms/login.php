<?php
session_start();

require_once "db.php";

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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Accedi</h2>
    
    <?php if ($error_message)
        echo htmlspecialchars($error_message); 
    ?>
    
    <form action="login.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
        <br>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        </br>
        <br>
        <button type="submit"">Accedi</button>
        </br>
    </form>
    <br><br><div class="auth-footer">Non hai un account? <a href="register.php">Registrati</a></div>
    
</body>
</html>