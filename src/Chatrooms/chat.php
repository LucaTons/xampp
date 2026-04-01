<?php
session_start();
require_once "db.php";
// Array vuoto che conterrà i messaggi recuperati dal DB
$messages = [];
// Nome della stanza, inizialmente vuoto
$nomeStanza = "";
// Legge il parametro ?room_id=X dall'URL. Se non esiste, vale null
$room_id = $_GET['room_id'] ?? null;

// Procede solo se l'utente è loggato (ha username in sessione) E se room_id è presente nell'URL
if (isset($_SESSION['username']) && $room_id) {
    // Query per ottenere il nome della stanza dal suo ID
    $queryNome = "SELECT Nome FROM Chatroom WHERE ID = $room_id";
    $result = $connection->query($queryNome);
    // fetch_assoc() restituisce la riga come array associativo (es. ['Nome' => 'Tecnologia'])
    $row2 = $result->fetch_assoc();
    // Salva il nome della stanza in una variabile
    $nomeStanza = $row2['Nome'];
    // Controlla se la pagina è stata chiamata con un POST (invio del form)
    // e che il campo 'messaggio' esista e non sia vuoto
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['messaggio']) && $_POST['messaggio'] !== '') {

        // real_escape_string() protegge da SQL injection escapando i caratteri speciali
        $msg  = $connection->real_escape_string($_POST['messaggio']);
        $user = $connection->real_escape_string($_SESSION['username']);
        // Inserisce il nuovo messaggio nel database
        $connection->query("INSERT INTO Messaggi (`Testo`, `NomeStanza`, `User`) VALUES ('$msg', '$nomeStanza', '$user')");
        // Redirect sulla stessa pagina dopo l'invio, per evitare che ricaricare
        // la pagina reinvii il form (pattern Post/Redirect/Get)
        header("Location: chat.php?room_id=$room_id");
        exit(); // Ferma l'esecuzione del resto del codice
    }
    // Query per prendere tutti i messaggi della stanza, ordinati per data crescente
    $query = "SELECT * FROM Messaggi WHERE NomeStanza = '$nomeStanza' ORDER BY Giorno ASC";
    $res = $connection->query($query);
    // Se ci sono risultati, li scorre uno per uno
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            // Aggiunge ogni messaggio all'array $messages
            $messages[] = $row;
        }
    }
}
// Chiude la connessione al database, non serve più
$connection->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- htmlspecialchars() converte caratteri come < > & in entità HTML
         per evitare XSS (es. se il nome stanza contenesse codice HTML malevolo) -->
    <title>Chat – <?php echo htmlspecialchars($nomeStanza); ?></title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="chat-wrapper">
        <!-- Mostra il nome della stanza come titolo della pagina -->
        <div class="chat-header">
            <?php echo htmlspecialchars($nomeStanza); ?>
        </div>
        <div class="chat-messages">
            <!-- Se l'array $messages non è vuoto, mostra i messaggi -->
            <?php if (!empty($messages)): ?>
                <!-- Cicla su ogni messaggio e lo stampa -->
                <?php foreach ($messages as $m): ?>
                    <div class="message">
                        <!-- Nome utente che ha scritto il messaggio -->
                        <span class="username"><?php echo htmlspecialchars($m['User']); ?></span>
                        <!-- Testo del messaggio -->
                        <?php echo htmlspecialchars($m['Testo']); ?>
                        <!-- Data/ora del messaggio -->
                        <span class="timestamp"><?php echo htmlspecialchars($m['Giorno']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Messaggio mostrato se non ci sono ancora messaggi nella stanza -->
                <div class="message" style="color:#aaa;">Nessun messaggio ancora. Inizia tu!</div>
            <?php endif; ?>

        </div>
        <!-- Form per inviare un messaggio. action="" = invia alla stessa pagina, method POST -->
        <form class="chat-form" action="" method="POST">
            <input type="text" name="messaggio" placeholder="Scrivi un messaggio...">
            <button type="submit">Invia</button>
        </form>
        <!-- Link per tornare alla lista delle stanze -->
        <div class="back-link">
            <a href="dashboard.php">← Torna alla dashboard</a>
        </div>

    </div>

</body>
</html>