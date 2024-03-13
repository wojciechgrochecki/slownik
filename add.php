<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href=
              "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
        <link rel="stylesheet" href="styling/addStyle.css">
        <title>Dictionary</title>
    </head>
    <body>
        <?php
        include_once 'klasy/Baza.php';

        $db = new Baza("localhost", "root", "", "dictionary");
        session_start();
        $sessionId = session_id();
        $sql = "SELECT DISTINCT userId FROM logged_in_users WHERE sessionId = '$sessionId';";
        $result = $db->query($sql);
        if ($result->num_rows == 0) {
            $result->free_result();
            header("location:login.php");
        }

        $readonly = "";
        $submitValue = "submit";
        $submitText = "Dodaj definicję!";
        $word = "";
        $definitionText = "";
        $formDefinitionId = "";
        $backRef = "main.php";

        if (isset($_REQUEST['defId']) && $_REQUEST['defId'] != "") {
            $defId = htmlspecialchars(trim($_REQUEST['defId']));
            $userId = $db->toAssocArray($result)[0]['userId'];
            $sql = "SELECT * FROM definition WHERE DefinitionID = '$defId'; ";
            $result = $db->query($sql);
            $definition = $db->toAssocArray($result)[0];

            if ($userId != $definition["UserID"]) {
                echo "UserId=$userId";
                echo "</br>$definiton[$userID]";
            } else {
                $readonly = "readonly";
                $submitValue = "edit";
                $submitText = "Zmień definicję!";
                $word = $definition["Word"];
                $definitionText = $definition["WordDefinition"];
                $formDefinitionId = '<input type="hidden" name="updateId" value="' . $definition["DefinitionID"] . '" />';
                $backRef = "profile.php";
            }
        }
        echo '
        <main>
            <div class="content">
                <div class="close-icon">
                    <a href="' . $backRef . '"><i class="fas fa-circle-arrow-left fa-lg"></i></a>
                </div>
                <div class="add-form-wrap">
                    <form class="add-form" action="add.php" method="post" autocomplete="off">
                        <input type="text" value="' . $word . '" name="word" id="form-word" placeholder="Słowo" ' . $readonly . ' maxlength="50" required>
                        <textarea name="definition" placeholder="Wpisz definicję słowa" rows="10" cols="50" minlength="10" required>' . $definitionText . '</textarea>
                         ' . $formDefinitionId . '
                        <button type="submit" value="' . $submitValue . '" name="submit">' . $submitText . '</button>
                    </form>
                </div>
            </div>
        </main>';

        if ((isset($_REQUEST['word']) && $_REQUEST['word'] != "") && (isset($_REQUEST['definition']) && $_REQUEST['definition'] != "")) {
            $word = htmlspecialchars(trim($_REQUEST['word']));
            $definition = htmlspecialchars(trim($_REQUEST['definition']));
            $obj = $result->fetch_assoc();
            $id = $obj['userId'];

            if ($_REQUEST['submit'] == "edit") {
                $idToUpdate = $_REQUEST['updateId'];
                $sql = "UPDATE definition SET WordDefinition = '$definition' WHERE DefinitionID = '$idToUpdate';";
            } else {
                $sql = "INSERT INTO definition VALUES(NULL, '$word', '$definition', $id);";
            }
            $db->insert($sql);
        }
        ?>
    </body>
</html>
