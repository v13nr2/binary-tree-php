<?php

require './data/btree.placement.function.php';
//'localhost', 'u7154823_api', '2bXJtm_x=1;%', 'u7154823_api'
try {
    $dbh = new PDO('mysql:host=localhost;dbname=u7154823_api', 'u7154823_api', '2bXJtm_x=1;%');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY id ASC') as $row) {
        array_push($members, $row);
    }
    
    $membersPlacement = array();
    foreach($dbh->query('SELECT * from nng_members  ORDER BY id ASC') as $row) {
        array_push($membersPlacement, $row);
    }
    
    $dbh = null;    
    
    $result = getPlacement($members, empty($_GET['id']) ? 0 : $_GET['id'], empty($_GET['sisi']) ? 'kiri' : $_GET['sisi']);

    $btree = getBTree($members, empty($_GET['id']) ? 0 : $_GET['id']);
    
    

    echo '<pre>';
    print_r($result);
    echo '</pre>';

    //echo json_encode($result);

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}