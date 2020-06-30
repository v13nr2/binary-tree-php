<?php 

include './btree.function.php';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=projects_binarytree', 'root', '');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY id ASC') as $row) {
        array_push($members, $row);
    }
    $dbh = null;

    $result = getBTree($members, empty($_GET['id']) ? 0 : $_GET['id']);
    echo json_encode($result);

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}