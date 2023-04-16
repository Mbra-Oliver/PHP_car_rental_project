<?php

include('./views/customer/includes/header.php');

?>
<!--Start Content-->

<div class="container main-container">
  <div class="grid_card">
    <?php foreach ($fetchCars->fetchAll() as $result) { ?>
      <div class="grid_item">

        <div class="grid_image" style="background-image:url('./assets/upload/<?= $result['image'] ?>')"></div>
        <h5 class="card-title"> <a href="details.php?car_id=<?= $result['id'] ?>"><?= $result['name'] ?></a> </h5>

        <p class="card-text"><small class="text-muted">
            <?php if ($result['available'] == 0) {
              echo 'cars available for booking';
            }  ?></small></p>


        <div class="link">Go to Details</div>

      </div>

    <?php }  ?>
  </div>
</div>

<!--End content here-->

</body>

</html>


