<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['room'])) {
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
$nome_stanza = $_GET['room'];
$error_message = "";
$success_message = "";

// Verifica che l'utente abbia accesso alla chatroom
$access_query = "
    SELECT c.Nome, c.CreataDa 
    FROM Chatroom c
    LEFT JOIN Inviti i ON c.Nome = i.NomeStanza AND i.Destinatario = ? AND i.Accettato = 1
    WHERE c.Nome = ? AND (c.CreataDa = ? OR i.ID IS NOT NULL)
";
$stmt = $connection->prepare($access_query);
$stmt->bind_param("sss", $current_user, $nome_stanza, $current_user);
$stmt->execute();
$access_result = $stmt->get_result();

if ($access_result->num_rows === 0) {
    $stmt->close();
    $connection->close();
    header('Location: dashboard.php');
    exit();
}

$chatroom_data = $access_result->fetch_assoc();
$stmt->close();

// Verifica se l'utente può invitare (creatore o membro)
$can_invite = ($chatroom_data['CreataDa'] === $current_user);
if (!$can_invite) {
    // Verifica se è un membro tramite invito accettato
    $member_check = $connection->prepare("SELECT ID FROM Inviti WHERE NomeStanza = ? AND Destinatario = ? AND Accettato = 1");
    $member_check->bind_param("ss", $nome_stanza, $current_user);
    $member_check->execute();
    $member_result = $member_check->get_result();
    $can_invite = ($member_result->num_rows > 0);
    $member_check->close();
}

// Invio messaggio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $testo = trim($_POST['testo']);
    
    if (!empty($testo)) {
        $stmt = $connection->prepare("INSERT INTO Messaggi (Testo, NomeStanza, User) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $testo, $nome_stanza, $current_user);
        
        if ($stmt->execute()) {
            $success_message = "Messaggio inviato!";
        } else {
            $error_message = "Errore durante l'invio del messaggio.";
        }
        $stmt->close();
    }
}

// Invio invito dalla chat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_invite') {
    $destinatario = trim($_POST['destinatario']);
    
    if (!empty($destinatario)) {
        // Verifica che l'utente esista
        $check_user = $connection->prepare("SELECT Username FROM Utenti WHERE Username = ?");
        $check_user->bind_param("s", $destinatario);
        $check_user->execute();
        $user_result = $check_user->get_result();
        
        if ($user_result->num_rows > 0) {
            // Verifica che l'utente non sia già stato invitato
            $check_invite = $connection->prepare("SELECT ID FROM Inviti WHERE NomeStanza = ? AND Destinatario = ?");
            $check_invite->bind_param("ss", $nome_stanza, $destinatario);
            $check_invite->execute();
            $invite_result = $check_invite->get_result();
            
            if ($invite_result->num_rows === 0) {
                // Verifica che non sia il creatore della stanza
                if ($destinatario !== $chatroom_data['CreataDa']) {
                    $stmt = $connection->prepare("INSERT INTO Inviti (NomeStanza, Mittente, Destinatario) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $nome_stanza, $current_user, $destinatario);
                    
                    if ($stmt->execute()) {
                        $success_message = "Invito inviato con successo!";
                    } else {
                        $error_message = "Errore durante l'invio dell'invito.";
                    }
                    $stmt->close();
                } else {
                    $error_message = "Non puoi invitare il creatore della stanza.";
                }
            } else {
                $error_message = "Utente già invitato a questa chatroom.";
            }
            $check_invite->close();
        } else {
            $error_message = "Utente non trovato.";
        }
        $check_user->close();
    }
}

// Recupera i messaggi della chatroom
$stmt = $connection->prepare("SELECT Testo, Giorno, User FROM Messaggi WHERE NomeStanza = ? ORDER BY ID ASC");
$stmt->bind_param("s", $nome_stanza);
$stmt->execute();
$messaggi = $stmt->get_result();
$stmt->close();

$connection->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nome_stanza); ?> - Chatrooms</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="chat-title">
                <a href="dashboard.php" class="back-link">← Dashboard</a>
                <h1><?php echo htmlspecialchars($nome_stanza); ?></h1>
                <span class="chat-subtitle">Creata da <?php echo htmlspecialchars($chatroom_data['CreataDa']); ?></span>
            </div>
            <div class="chat-actions">
                <?php if ($can_invite): ?>
                    <button type="button" class="btn-secondary" onclick="showInviteForm()">
                        Invita Utente
                    </button>
                <?php endif; ?>
                <a href="logout.php" class="btn-secondary">Logout</a>
            </div>
        </div>
        
        <?php if ($error_message): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <div class="chat-content">
            <div class="messages-container" id="messagesContainer">
                <?php if ($messaggi->num_rows > 0): ?>
                    <?php while ($msg = $messaggi->fetch_assoc()): ?>
                        <div class="message-bubble <?php echo $msg['User'] === $current_user ? 'own-message' : 'other-message'; ?>">
                            <div class="message-header">
                                <span class="message-user"><?php echo htmlspecialchars($msg['User']); ?></span>
                                <span class="message-date"><?php echo date('d/m/Y', strtotime($msg['Giorno'])); ?></span>
                            </div>
                            <div class="message-text">
                                <?php echo nl2br(htmlspecialchars($msg['Testo'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-messages">Nessun messaggio ancora. Inizia la conversazione!</p>
                <?php endif; ?>
            </div>
            
            <div class="message-input-container">
                <form action="chat.php?room=<?php echo urlencode($nome_stanza); ?>" method="POST" class="message-form">
                    <input type="hidden" name="action" value="send_message">
                    <textarea name="testo" id="messageInput" placeholder="Scrivi un messaggio..." required rows="2"></textarea>
                    <button type="submit" class="btn-primary">Invia</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal per invitare utenti -->
    <?php if ($can_invite): ?>
    <div id="inviteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeInviteForm()">&times;</span>
            <h2>Invita Utente a <?php echo htmlspecialchars($nome_stanza); ?></h2>
            <form action="chat.php?room=<?php echo urlencode($nome_stanza); ?>" method="POST">
                <input type="hidden" name="action" value="send_invite">
                <div class="form-group">
                    <label for="destinatario">Username destinatario</label>
                    <input type="text" name="destinatario" id="destinatario" required>
                </div>
                <button type="submit" class="btn-primary">Invia Invito</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="main.js"></script>
    <script>
        // Auto-scroll ai messaggi più recenti
        window.addEventListener('load', function() {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
        
        // Focus automatico sul campo messaggio
        document.getElementById('messageInput')?.focus();
    </script>
</body>
</html>