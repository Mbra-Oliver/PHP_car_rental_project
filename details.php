<?php

include('./views/customer/includes/header.php');

$carId = $_GET['car_id'];


$singleCarQuery = $databaseConnexion->prepare('SELECT cars.*, brands.name as brand FROM cars LEFT JOIN brands ON cars.brand_id = brands.id where cars.id = ?');
$singleCarQuery->execute(array($carId));


$optionList = [
    [
        'id' => 1,
        'libelle' => 'Hour'
    ],
    [
        'id' => 2,
        'libelle' => 'Day'
    ],
    [
        'id' => 3,
        'libelle' => 'Month'
    ],
];

?>


<div class="container main-container">
    <div class="details-box">
        <?php foreach ($singleCarQuery->fetchAll() as $result) { ?>
            <div class="image-data-container">
                <div class="details_image" style="background-image:url('./assets/upload/<?= $result['image'] ?>')">
                </div>

                <div class="cars-libelle"><?= $result['name'] ?></div>
                <!-- <?php

                if ($result['available'] == 0) {


                    if ($userLogged) {
                        echo '<button>Book this car now</button>';
                    } else { ?>
                        <div class="alert alert-warning">You need to log in for rent this card <a href="">Go to Login</a> </div>
                <?php }
                    
                }

                ?> -->

            </div>


            <div class="details-right-side">
                <div class="title">Booking Operation Détails</div>

                <p class="cars-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius, aperiam nobis. In eaque vitae ex obcaecati labore officiis, hic enim quam sint reprehenderit corporis atque facilis vero consectetur adipisci praesentium?</p>

                <div class="custom-btn-primary-bordered">Book this cars Now</div>
            </div>


        <?php } ?>


    </div>


</div>