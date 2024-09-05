<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-wrapper">
<div class="container-fluid" style="max-width: 100% !important;">
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
        <?=$breadcrumb;?>
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
                                <h3 class="card-title" id="test">Data Import Master </h3>
                                <h6 class="card-subtitle"></h6>
                            </div>
                        </div>
                      </div>
                    <div class="col-12">
                        <form  id="myForm" class="ajaxsubmit"  action="<?=$action_url;?>" method="post" enctype= multipart/form-data>
                        <div class="row">
                        <div class="col-3">
                                        <div class="form-group">
                                            <label class="control-label">Business:</label>
                                            <select class="form-control" style="width:100%;" name="business" id="business_id" onchange="fetch_shop(this.value)">
                                            <option value="">Select Business</option>
                                            <?php foreach ($business as $busi) { ?>
                                            <option value="<?php echo $busi->id; ?>">
                                                <?php echo $busi->title; ?>(<?php echo $busi->owner_name; ?>)
                                            </option>
                                            <?php } ?>
                                            </select>
                                        </div>                         
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label class="control-label">Shop:</label>
                                            <select class="form-control shop_id" style="width:100%;" name="shop" id="shop_id">
                                            
                                            </select>
                                        </div>
                                    </div>
                            <div class="col-3">
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

                    <div class="col-12 d-none bundle_details">
                        <h5>Insertion Item Detais</h5>
                        <div id="bundle"></div>
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
<script type="text/javascript">
   function fetch_shop(business_id)
   {
    //    alert(business_id);
    $.ajax({
        url: "<?php echo base_url('master-data/fetch_shop'); ?>",
        method: "POST",
        data: {
            business_id:business_id
        },
        success: function(data){
            $(".shop_id").html(data);
        },
    });
   }
</script>
<script>
   $(document).ready(function () {
        $("#btnsubmit").on("click", function() {
           // Check if business or shop is selected
           var businessSelected = $("#business_id").val();
            var shopSelected = $("#shop_id").val();
            if (businessSelected === "" || shopSelected === "") {
                Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a business and a shop.'
                });
                $("#myForm")[0].reset();
                return; // Stop further execution
            }
            // Trigger form validation
            if ($("#myForm").valid()) {
                $("#btnsubmit").prop('disabled', true);

                // Serialize the form data
                var formData = new FormData($("#myForm")[0]);

                // Perform AJAX submission
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

                                // Show SweetAlert success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: parsedData.msg,
                                    timer: 2000,  // Optional timer to auto-close the alert after 2 seconds
                                    showConfirmButton: false
                                });
                                $('.bundle_details').removeClass('d-none');
                                $('#bundle').html(parsedData.table);
                                $("#myForm")[0].reset();

                                // Additional actions can be added here

                            } else {
                                // Show SweetAlert error message
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


