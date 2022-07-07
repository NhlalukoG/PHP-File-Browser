<?php
// Called by another script, session already started
$_SESSION['root'] = isset($_SESSION['root']) ? $_SESSION['root'] : "/var/www/html";
/* Let's connect to the database */
require_once $_SESSION['root']."/api/config/config.php";
/* Let's get the results */

class AccessDatabase{
    private $db = null;
    private $link = null;
    // Let's get the db connection
    public function connect(){
        $this->db = new Connect("PCloud");
        $this->link = $this->db->connectDB();
    }
    public function __construct(){
        // Constructor
        $this->connect();
    }
    public function executeSQL($query){
        return $this->link->query($query);
    }
    /*
     * Login & Register management
     */
    public function login($email, $passwd){
        $query = "SELECT * FROM users WHERE email = '$email' AND passwd = '$passwd' LIMIT 1";
        return $this->executeSQL($query);
    }
}

?>
