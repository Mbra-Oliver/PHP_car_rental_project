<?php

include('../../../configs/database.php');
include('./functions.php');

//Backend 

if (isset($_POST['operation'])) {

    $currentOperaion = $_POST['operation'];
    $output = array(
        'success' => false,
        'error' => false,
        'success_msg' => '',
        'error_msg' => ''
    );

    if ($currentOperaion == 'Add') {

        $brandName = $_POST['brandName'];
        //Check if brandName are not already exist

        $checkName = checkExist($databaseConnexion, 'brands', $brandName, 'name');

        if ($checkName >= 1) {
            //brand already exist

            $output = array(
                'success' => false,
                'error' => true,
                'success_msg' => '',
                'error_msg' => 'Brand Name already taken'
            );
        } else {
            $query = $databaseConnexion->prepare('INSERT INTO brands(name) VALUES(?)');
            $query->execute(array($brandName));

            if ($query) {
                $output = array(
                    'success' => true,
                    'error' => false,
                    'success_msg' => 'Brand inserted successfully',
                    'error_msg' => ''
                );
            }
        }

        //Insert in the brands table


    }

    if ($currentOperaion == 'FetchSingle') {

        $brandid = $_POST['brandId'];

        $query = $databaseConnexion->prepare('SELECT * FROM brands Where id = ?');
        $query->execute(array($brandid));

        foreach ($query->fetchAll() as $result) {
            $output = array(
                'id' => $result['id'],
                'name' => $result['name'],
            );
        }
    }

    if($currentOperaion == 'Edit'){

        $query = $databaseConnexion->prepare('UPDATE brands SET name = ? WHERE id = ?');
        $query->execute(array($_POST['brandName'], $_POST['brand_id']));
        
        if($query){
            $output = array(
                'success'=>true,
                'error'=>false,
                'success_msg'=> 'Brand Updated',
                'error_msg'=> ''
            );

        }

    }

    if($currentOperaion == 'DeleteSingle'){
        $query = $databaseConnexion->prepare('DELETE  FROM brands WHERE id = ?');
        $query->execute(array($_POST['brandId']));

        $output = array(
            'success'=>true
        );
    }

    echo json_encode($output);
}
