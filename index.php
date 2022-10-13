<?php 

include('./views/customer/includes/header.php');

?>
  <!--Start Content-->

  <div class="container">
    <div class="row mt-4">
      <?php foreach ($fetchCars->fetchAll() as $result) { ?>
        <div class="col-md-6 ">



          <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
              <div class="col-md-4">
                <img src="./assets/upload/<?= $result['image'] ?>" class="img-fluid rounded-start" alt="...">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5 class="card-title"> <a href="details.php?car_id=<?= $result['id'] ?>"><?= $result['name'] ?></a> </h5>
                  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                  <p class="card-text"><small class="text-muted">
                      <?php if ($result['available'] == 0) {
                        echo 'cars available for booking';
                      }  ?></small></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php }  ?>
    </div> 
  </div>

  <!--End content here-->

</body>

</html>