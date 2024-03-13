<?php

class Baza {

    private $mysqli; //uchwyt do BD

    public function __construct($serwer, $user, $pass, $baza) {
        $this->mysqli = new mysqli($serwer, $user, $pass, $baza);
        /* sprawdz połączenie */
        if ($this->mysqli->connect_error) {
            printf("Nie udało sie połączenie z serwerem: %s\n",
                    $this->mysqli->connect_error);
            exit();
        }
        /* zmien kodowanie na utf8 */
        if ($this->mysqli->set_charset("utf8")) {
            //udało sie zmienić kodowanie
        }
    }

    //koniec funkcji konstruktora
    function __destruct() {
        $this->mysqli->close();
    }

    function query($sql) {
        return $this->mysqli->query($sql);
    }

    function toAssocArray($mySqliResult) {
        $rows = [];
        while ($row = $mySqliResult->fetch_assoc()) {
            $rows[] = $row;
        }
        $mySqliResult->free_result();
        return $rows;
    }

    public function delete($sql) {
        if ($this->mysqli->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function insert($sql) {
        if ($this->mysqli->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function getMysqli() {
        return $this->mysqli;
    }

    public function emptyTable($tableName) {
        if ($this->mysqli->query("TRUNCATE TABLE $tableName;")) {
            return true;
        }
        return false;
    }

    public function selectUser($login, $passwd, $tabela) {
        //parametry $login, $passwd , $tabela – nazwa tabeli z użytkownikami
        //wynik – id użytkownika lub -1 jeśli dane logowania nie są poprawne
        $id = -1;
        $sql = "SELECT * FROM $tabela WHERE userName='$login'";
        if ($result = $this->mysqli->query($sql)) {
            $ile = $result->num_rows;
            if ($ile == 1) {
                $row = $result->fetch_object(); //pobierz rekord z użytkownikiem
                $hash = $row->passwd; //pobierz zahaszowane hasło użytkownika
                //sprawdź czy pobrane hasło pasuje do tego z tabeli bazy danych:
                if (password_verify($passwd, $hash))
                    $id = $row->id; //jeśli hasła się zgadzają - pobierz id użytkownika
            }
        }
        return $id; //id zalogowanego użytkownika(>0) lub -1
    }

}
