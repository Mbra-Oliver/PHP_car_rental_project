<?php 

function checkExist($databaseConnexion, $table, $key, $field){
    
    $query = $databaseConnexion->prepare('SELECT * from '.$table.' where '.$field.' = ?');

    $query->execute(array($key));   


    return $query->rowCount();
}


function get_total_all_records($databaseConnexion, $table){
    
    $query = $databaseConnexion->prepare('SELECT * FROM '.$table);
    $query->execute();
    $result = $query->fetchAll();
    return $query->rowCount();
}

?>