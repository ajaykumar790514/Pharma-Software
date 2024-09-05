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
            fname:"required",
            mobile: {
                required:true,
                minlength:10,
                maxlength:10,
                number: true,
            },
            alternate_mobile: {
                minlength:10,
                maxlength:10,
                number: true,
            },
            state:"required",
            city:"required",
            address:"required",                                                                                        
            pincode:"required",                 
            gstin: {
                required:true,
                remote:"<?=$remote?>null/gstin"
            },
            vendor_code: {
                required:true,
                remote:"<?=$remote?>null/vendor_code"
            },
        },
        messages: {
            vendor_code: {
                remote : "Supplier Code Already Exists!!"
            },
            gstin: {
                remote : "GSTIN Already Exists!!"
            },
        }
    }); 
});
</script>
<form class="add_supplier needs-validation reload-tb" action="<?=$action_url?>" method="post">
<div class="modal-body">
        <div class="row">
        <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Supplier Name:</label>
                        <input type="text" value="<?=@$Name;?>" class="form-control" name="fname">
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
                        <label class="control-label">Mobile:</label>
                        <input type="number" class="form-control" name="mobile">
                    </div>
            </div>
            <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Alternate Mobile:</label>
                        <input type="number" class="form-control" name="alternate_mobile">
                    </div>
            </div>
         
            <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">GSTIN:</label>
                        <input type="text" class="form-control" name="gstin" id="gstinInput" oninput="validateInput()">
                    <div id="errorMessage" class="text-danger"></div>
                    <div id="successMessage" class="text-success"></div>
                    </div>
            </div>
            <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Supplier Code:</label>
                        <input type="text" class="form-control" name="vendor_code">
                    </div>
            </div>
            <div class="col-6">
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
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">City:</label>
                    <select class="form-control select2 city" style="width:100%;" name="city" id="city">
                    
                    </select>
                </div>
            </div>
            <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Pincode:</label>
                        <input type="number" class="form-control" name="pincode">
                    </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Address:</label>
                    <textarea  class="form-control" name="address"></textarea>
                </div>
            </div>
            <div class="col-12">
                <label class="control-label">Opening</label>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Dr./Cr.:</label>
                    <select class="form-control" name="dr_cr">
                        <option value="">-- Select --</option>
                        <option value="cr">Credit</option>
                        <option value="dr">Debit</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Amount:</label>
                    <input type="number" class="form-control" name="amount" step="0.01" pattern="\d+(\.\d{1,2})?">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Remark:</label>
                    <input type="text" class="form-control" name="remark">
                </div>
            </div>
        
</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-danger waves-light" ><i id="loader" class=""></i>Add</button>
    <!-- <input type="submit" class="btn btn-danger waves-light" type="submit" value="ADD" /> -->
</div>

</form>

