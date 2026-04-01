<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: login.php');
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
$error_message = "";
$success_message = "";

// Creazione nuova chatroom
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_room') {
    $nome_stanza = trim($_POST['nome_stanza']);
    
    if (!empty($nome_stanza)) {
        $stmt = $connection->prepare("INSERT INTO Chatroom (Nome, CreataDa) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome_stanza, $current_user);
        
        if ($stmt->execute()) {
            $success_message = "Chatroom creata con successo!";
        } else {
            $error_message = "Errore: chatroom già esistente o errore durante la creazione.";
        }
        $stmt->close();
    } else {
        $error_message = "Il nome della chatroom non può essere vuoto.";
    }
}

// Invio invito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_invite') {
    $nome_stanza = $_POST['nome_stanza'];
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
                $stmt = $connection->prepare("INSERT INTO Inviti (NomeStanza, Mittente, Destinatario) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nome_stanza, $current_user, $destinatario);
                
                if ($stmt->execute()) {
                    $success_message = "Invito inviato con successo!";
                } else {
                    $error_message = "Errore durante l'invio dell'invito.";
                }
                $stmt->close();
            } else {
                $error_message = "Utente già invitato a questa chatroom.";
            }
            $check_invite->close();
        } else {
            $error_message = "Utente non trovato.";
        }
        $check_user->close();
    } else {
        $error_message = "Il nome utente non può essere vuoto.";
    }
}

// Recupera le chatroom disponibili (create dall'utente o a cui ha accesso tramite invito accettato)
$query = "
    SELECT DISTINCT c.Nome, c.CreataDa, c.DataCreazione 
    FROM Chatroom c
    LEFT JOIN Inviti i ON c.Nome = i.NomeStanza AND i.Destinatario = ? AND i.Accettato = 1
    WHERE c.CreataDa = ? OR i.ID IS NOT NULL
    ORDER BY c.DataCreazione DESC
";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $current_user, $current_user);
$stmt->execute();
$chatrooms = $stmt->get_result();
$stmt->close();

// Recupera gli inviti pendenti
$stmt = $connection->prepare("SELECT ID, NomeStanza, Mittente, DataInvito FROM Inviti WHERE Destinatario = ? AND Accettato = 0 ORDER BY DataInvito DESC");
$stmt->bind_param("s", $current_user);
$stmt->execute();
$inviti_pendenti = $stmt->get_result();
$stmt->close();

$connection->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Chatrooms</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard Chatrooms</h1>
            <div class="user-info">
                <span>Benvenuto, <strong><?php echo htmlspecialchars($current_user); ?></strong></span>
                <a href="logout.php" class="btn-secondary">Logout</a>
            </div>
        </div>
        
        <?php if ($error_message): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <div class="dashboard-content">
            <!-- Sezione inviti pendenti -->
            <?php if ($inviti_pendenti->num_rows > 0): ?>
                <div class="section invites-section">
                    <h2>Inviti Pendenti</h2>
                    <div class="invites-list">
                        <?php while ($invito = $inviti_pendenti->fetch_assoc()): ?>
                            <div class="invite-card">
                                <div class="invite-info">
                                    <strong><?php echo htmlspecialchars($invito['Mittente']); ?></strong> 
                                    ti ha invitato a 
                                    <strong><?php echo htmlspecialchars($invito['NomeStanza']); ?></strong>
                                    <span class="invite-date"><?php echo date('d/m/Y H:i', strtotime($invito['DataInvito'])); ?></span>
                                </div>
                                <div class="invite-actions">
                                    <form action="accept_invite.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="invite_id" value="<?php echo $invito['ID']; ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="btn-success">Accetta</button>
                                    </form>
                                    <form action="accept_invite.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="invite_id" value="<?php echo $invito['ID']; ?>">
                                        <input type="hidden" name="action" value="decline">
                                        <button type="submit" class="btn-danger">Rifiuta</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Sezione crea chatroom -->
            <div class="section create-room-section">
                <h2>Crea Nuova Chatroom</h2>
                <form action="dashboard.php" method="POST" class="create-room-form">
                    <input type="hidden" name="action" value="create_room">
                    <div class="form-group-inline">
                        <input type="text" name="nome_stanza" placeholder="Nome della chatroom" required>
                        <button type="submit" class="btn-primary">Crea</button>
                    </div>
                </form>
            </div>
            
            <!-- Sezione chatroom disponibili -->
            <div class="section chatrooms-section">
                <h2>Le Tue Chatroom</h2>
                
                <?php if ($chatrooms->num_rows > 0): ?>
                    <div class="chatrooms-grid">
                        <?php while ($room = $chatrooms->fetch_assoc()): ?>
                            <div class="chatroom-card">
                                <div class="chatroom-header">
                                    <h3><?php echo htmlspecialchars($room['Nome']); ?></h3>
                                    <span class="chatroom-creator">
                                        di <?php echo htmlspecialchars($room['CreataDa']); ?>
                                    </span>
                                </div>
                                <div class="chatroom-date">
                                    Creata il <?php echo date('d/m/Y', strtotime($room['DataCreazione'])); ?>
                                </div>
                                <div class="chatroom-actions">
                                    <a href="chat.php?room=<?php echo urlencode($room['Nome']); ?>" class="btn-primary">
                                        Entra
                                    </a>
                                    <?php if ($room['CreataDa'] === $current_user): ?>
                                        <button type="button" class="btn-secondary" onclick="showInviteForm('<?php echo htmlspecialchars($room['Nome'], ENT_QUOTES); ?>')">
                                            Invita
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="no-data">Nessuna chatroom disponibile. Creane una per iniziare!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal per invitare utenti -->
    <div id="inviteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeInviteForm()">&times;</span>
            <h2>Invita Utente</h2>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="send_invite">
                <input type="hidden" name="nome_stanza" id="invite_room_name">
                <div class="form-group">
                    <label for="destinatario">Username destinatario</label>
                    <input type="text" name="destinatario" id="destinatario" required>
                </div>
                <button type="submit" class="btn-primary">Invia Invito</button>
            </form>
        </div>
    </div>
    
    <script src="main.js"></script>
</body>
</html>