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



<script src="./../assets/admins/plugins/bower_components/jquery/dist/jquery.min.js"></script>


<div class="details-box">
    <?php foreach ($singleCarQuery->fetchAll() as $result) { ?>
        <div class="image-part">
            <img src="./assets/upload/<?= $result['image'] ?>" class="img-fluid rounded-start" alt="...">
        </div>
        <div class="text-part">

            <div><?= $result['name'] ?></div>
            <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio cum quibusdam recusandae, possimus, rem et atque est ipsum eum maxime ipsam esse numquam minima reprehenderit qui dignissimos iste similique voluptas?</div>

            <?php

            if ($result['available'] == 0) {


                if ($userLogged) {
                    echo '<button>Book this car now</button>';
                } else { ?>
                    <div class="alert alert-warning">You need to log in for rent this card <a href="">Go to Login</a> </div>
            <?php }
                // 
            }

            ?>

        </div>

    <?php } ?>
</div>

<select name="" id="test">
    <?php foreach ($optionList as $option) { ?>

        <option value="<?= $option['id'] ?>"><?= $option['libelle'] ?></option>

    <?php }  ?>
</select>


<script>
    $(document).ready(function() {
        function getState(val) {

            console.log(val)
            $.ajax({
                type: "POST",
                url: "./ajax/get-country-state-ep.php",
                data: 'country_id=' + val,
                success: function(data) {
                    $("#state-list").html(data);
                    $("#loader").hide();
                }
            });
        }

        $('#test').on('change', function() {
            console.log('value_change', this.value)

            $.ajax({
                type: "POST",
                url: "./ajax/get-country-state-ep.php",
                data: 'country_id=' + this.value,
                success: function(data) {
                    $("#state-list").html(data);
                    $("#loader").hide();
                }
            });
        })
    })
</script>
<style>
    .details-box {
        display: flex;
        gap: 1rem;
        margin: auto;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .details-box .image-part {
        width: 30%;
        margin-left: 1rem;
    }

    .details-box .image-part img {
        width: 100%;
    }

    .details-box .text-part {
        width: 68%;
    }
</style>