<?php

use BotMan\BotMan\Interfaces\UserInterface;

function InsertUserIfNecessary(UserInterface $user) {
    global $conn;
    $id       = $user->getId();
    $username = $user->getUsername();
    $nickname = $user->getFirstName();

    $queryCheckUser = "SELECT * FROM users_bot_telegram WHERE id = $id LIMIT 1";
    $resultRow = $conn->query($queryCheckUser)->fetch_row();
    
    if ($resultRow == null) {
        $queryInsert = "INSERT INTO users_bot_telegram VALUES ('$id', '$username', '$nickname', current_timestamp(), current_timestamp())";
        $conn->query($queryInsert);
        return;
    }

    // Update, kolom 1: username, kolom 2: nickname
    if ($username != $resultRow[1] || $nickname != $resultRow[2]) {
        $queryUpdate = "UPDATE users_bot_telegram 
                        SET username = '$username', nickname = '$nickname', updated_at = current_timestamp()
                        WHERE id = $id";
        $conn->query($queryUpdate);
    }
}

?>