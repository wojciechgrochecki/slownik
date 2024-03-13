<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
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
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styling/mainStyle.css">
        <link rel="stylesheet" href=
              "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
        <script defer src="scripts/mainJavascript.js"></script>
        <title>Dictionary</title>
    </head>
    <body>
        <div class="topnav">
            <div class="search-container">
                <form action="javascript:void(0);" autocomplete="off">
                    <div class="input-wrap">
                        <div class="search-bar-wrap">
                            <label id="search-bar-label" for="search-bar"><i class="fas fa-magnifying-glass"></i></label>
                            <input id="search-bar" type="text" placeholder="Search.." name="search">
                        </div>
                        <div class="search-results" id="search-results" >
                        </div>
                    </div>
                </form>
                <a href="add.php" id="add-definiton-button"><i class='fas fa-circle-plus fa-lg'></i></a>
                <a href="profile.php" id="user-account-button"><i class='fas fa-user-circle fa-lg'></i></a>
                <a href="login.php?akcja=wyloguj" id="sign-out-button"<i class='fas fa-door-closed'></i></a>
            </div>
        </div>
        <main>

        </main>

    </body>
</html>
