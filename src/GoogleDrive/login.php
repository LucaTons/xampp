<?php
session_start();

require_once "db.php";

$error = "";

if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $connection->prepare("SELECT Password FROM Utenti WHERE Username = ? AND Email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['Password'])) {
            $_SESSION['auth'] = true;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        }
    }
    
    $error = "Username, email o password sbagliati.";
    $stmt->close();
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    
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
        
        <button type="submit">Entra</button>
    </form>
    
    <p>Non hai un account? <a href="register.php">Registrati</a></p>
</body>
</html>