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

        $image = '';

        if ($checkName >= 1) {
            //brand already exist

            $output = array(
                'success' => false,
                'error' => true,
                'success_msg' => '',
                'error_msg' => 'Car Name already taken'
            );
        } else {

            if ($_FILES['carPicture']['name'] != '') {

                $image = upload();
            }

            $query = $databaseConnexion->prepare('INSERT INTO cars(image, name, brand_id, hour_price, day_price, month_price, available) VALUES(?, ?, ?,?,?,?, ?)');
            $query->execute(array($image, $carName, $brandId, $hourPrice, $dayPrice, $monthPrice, false));

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

        $car_id = $_POST['carId'];

        $query = $databaseConnexion->prepare('SELECT * FROM cars Where id = ?');
        $query->execute(array($car_id));

        foreach ($query->fetchAll() as $result) {
            $output = array(
                'id' => $result['id'],
                'image' => $result['image'],
                'name' => $result['name'],
                'hour_price' => $result['hour_price'],
                'day_price' => $result['day_price'],
                'month_price' => $result['month_price'],
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

            if ($row["available"] == 0) {
                $available = '<span class="btn btn-primary btn-xs">Available for booking</span>';
            }
            if ($row["available"] == 1) {
                $available = '<span class="btn btn-warning btn-xs">In booking</span>';
            }

            $sub_array = array();

            $sub_array[] = $row["id"];
            $sub_array[] = '<img src="./../../../assets/upload/' . $row['image'] . '" />';
            $sub_array[] = $row["name"];
            $sub_array[] = $row["brand"];
            $sub_array[] = $row["hour_price"] . " $";
            $sub_array[] = $row["day_price"] . " $";
            $sub_array[] = $row["month_price"] . " $";
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

        //Check if the user choose an new image


        $oldImage = $_POST['hiden_image'];

        if ($_FILES['carPicture']['name'] != '') {

            $image = upload();

            $removeQuery = unlink('./../../../assets/upload/' . $oldImage);
        } else {
            $image = $oldImage;
        }

        //remove old image




        $query = $databaseConnexion->prepare('UPDATE cars SET image = ?, name = ?, brand_id = ?, hour_price=?, day_price=?, month_price= ? WHERE id = ?');

        $query->execute(array($image, $_POST['carName'], $_POST['brandId'], $_POST['hourPrice'], $_POST['dayPrice'],  $_POST['monthPrice'],  $_POST['car_id']));

        if ($query) {
            $output = array(
                'success' => true,
                'error' => false,
                'success_msg' => 'Car Updated',
                'error_msg' => ''
            );
        }
    }

    if ($currentOperaion == 'DeleteSingle') {

        $image = '';

        //Remove image from storage

        $queryImage = $databaseConnexion->prepare('SELECT image from cars where id = ?');

        $queryImage->execute(array($_POST['car_id']));

        foreach ($queryImage as $res) {
            $image = $res['image'];
        }

        if (!empty($image)) {
            $Query = unlink('./../../../assets/upload/' . $image);
        }

        $query = $databaseConnexion->prepare('DELETE  FROM cars WHERE id = ?');

        $query->execute(array($_POST['car_id']));




        $output = array(
            'success' => true
        );
    }

    echo json_encode($output);
}
