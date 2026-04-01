<?php
    session_start();
    require_once "db.php";

    $query = "SELECT * FROM Chatroom";
    $result = $connection->query($query);
    $connection->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="dashboard-card">
        <div class="card-header">
            <h2>Chatrooms</h2>
            <a href="logout.php" class="logout-link">Esci</a>
        </div>
        <div class="room-list">
            <?php
                if ($result && $result->num_rows > 0) 
                {
                    while ($row = $result->fetch_assoc()) 
                    {
                        echo '<a href="chat.php?room_id=' . htmlspecialchars($row['ID']) . '">' . htmlspecialchars($row['Nome']) . '</a>';
                    }
                }
            ?>
        </div>
    </div>

</body>
</html>