<?php
    //per controllare che venga fatto il login tramite POST
    if($_POST && isset($_POST['username']) && isset($_POST['password'])) 
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if($username == 'santa' && $password == 'rudolf')
        {
            session_start();
            $_SESSION['auth'] = true;
            header('Location: dashboard.php'); //location è il descrittore
        }
        else
        {
            header('Location: login.html');
        }
    }
?>