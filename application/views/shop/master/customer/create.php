<style>
    .fa {
        margin-left: -12px;
        margin-right: 8px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $(".needs-validation").validate({
            rules: {
                fname: "required",
                lname: "required",
                mobile: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    number: true,
                    remote: "<?= $remote ?>null/mobile"
                },
                alternate_mobile: {
                    minlength: 10,
                    maxlength: 10,
                    number: true
                },
                state: "required",
                city: "required",
                gstin: {
                    required: false,
                    remote: "<?= $remote ?>null/gstin"
                },
                vendor_code: {
                    required: true,
                    remote: "<?= $remote ?>null/vendor_code"
                }
            },
            messages: {
                mobile: {
                    remote: "Mobile No. Already Exists!"
                },
                vendor_code: {
                    remote: "Customer Code Already Exists!"
                },
                gstin: {
                    remote: "GSTIN Already Exists!"
                }
            }
        });
    });
</script>
<form class="ajaxsubmit needs-validation reload-tb" action="<?= $action_url ?>" method="post">
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">First Name:</label>
                    <input type="text" class="form-control" name="fname">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Last Name:</label>
                    <input type="text" class="form-control" name="lname">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Mobile:</label>
                    <input type="number" class="form-control" name="mobile">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Email:</label>
                    <input type="email" class="form-control" name="email">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">D.O.B:</label>
                    <input type="date" class="form-control" name="dob" >
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">If B2B:</label>
                    <select name="b2b_b2c" id="b2b_b2c" class="form-control">
                        <option value="b2c">B2C</option>
                        <option value="b2b">B2B</option>
                    </select>
                </div>
            </div>
            <div class="col-6 d-none"  id="b2b_a_mobile">
                <div class="form-group">
                    <label class="control-label">Alternate Mobile:</label>
                    <input type="number" class="form-control" name="alternate_mobile">
                </div>
            </div>
           
            <div class="col-6 d-none"  id="b2b_code">
                <div class="form-group">
                    <label class="control-label">Customer Code:</label>
                    <input type="text" class="form-control" name="vendor_code">
                </div>
            </div>
        
            <div class="col-6 d-none"  id="b2b_gst">
                <div class="form-group">
                    <label class="control-label">GSTIN:</label>
                    <input type="text" class="form-control" name="gstin" id="gstinInput" oninput="validateInput()">
                    <div id="errorMessage" class="text-danger"></div>
                    <div id="successMessage" class="text-success"></div>
                </div>
            </div>
         
           
             <div class="col-6 d-none"  id="b2b_limit">
                <div class="form-group">
                    <label class="control-label">Credit limit:</label>
                    <input type="number" class="form-control" name="credit_limit">
                </div>
            </div>
          
            <div class="col-6 d-none"  id="b2b_aadhaar">
              <div class="form-group">
               <label class="control-label">Aadhar No.:</label>
                 <input type="number" class="form-control" name="aadhar_no" id="aadhar_no" maxlength="12">
                  </div>
                </div>
                <div class="col-6"></div>
                <div class="col-6 d-none"  id="b2b_state">
                <div class="form-group">
                    <label class="control-label">State:</label>
                    <select class="form-control select2" style="width:100%;" name="state" id="state" onchange="fetch_city(this.value)">
                        <option value="">Select State</option>
                        <?php foreach ($states as $state) { ?>
                            <option value="<?php echo $state->id; ?>">
                                <?php echo $state->name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-6 d-none"  id="b2b_city">
                <div class="form-group">
                    <label class="control-label">City:</label>
                    <select class="form-control select2 city" style="width:100%;" name="city" id="city">

                    </select>
                </div>
            </div>
            <div class="col-6 d-none"  id="b2b_pincode">
                <div class="form-group">
                    <label class="control-label">Pincode:</label>
                    <input type="number" class="form-control" name="pincode">
                </div>
            </div>
               <div class="col-6 d-none"  id="b2b_address">
                <div class="form-group">
                    <label class="control-label">Address:</label>
                    <textarea  class="form-control" name="address"></textarea>
                </div>
            </div>

            <div class="col-12 d-none"  id="b2b_opening">
                <label class="control-label">Opening</label>
            </div>



            <div class="col-3 d-none"  id="b2b_dr_cr">
                <div class="form-group">
                    <label class="control-label">Dr./Cr.:</label>
                    <select class="form-control" name="dr_cr">
                        <option value="">-- Select --</option>
                        <option value="cr">Credit</option>
                        <option value="dr">Debit</option>
                    </select>
                </div>
            </div>
            <div class="col-3 d-none"  id="b2b_amount">
                <div class="form-group">
                    <label class="control-label">Amount:</label>
                    <input type="number" class="form-control" name="amount" step="0.01" pattern="\d+(\.\d{1,2})?">
                </div>
            </div>
            <div class="col-6 d-none" id="b2b_remark">
                <div class="form-group">
                    <label class="control-label">Remark:</label>
                    <input type="text" class="form-control" name="remark">
                </div>
            </div>



        </div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
            <button id="btnsubmit" type="submit" class="btn btn-danger waves-light"><i id="loader" class=""></i>Add</button>
            <!-- <input type="submit" class="btn btn-danger waves-light" type="submit" value="ADD" /> -->
        </div>

</form>

<script>
   var selectElement = document.getElementById('b2b_b2c');
    selectElement.addEventListener('change', function() {
        var selectedValue = selectElement.value;
        if (selectedValue === 'b2b') {
           $("#b2b_gst").removeClass('d-none');
           $("#b2b_code").removeClass('d-none');
           $("#b2b_limit").removeClass('d-none');
           $("#b2b_aadhaar").removeClass('d-none');
           $("#b2b_opening").removeClass('d-none');
           $("#b2b_dr_cr").removeClass('d-none');
           $("#b2b_amount").removeClass('d-none');
           $("#b2b_remark").removeClass('d-none');
           $("#b2b_a_mobile").removeClass('d-none');
           $("#b2b_state").removeClass('d-none');
           $("#b2b_city").removeClass('d-none');
           $("#b2b_pincode").removeClass('d-none');
           $("#b2b_address").removeClass('d-none');
        } else if (selectedValue === 'b2c') {
            $("#b2b_gst").addClass('d-none');
            $("#b2b_code").addClass('d-none');
            $("#b2b_limit").addClass('d-none');
            $("#b2b_aadhaar").addClass('d-none');
            $("#b2b_opening").addClass('d-none');
            $("#b2b_dr_cr").addClass('d-none');
            $("#b2b_amount").addClass('d-none');
            $("#b2b_remark").addClass('d-none');
           $("#b2b_a_mobile").addClass('d-none');
           $("#b2b_state").addClass('d-none');
           $("#b2b_city").addClass('d-none');
           $("#b2b_pincode").addClass('d-none');
           $("#b2b_address").addClass('d-none');
        }
    });
</script>