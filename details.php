<?php

include('./views/customer/includes/header.php');

$carId = $_GET['car_id'];


$singleCarQuery = $databaseConnexion->prepare('SELECT cars.*, brands.name as brand FROM cars LEFT JOIN brands ON cars.brand_id = brands.id where cars.id = ?');
$singleCarQuery->execute(array($carId));


?>




<div class="details-box">
    <?php foreach ($singleCarQuery->fetchAll() as $result) { ?>
        <div class="image-part">
            <img src="./assets/upload/<?= $result['image'] ?>" class="img-fluid rounded-start" alt="...">
        </div>
        <div class="text-part">

            <div><?= $result['name'] ?></div>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio cum quibusdam recusandae, possimus, rem et atque est ipsum eum maxime ipsam esse numquam minima reprehenderit qui dignissimos iste similique voluptas?</div>

            <?php

            if ($result['available'] == 0) { ?>
                <button>Book this car now</button>
            <?php }

            ?>

        </div>

    <?php } ?>
</div>


<style>
    .details-box{
        display: flex;
        gap: 1rem;
        margin: auto;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .details-box .image-part{
        width: 30%;
        margin-left: 1rem;
    }
    .details-box .image-part img{
        width: 100%;
    }
    .details-box .text-part{
        width: 68%;
    }
</style>