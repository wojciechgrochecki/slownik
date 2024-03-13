<?php

class User {

    const STATUS_USER = 1;
    const STATUS_ADMIN = 2;

    protected $userName;
    protected $passwd;
    protected $fullName;
    protected $email;
    protected $date;

    //metody klasy:
    function __construct($userName, $email, $passwd) {
        //implementacja konstruktora
        $this->userName = $userName;
        $this->email = $email;
        $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
        $this->date = new DateTime();
    }

//    public function show() {
//        echo $this->userName . " " . $this->fullName . " " . $this->email . " ";
//        echo "status: " . $this->status . " " . $this->date->format('Y-m-d') . "<br/>";
//    }

    public function setUserName($newUserName) {
        $this->userName = $newUserName;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getPasswd() {
        return $this->passwd;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDate() {
        return $this->date;
    }

    public function setPasswd($passwd): void {
        $this->passwd = $passwd;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setDate($date): void {
        $this->date = $date;
    }

    static function getAllUsers($plik) {
        $tab = json_decode(file_get_contents($plik));
        //var_dump($tab);
        echo "<h3>Użytkownicy odczytani z pliku JSON:</h3>";
        foreach ($tab as $val) {
            echo $val->userName . " " . $val->fullName . " " . $val->email . " ";
            echo "status: " . $val->status . " " . $val->date . "<br/><br/>";
        }
    }

    function toArray() {
        $arr = [
            "userName" => $this->userName,
            "date" => $this->date->format('Y-m-d'),
            "email" => $this->email,
            "passwd" => $this->passwd
        ];
        return $arr;
    }

    function save($plik) {
        $tab = json_decode(file_get_contents($plik), true);
        array_push($tab, $this->toArray());
        file_put_contents($plik, json_encode($tab));
    }

//    function saveXML() {
//        //wczytujemy plik XML:
//        $xml = simplexml_load_file('users.xml');
//        //dodajemy nowy element user (jako child)
//        $xmlCopy = $xml->addChild("user");
//        //do elementu dodajemy jego właściwości o określonej nazwie i treści
//        $xmlCopy->addChild("userName", $this->userName);
//        $xmlCopy->addChild("passwd", $this->passwd);
//        $xmlCopy->addChild("fullName", $this->fullName);
//        $xmlCopy->addChild("email", $this->email);
//        $xmlCopy->addChild("date", $this->date->format('Y-m-d'));
//        $xmlCopy->addChild("status", $this->status);
//        //zapisujemy zmodyfikowany XML do pliku:
//        $xml->asXML('users.xml');
//    }
//    static function getAllUsersFromXML() {
//        $allUsers = simplexml_load_file('users.xml');
//        echo "<h3>Użytkownicy odczytani z pliku XML:</h3>";
//        echo "<ul>";
//        foreach ($allUsers as $user):
//            $userName = $user->userName;
//            $fullName = $user->fullName;
//            $date = $user->date;
//            $email = $user->email;
//            $status = $user->status;
//            echo "<li>$userName, $fullName, $email, status:$status, $date  </li>";
//        endforeach;
//        echo "</ul>";
//    }

    public function saveDB($db) {
        $sql = "INSERT INTO users VALUES (NULL, '{$this->userName}', "
                . "'{$this->passwd}', '{$this->email}', '{$this->date->format('Y-m-d-h-i-s')}')";

        $db->insert($sql);
    }

    static function getAllUsersFromDB($db) {
        $sql = "SELECT * FROM users;";
        $pola = ['userName', 'fullName', 'passwd', 'email', 'date'];
        return $db->select($sql, $pola);
    }

}

?>
