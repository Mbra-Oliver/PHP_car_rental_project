<?php


function upload()
{
    if (isset($_FILES['carPicture'])) {

        $extension = explode('.', $_FILES['carPicture']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = './../../../assets/upload/' . $new_name;
        move_uploaded_file($_FILES['carPicture']['tmp_name'], $destination);
        return $new_name;
    }
}


function checkExist($databaseConnexion, $table, $key, $field)
{

    $query = $databaseConnexion->prepare('SELECT * from ' . $table . ' where ' . $field . ' = ?');

    $query->execute(array($key));


    return $query->rowCount();
}


function get_total_all_records($databaseConnexion, $table)
{

    $query = $databaseConnexion->prepare('SELECT * FROM ' . $table);
    $query->execute();
    $result = $query->fetchAll();
    return $query->rowCount();
}
