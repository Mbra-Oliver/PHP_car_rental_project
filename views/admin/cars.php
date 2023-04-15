<?php


include('./includes/header.php');


//Get brands :wrong way

require('../../configs/database.php');


$brandQuery = $databaseConnexion->prepare('SELECT * FROM brands');
$brandQuery->execute();

?>

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->


<style>
    .row {
        margin-bottom: .5rem;
    }

    img {
        width: 50px;

    }
</style>


<div class="modal" tabindex="-1" role="dialog" id="carModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="carForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Image</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" class="form-control" id="carPicture" name="carPicture">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Car Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="carName" name="carName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Choose Brand</label>
                        </div>
                        <div class="col-md-8">
                            <select name="brandId" id="brandId" class="form-control">

                                <option value=""></option>
                                <?php

                                foreach ($brandQuery as $brand) { ?>

                                    <option value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>

                                <?php }

                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Hour Price</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="hourPrice" name="hourPrice">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Day Price</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="dayPrice" name="dayPrice">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Month Price</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="monthPrice" name="monthPrice">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="car_id" id="car_id">
                    <input type="hidden" name="hiden_image" id="hiden_image">
                    <input type="hidden" name="operation" id="operation">
                    <button type="submit" class="btn btn-primary" id="action_btn"></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="closeModal" >Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="page-breadcrumb bg-white">

    <div class="row align-items-center">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Car</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <div class="d-md-flex">
                <ol class="breadcrumb ms-auto">
                    <li><a href="#" class="fw-normal"></a></li>
                </ol>
                <div class="btn btn-danger  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white open-cars-modal">Add new Car</div>
            </div>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <div class="table-responsive">
                    <table class="table table-striped" id="brandTable">
                        <thead>
                            <th>#</th>
                            <th>Image</th>
                            <th>Car</th>
                            <th>Brand</th>
                            <th>Hour Price</th>
                            <th>Day Price</th>
                            <th>Month Price</th>
                            <th>Available</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include('./includes/footer.php') ?>


<script>
    //initialize jquery
    $(document).ready(function() {

        //Get All datas in dataTables
        var carDataTabale = $('#brandTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "backend/m_cars.php",
                data: {
                    operation: 'FetchAll'
                },
                type: "POST",
            },
            "columnDefs": [{
                "targets": []
            }]

        })
        //check if we press on open modal buttton

        $('.open-cars-modal').click(function() {
            $('#operation').val('Add');
            $('#modal_title').text('Add new Brand for cars');
            $('#action_btn').text('Add the car')
            $('#carModal').modal('show');
        });

        $('#closeModal').click(function() {
            $('#carModal').hide();
            $('#carForm')[0].reset();
        })

        //Manage the form : edit and store

        $(document).on('submit', '#carForm', (e) => {

            e.preventDefault();

            var carName = $('#carName').val();
            var brandId = $('#brandId').val();
            var hourPrice = $('#hourPrice').val();
            var dayPrice = $('#dayPrice').val();
            var monthPrice = $('#monthPrice').val();

            var carPicture = $('#carPicture').val()

            if (carPicture != '') {

                var extension = $('#carPicture').val().split('.').pop().toLowerCase();

                if (extension != '') {
                    if (jQuery.inArray(extension, ['gif', 'png', 'jpg']) == -1) {
                        alert('The image format not supported');
                        $('#carPicture').val('');
                        return false;
                    }
                }
            }

            if (carName != '' && brandId != '' && hourPrice != '' && dayPrice != '' && monthPrice != '') {
                $.ajax({
                    url: 'backend/m_cars.php',
                    method: 'POST',
                    data: new FormData($('#carForm')[0]),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (data) => {
                        //get the data
                        if (data.success) {

                            showAlert('Success', data.success_msg, 'success', data.success_msg)

                            $('#carModal').modal('hide');
                            $('#carForm')[0].reset();
                            carDataTabale.ajax.reload();
                        }
                        if (data.error) {

                            showAlert('Error', data.error_msg, 'error', data.error_msg)

                            // $('#carForm')[0].reset();

                        }
                    }
                })
            } else {
                showAlert('Error', 'All field are required', 'error', 'Try Again')
            }
        })


        $(document).on('click', '.update', function() {

            var button = $(this)[0];
            var carId = $(button).attr('id')

            $.ajax({
                url: 'backend/m_cars.php',
                method: 'POST',
                data: {
                    operation: 'FetchSingle',
                    carId: carId
                },
                dataType: 'json',
                success: (data) => {
                    //Leave 

                    $('#modal_title').text('Edit Brand');
                    $('#action_btn').text('Submit for Edit');
                    $('#hiden_image').val(data.image);
                    $('#carName').val(data.name);
                    $('#hourPrice').val(data.hour_price);
                    $('#dayPrice').val(data.day_price);
                    $('#monthPrice').val(data.month_price);
                    $('#car_id').val(data.id);
                    $('#operation').val('Edit');
                    $('#carModal').modal('show');


                }
            })

        })
        $(document).on('click', '.delete', function() {

            var button = $(this)[0];
            var car_id = $(button).attr('id')

            $.ajax({
                url: 'backend/m_cars.php',
                method: 'POST',
                data: {
                    operation: 'DeleteSingle',
                    car_id: car_id
                },
                dataType: 'json',
                success: (data) => {
                    showAlert('Car Deleted', 'Car deleted successfully', 'error', 'Car Deleted Close')
                    carDataTabale.ajax.reload()
                }
            })

        })

        function showAlert(title, text, icon, confirmButtonText) {
            Swal.fire({
                title,
                text,
                icon,
                confirmButtonText
            })
        }

    });
</script>