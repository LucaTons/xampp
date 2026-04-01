<?php
session_start();

require_once "db.php";

$error = "";

if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Controlla se esiste già
    $check = $connection->prepare("SELECT Username FROM Utenti WHERE Username = ? OR Email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Username o email già esistenti.";
    } else {
        $stmt = $connection->prepare("INSERT INTO Utenti (Username, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        }
        $stmt->close();
    }
    $check->close();
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
</head>
<body>
    <h2>Registrati</h2>
    
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Registrati</button>
    </form>
    
    <p>Hai già un account? <a href="login.php">Accedi</a></p>
</body>
</html>