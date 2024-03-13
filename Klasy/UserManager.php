<?php

class UserManager {

    function loginForm() {
        ?>
        <main>
            <div class="form-wrap">
                <form action="login.php" method="post">
                    <div class="inputs">
                        <div class="input-wrap">
                            <div class="icon-wrap"><i class='fas fa-user-alt input-icon'></i></div>
                            <input type="text" placeholder="Nazwa użytkownika" id="login" name="login" minlength="2" required />
                        </div>
                        <div class="input-wrap">
                            <div class="icon-wrap"><i class='fas fa-lock input-icon'></i></div>
                            <input type="password" placeholder="Hasło" id="passwd" name="passwd" required
                                   minlength="4" />
                        </div>
                    </div>
                    <div class="button-wrap"><button type="submit" value="Zaloguj" name="zaloguj">Zaloguj</button></div>
                    <p class="register-text">Nie masz konta? <a href='register.php'>Zarejestruj się</a></p>
                </form>
            </div>
        </main> <?php
    }

    function login($db) {
        //funkcja sprawdza poprawność logowania
        //wynik - id użytkownika zalogowanego lub -1
        $args = [
            'login' => ['filter' => FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżó_-]{2,25}$/']],
            'passwd' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS],
        ];
        //przefiltruj dane z GET (lub z POST) zgodnie z ustawionymi w $args filtrami:
        $dane = filter_input_array(INPUT_POST, $args);
        //sprawdź czy użytkownik o loginie istnieje w tabeli users
        //i czy podane hasło jest poprawne
        $login = $dane["login"];
        $passwd = $dane["passwd"];
        $userId = $db->selectUser($login, $passwd, "users");
        if ($userId >= 0) { //Poprawne dane
            session_start();
            //usuń wszystkie wpisy historyczne dla użytkownika o $userId
            $sql = "DELETE FROM logged_in_users WHERE userID = '$userId'";
            $db->delete($sql);
            //ustaw datę - format("Y-m-d H:i:s");
            $date = new DateTime();
            $date = $date->format("Y-m-h H:i:s");
            //pobierz id sesji i dodaj wpis do tabeli logged_in_users
            $sessionId = session_id();
            $sql = "INSERT INTO logged_in_users VALUES ('$sessionId',$userId,'$date');";
            $db->insert($sql);
            $this->getLoggedInUser($db, $sessionId);
        }
        return $userId;
    }

    function logout($db) {
        //pobierz id bieżącej sesji (pamiętaj o session_start()
        session_start();
        $sessionId = session_id();
        //usuń sesję (łącznie z ciasteczkiem sesyjnym)
        session_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        //usuń wpis z id bieżącej sesji z tabeli logged_in_users
        $sql = "DELETE FROM logged_in_users WHERE sessionId ='$sessionId';";
        $db->delete($sql);
    }

    function getLoggedInUser($db, $sessionId) {
        //wynik $userId - znaleziono wpis z id sesji w tabeli logged_in_users
        $sql = "SELECT DISTINCT userId from logged_in_users WHERE sessionId = '$sessionId';";
        $result = $db->getMysqli()->query($sql);
        if ($result->num_rows == 0) {
            //wynik -1 - nie ma wpisu dla tego id sesji w tabeli logged_in_users
            return -1;
        }
        $userId = $result->fetch_object()->userId;
        $result->free_result();
        return $userId;
    }

}
