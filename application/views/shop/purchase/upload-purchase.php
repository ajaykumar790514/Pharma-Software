<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-wrapper">
<div class="container-fluid" style="max-width: 100% !important;">
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
        <?php echo $breadcrumb;?>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- Row -->
<div class="row">
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <h3 class="card-title" id="test">Upload Purchase Data </h3>
                                <h6 class="card-subtitle"></h6>
                                </div>
                                <div class="float-right col-md-6 col-lg-6 col-sm-6">
                                <a class="float-right btn  btn-sm btn-danger" href="<?php echo base_url('purchase/'.$menu_id);?>">Back</a>
                            </div>
                        </div>
                      </div>
                      <div class="col-4"></div>
                    <div class="col-4">
                        <form  id="myForm" class="ajaxsubmit"  action="<?=$action_url;?>" method="post" enctype= multipart/form-data>
                        <div class="row">
                            <div class="col-10">
                            <div class="form-group">
                            <label for="">Select Excel</label>
                            <input type="file" name="import_file" id="import_file" required class="form-control" >
                             </div>
                            </div>
                            <div class="col-2" style="margin-top: 30px;">
                            <div class="form-group">
                            <button type="button" id="btnsubmit"  class="btn btn-primary">Submit</button>
                            </div>
                          </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
<script>
   $(document).ready(function () {
        $("#btnsubmit").on("click", function() {
            // Trigger form validation
            if ($("#myForm").valid()) {
                $("#btnsubmit").prop('disabled', true);
                var formData = new FormData($("#myForm")[0]);
                $.ajax({
                    url: $("#myForm").attr("action"),
                    type: $("#myForm").attr("method"),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        console.log("Success Data:", data);

                        try {
                            var parsedData = JSON.parse(data);
                            console.log("Parsed Data:", parsedData);

                            if (parsedData.res == 'success') {
                                $("#btnsubmit").prop('disabled', false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: parsedData.msg,
                                    timer: 2000, 
                                    showConfirmButton: false
                                });
                                $("#myForm")[0].reset();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: parsedData.msg
                                });
                            }
                        } catch (e) {
                            console.error("Error parsing JSON: ", e);
                            alert("Error parsing server response.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        alert("AJAX Error: " + status);
                    }
                });
            }
        });
    });
</script>


