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

        $carName = $_POST['carName'];
        $brandId = $_POST['brandId'];
        $hourPrice = $_POST['hourPrice'];
        $dayPrice = $_POST['dayPrice'];
        $monthPrice = $_POST['monthPrice'];
        //Check if brandName are not already exist

        $checkName = checkExist($databaseConnexion, 'cars', $carName, 'name');

        if ($checkName >= 1) {
            //brand already exist

            $output = array(
                'success' => false,
                'error' => true,
                'success_msg' => '',
                'error_msg' => 'Car Name already taken'
            );
        } else {
            $query = $databaseConnexion->prepare('INSERT INTO cars(name, brand_id, hour_price, day_price, month_price, available) VALUES(?, ?,?,?,?, ?)');
            $query->execute(array($carName, $brandId, $hourPrice, $dayPrice, $monthPrice, false));

            if ($query) {
                $output = array(
                    'success' => true,
                    'error' => false,
                    'success_msg' => 'Car inserted successfully',
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

    if ($currentOperaion == 'FetchAll') {
        $query = '';
        $output = array();
        $query .= "SELECT cars.*, brands.name as brand FROM cars LEFT JOIN brands ON cars.brand_id = brands.id ";
        if (isset($_POST["search"]["value"])) {
            $query .= 'WHERE cars.name LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (isset($_POST["order"])) {
            $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $query .= 'ORDER BY id DESC ';
        }
        if ($_POST["length"] != -1) {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $statement = $databaseConnexion->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $data = array();
        $filtered_rows = $statement->rowCount();
        foreach ($result as $row) {

            if($row["available"] == 0){
               $available = '<span class="btn btn-primary btn-xs">Available for booking</span>';
            }
            if($row["available"] == 1){
                $available = '<span class="btn btn-warning btn-xs">In booking</span>';
            }

            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["name"];
            $sub_array[] = $row["brand"];
            $sub_array[] = $row["hour_price"]." $";
            $sub_array[] = $row["day_price"]." $";
            $sub_array[] = $row["month_price"]." $";
            $sub_array[] = $available;
            $sub_array[] = '<button type="button" name="update" id="' . $row["id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $sub_array[] = '<button type="button" name="delete" id="' . $row["id"] . '" class="btn btn-danger btn-xs delete">Delete</button>';
            $data[] = $sub_array;
        }
        $output = array(
            "draw"    => intval($_POST["draw"]),
            "recordsTotal"  =>  $filtered_rows,
            "recordsFiltered" => get_total_all_records($databaseConnexion, 'brands'),
            "data"    => $data
        );
    }

    if ($currentOperaion == 'Edit') {

        $query = $databaseConnexion->prepare('UPDATE brands SET name = ? WHERE id = ?');
        $query->execute(array($_POST['brandName'], $_POST['brand_id']));

        if ($query) {
            $output = array(
                'success' => true,
                'error' => false,
                'success_msg' => 'Brand Updated',
                'error_msg' => ''
            );
        }
    }

    if ($currentOperaion == 'DeleteSingle') {
        $query = $databaseConnexion->prepare('DELETE  FROM brands WHERE id = ?');
        $query->execute(array($_POST['brandId']));

        $output = array(
            'success' => true
        );
    }

    echo json_encode($output);
}
