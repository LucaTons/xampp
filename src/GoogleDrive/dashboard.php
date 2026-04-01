<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: login.php');
    exit();
}

require_once "db.php";

$username = $_SESSION['username'];
$error = "";
$success = "";

// Upload file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        
        $file_name = $_FILES['file_upload']['name'];
        $file_tmp = $_FILES['file_upload']['tmp_name'];
        
        $contenuto = file_get_contents($file_tmp);
        $contenuto = base64_encode($contenuto);
        
        $stmt = $connection->prepare("INSERT INTO File (Nome, Contenuto, Username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $file_name, $contenuto, $username);
        
        if ($stmt->execute()) {
            $success = "File caricato!";
        } else {
            $error = "Errore caricamento.";
        }
        $stmt->close();
    }
}

// Elimina file
if (isset($_GET['delete'])) {
    $file_id = intval($_GET['delete']);
    
    $stmt = $connection->prepare("DELETE FROM File WHERE ID = ? AND Username = ?");
    $stmt->bind_param("is", $file_id, $username);
    $stmt->execute();
    $stmt->close();
    
    $success = "File eliminato!";
}

// Rinomina file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rename'])) {
    $file_id = intval($_POST['rename_id']);
    $new_name = trim($_POST['new_name']);

    if (!empty($new_name)) {
        $stmt = $connection->prepare("UPDATE File SET Nome = ? WHERE ID = ? AND Username = ?");
        $stmt->bind_param("sis", $new_name, $file_id, $username);

        if ($stmt->execute()) {
            $success = "File rinominato!";
        } else {
            $error = "Errore durante la rinominazione.";
        }
        $stmt->close();
    } else {
        $error = "Il nome non può essere vuoto.";
    }
}

// Prendi i file
$stmt = $connection->prepare("SELECT ID, Nome, Data FROM File WHERE Username = ? ORDER BY Data DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Ciao, <?php echo $username; ?>!</h1>
   
    <h2>Carica un file</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file_upload" required>
        <button type="submit" name="upload">Carica</button>
    </form>
    
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <hr>
    
    <h2>I miei file</h2>
    <?php if (count($files) > 0): ?>
        <table border="1">
            <tr>
                <th>Nome</th>
                <th>Data</th>
                <th>Azioni</th>
            </tr>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo $file['Nome']; ?></td>
                    <td><?php echo $file['Data']; ?></td>
                    <td>
                        <a href="download.php?id=<?php echo $file['ID']; ?>">Scarica</a> |
                        <a href="download.php?id=<?php echo $file['ID']; ?>&action=view" target="_blank">Apri</a> |
                        <a href="#" onclick="document.getElementById('rename-<?php echo $file['ID']; ?>').style.display='inline'; return false;">Rinomina</a>
                        <form id="rename-<?php echo $file['ID']; ?>" method="POST" style="display:none; margin-top:4px;">
                            <input type="hidden" name="rename_id" value="<?php echo $file['ID']; ?>">
                            <input type="text" name="new_name" value="<?php echo htmlspecialchars($file['Nome']); ?>" required>
                            <button type="submit" name="rename">Salva</button>
                            <button type="button" onclick="document.getElementById('rename-<?php echo $file['ID']; ?>').style.display='none';">Annulla</button>
                        </form> |
                        <a href="?delete=<?php echo $file['ID']; ?>" onclick="return confirm('Sicuro?');">Elimina</a>                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nessun file caricato.</p>
    <?php endif; ?>
    
    <hr>
    <a href="logout.php">Esci</a>
</body>
</html>