<?php include('./includes/header.php') ?>

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->



<div class="modal" tabindex="-1" role="dialog" id="brandModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="brandForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Brand Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="brandName" name="brandName">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="brand_id" id="brand_id">
                    <input type="hidden" name="operation" id="operation">
                    <button type="submit" class="btn btn-primary" id="action_btn">Save Brand</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="page-breadcrumb bg-white">

    <div class="row align-items-center">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Brand</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <div class="d-md-flex">
                <ol class="breadcrumb ms-auto">
                    <li><a href="#" class="fw-normal"></a></li>
                </ol>
                <div class="btn btn-danger  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white open-brands-modal">Add new brand</div>
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
                            <th>Brand</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>

<?php include('./includes/footer.php') ?>


<script>
    //initialize jquery
    $(document).ready(function() {

        //Get All datas in dataTables
        var brandDataTable = $('#brandTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "backend/fetch_brand.php",
                type: "POST",
            },
            "columnDefs": [{
                "targets": []
            }]

        })
        //check if we press on open modal buttton

        $('.open-brands-modal').click(function() {
            $('#operation').val('Add');
            $('#modal_title').text('Add new Brand for cars');
            $('#brandModal').modal('show');
        });

        //Manage the form : edit and store

        $(document).on('submit', '#brandForm', (e) => {

            e.preventDefault();

            var brandName = $('#brandName').val();
            if (brandName != '') {
                $.ajax({
                    url: 'backend/m_brands.php',
                    method: 'POST',
                    data: new FormData($('#brandForm')[0]),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (data) => {
                        //get the data

                        if (data.success) {


                            showAlert('Success', data.success_msg, 'success', data.success_msg)

                            $('#brandModal').modal('hide');
                            $('#brandForm')[0].reset();
                            brandDataTable.ajax.reload();
                        }
                        if (data.error) {

                            showAlert('Error', data.error_msg, 'error', data.error_msg)

                            $('#brandForm')[0].reset();

                        }
                    }
                })
            }
        })


        $(document).on('click', '.update', function() {

            var button = $(this)[0];
            var brandId = $(button).attr('id')

            $.ajax({
                url: 'backend/m_brands.php',
                method: 'POST',
                data: {
                    operation: 'FetchSingle',
                    brandId: brandId
                },
                dataType: 'json',
                success: (data) => {
                    //Leave 

                    $('#modal_title').text('Edit Brand');
                    $('#action_btn').text('Submit for Edit');
                    $('#brandName').val(data.name);
                    $('#brand_id').val(data.id);
                    $('#operation').val('Edit');
                    $('#brandModal').modal('show');

                }
            })

        })
        $(document).on('click', '.delete', function() {

            var button = $(this)[0];
            var brandId = $(button).attr('id')

            $.ajax({
                url: 'backend/m_brands.php',
                method: 'POST',
                data: {
                    operation: 'DeleteSingle',
                    brandId: brandId
                },
                dataType: 'json',
                success: (data) => {
                    showAlert('Brand Deleted', 'Brand deleted successfully', 'error', 'Brand Deleted Close')
                    brandDataTable.ajax.reload()
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