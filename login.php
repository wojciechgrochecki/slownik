<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styling/login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
        <title>Dictionary</title>
    </head>
    <body>
        <?php
        include_once 'klasy/Baza.php';
        include_once 'klasy/User.php';
        include_once 'klasy/UserManager.php';

        $db = new Baza("localhost", "root", "", "dictionary");
        $um = new UserManager();
//parametr z GET – akcja = wyloguj
        if (filter_input(INPUT_GET, "akcja") == "wyloguj") {
            $um->logout($db);
        }
//kliknięto przycisk submit z name = zaloguj
        if (filter_input(INPUT_POST, "zaloguj")) {
            $userId = $um->login($db); //sprawdź parametry logowania
            if ($userId > 0) {
                //poprawne logownie, użytkownik jest przenoszony do strony głównej
                header("location:main.php");
            } else {
                echo "<p>Błędna nazwa użytkownika lub hasło</p>";
                $um->loginForm(); //Pokaż formularz logowania
            }
        } else {
            //pierwsze uruchomienie skryptu processLogin
            $um->loginForm();
        }
        ?>

    </body>
</html>
