<?php
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("DROP DATABASE IF EXISTS db_nasaktion");
    $sql = file_get_contents('database.sql');
    $pdo->exec($sql);
    echo "DB SUCCESSFULLY MIGRATED";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
