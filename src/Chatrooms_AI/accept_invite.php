<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['invite_id']) || !isset($_POST['action'])) {
    header('Location: dashboard.php');
    exit();
}

$host = 'db';
$dbname = 'Chatrooms';
$user = 'user';
$password = 'user';
$port = 3306;

$connection = new mysqli($host, $user, $password, $dbname, $port);

if ($connection->connect_error) {
    die("Errore di connessione: " . $connection->connect_error);
}

$current_user = $_SESSION['username'];
$invite_id = intval($_POST['invite_id']);
$action = $_POST['action'];

// Verifica che l'invito appartenga all'utente corrente
$stmt = $connection->prepare("SELECT ID, NomeStanza FROM Inviti WHERE ID = ? AND Destinatario = ? AND Accettato = 0");
$stmt->bind_param("is", $invite_id, $current_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $invite = $result->fetch_assoc();
    
    if ($action === 'accept') {
        // Accetta l'invito
        $update_stmt = $connection->prepare("UPDATE Inviti SET Accettato = 1 WHERE ID = ?");
        $update_stmt->bind_param("i", $invite_id);
        $update_stmt->execute();
        $update_stmt->close();
    } elseif ($action === 'decline') {
        // Rifiuta l'invito (elimina dalla tabella)
        $delete_stmt = $connection->prepare("DELETE FROM Inviti WHERE ID = ?");
        $delete_stmt->bind_param("i", $invite_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }
}

$stmt->close();
$connection->close();

header('Location: dashboard.php');
exit();
?>