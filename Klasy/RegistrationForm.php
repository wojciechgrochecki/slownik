<?php

class RegistrationForm {

    protected $user;

    function __construct() {
        ?>
        <main>
            <div class="form-wrap">
                <form action="register.php" method="post">
                    <div class="inputs">
                        <div class="input-wrap">
                            <div class="icon-wrap"><i class='fas fa-user-alt input-icon'></i></div>
                            <input type="text" placeholder="Nazwa użytkownika" id="userName" name="userName"
                                   minlength="4" required />
                        </div>
                        <div class="input-wrap">
                            <div class="icon-wrap"><i class='fas fa-lock input-icon'></i></div>
                            <input type="password" placeholder="Hasło" id="passwd" name="passwd" required minlength="4" />
                        </div>
                        <div class="input-wrap">
                            <div class="icon-wrap"><i class="fa-solid fa-at input-icon"></i></div>
                            <input type="email" placeholder="E-mail" id="email" name="email" required />
                        </div>
                    </div>
                    <div class="button-wrap"><button type="submit" value="submit" name="submit">Rejestracja</button></div>
                    <p class="register-text">Masz już konto? <a href='login.php'>Zaloguj się</a></p>
                </form>
            </div>
        </main>
        <?php
    }

    function checkUser() { // podobnie jak metoda validate z lab4
        $args = [
            'userName' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżó_-]{2,25}$/']],
            'passwd' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS],
            'email' => FILTER_VALIDATE_EMAIL,
        ];
        $dane = filter_input_array(INPUT_POST, $args);
        $errors = "";
        foreach ($dane as $key => $val) {
            if ($val === false or $val === NULL) {
                $errors .= $key . " ";
            }
        }
        if ($errors === "") {
            //Dane poprawne – utwórz obiekt user
            $this->user = new User($dane['userName'], $dane['email'], $dane['passwd']);
        } else {
            echo "<p>Błędne dane:$errors</p>";
            $this->user = NULL;
        }

        return $this->user;
    }

}
