<?php

require './data/to-top.php';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=', '', ')kY%');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY id ASC') as $row) {
        array_push($members, $row);
    }
    $dbh = null; 

    $result = goToTop($members, empty($_GET['id']) ? 0 : $_GET['id']);

    echo '<pre>';
    print_r($result);
    echo '</pre>';

    //echo json_encode($result);

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}