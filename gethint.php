<?php

include_once 'klasy/Baza.php';

$q = "";
$hints = [];
// get the q parameter from URL
if (isset($_REQUEST["q"])) {
    $q = $_REQUEST["q"];
}

// lookup all hints from array if $q is different from ""
if ($q !== "") {
    $db = new Baza("localhost", "root", "", "dictionary");
    $q = strtolower($q);
    $len = strlen($q);
    $sql = "SELECT DISTINCT Word FROM Definition WHERE Word LIKE '$q%';";
    $result = $db->query($sql);
    $rows = $db->toAssocArray($result);
    foreach ($rows as $row) {
        $hints[] = $row["Word"];
    }
    $hints = json_encode($hints);
    echo $hints;
} else {
    echo json_encode($hints);
}
?>