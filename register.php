<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
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
        include_once 'klasy/User.php';
        include_once 'klasy/RegistrationForm.php';
        include_once 'klasy/Baza.php';
        session_start();
        $rf = new RegistrationForm(); //wyświetla formularz rejestracji
        $db = new Baza("localhost", "root", "", "dictionary");
        if (filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $user = $rf->checkUser(); //sprawdza poprawność danych
            if ($user === NULL)
                echo "<p>Niepoprawne dane rejestracji.</p>";
            else {
                $user->saveDB($db);
                header("location:main.php");
            }
        }
        ?>
    </body>
</html>
