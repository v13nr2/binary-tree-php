<?php

require './data/btree.place.function.php';
//'localhost', '', ';%', ''
try {
    $dbh = new PDO('mysql:host=localhost;dbname=', '', '=1;%');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY sisi DESC, id ASC') as $row) {
        array_push($members, $row);
    }
    

    $dbh = null;    
    
    $result = getSpillOverMember($members, empty($_GET['id']) ? 0 : $_GET['id'], empty($_GET['sisi']) ? 'kiri' : $_GET['sisi']);

    $btree = getBTree($members, empty($_GET['id']) ? 0 : $_GET['id']);
    
    

    // echo '<pre>';
    // print_r($result);
    // echo '</pre>';

    echo json_encode($result);

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}