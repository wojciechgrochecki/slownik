<?php

include_once 'klasy/Baza.php';
$db = new Baza("localhost", "root", "", "dictionary");
session_start();
$sessionId = session_id();
$sql = "SELECT DISTINCT userId FROM logged_in_users WHERE sessionId = '$sessionId';";
$result = $db->query($sql);
if ($result->num_rows == 0) {
    $result->free_result();
    echo json_encode(0);
    exit();
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $definitionID = $data["idToDelete"];
    $sql = "DELETE FROM definition WHERE DefinitionID = '$definitionID';";
    $db->insert($sql);

    echo json_encode(1);
}
