<?php
    require_once 'db.php';

    $UsernameAdmin = 'admin';
    $PasswordAdmin = 'admin';
    if($_POST && isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if($username == $UsernameAdmin && $password == $PasswordAdmin)
        {
            session_start();
            $_SESSION['admin'] = true;
            header('Location: dashboard.php');
            exit();
        }
        else
        {
            $query = ('SELECT * FROM Abitanti WHERE CF = ? AND DataNascita = ?');
            $stmt = $connection->prepare($query);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
		    $result = $stmt->get_result();
            if($stmt->num_rows > 0)
            {
                session_start();
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit();
            }
        }
    }    
?>

<form action="login.php" method="POST">
	<label for="username">Username:</label>
	<input type="text" name="username" placeholder="Username">
	<br>
	<br>
	<label for="password">Password:</label>
	<input type="password" name="password" placeholder="Password">
	<br>
	<br>
	<button type="submit"">Accedi</button>
</form>