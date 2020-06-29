<title>Binary Tree DeMoneyBox</title><?php

require './data/btree.function.php';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=binari', 'root', '');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY id ASC') as $row) {
        array_push($members, $row);
    }
    $dbh = null;    
    
    $result = getSpillOverMember($members, empty($_GET['id']) ? 0 : $_GET['id'], empty($_GET['sisi']) ? 'kiri' : $_GET['sisi']);

    $btree = getBTree($members, empty($_GET['id']) ? 0 : $_GET['id']);

    echo '<pre>';
    print_r($result);
    echo '</pre>';


} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}