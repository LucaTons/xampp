<?php

session_start();

if(isset($_SESSION['auth']) && $_SESSION['auth'])
{
    //inserimento nome giocattolo e nome elfo
    echo '<section>';
    echo '<form name="Giocattoli_POST" method="POST" action="prodGiocattoli.php">';

    echo '<label for="nome_giocattolo"> Nome Giocattolo </label>';
    echo '<input type=text id="nomeGiocattolo" name="nomeGiocattolo">';
    echo '<br>';

    echo '<label for="nome_elfo"> Nome Elfo </label>';
    echo '<input type=text id="nomeElfo" name="nomeElfo">';
    echo '<br>';

    echo '<button type=submit>Inserisci</button>';

    echo '</form>';
    echo '</section>';

    //visualizzare tabella giocattoli
    echo '<section>';
    echo '<a href="prodElfi.php"> Visualizza i giocattoli degli elfi</a>';
    echo'<br>';
    echo '</section>';

}

?>