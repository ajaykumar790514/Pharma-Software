<script type="text/javascript">
$(document).ready(function() {
    $(".needs-validation").validate({
        rules: {   
            title:"required",
            description:"required",
            discount_type:"required",
            value:"required",
            expiry_date:"required",
            start_date:"required",
            business_id:"required",
            offer_created_by:"required"
        }
    }); 
});
</script>
<form class="ajaxsubmit needs-validation update-form reload-page" action="<?=$action_url?>" method="post" enctype= multipart/form-data>
<div class="modal-body">
<div class="row">
        
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Title</label>
                <input type="text" class="form-control" name="title" value="<?= $value->title; ?>">
            </div>
        </div>
        
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Discount Type:</label>
                <select class="form-control" style="width:100%;" name="discount_type">
                    <option value="">--Select--</option>
                    <option value="0" <?php if($value->discount_type == '0') {
                                                                            echo "selected";
                                                                        } ?>>Fixed</option>
                    <option value="1" <?php if($value->discount_type == '1') {
                                                                            echo "selected";
                                                                        } ?>>Percentage</option>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Value:</label>
                <input type="number" class="form-control" name="value" value="<?= $value->value; ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Image:</label>
                <input type="file" name="photo" class="form-control"
size="55550" accept=".png, .jpg, .jpeg, .gif"  id="productImage">
            </div>
            <?php if(!empty($value->photo)) { ?>
                <img src="<?php echo IMGS_URL.$value->photo;?>" alt="<?php echo $value->title; ?>" height="50">
                <?php } ?>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" min="<?= date('Y-m-d'); ?>" value="<?= $value->start_date; ?>" onchange="validate_date()">
                <div id="msg"></div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Expiry Date:</label>
                <input type="date" name="expiry_date" id="expiry_date" class="form-control" min="<?= date('Y-m-d'); ?>" value="<?= $value->expiry_date; ?>" onchange="validate_date()">
            </div>
        </div>
        <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Business:</label>
                                                                            <select class="form-control" style="width:100%;" name="business_id" id="business_id" onchange="fetch_shop(this.value)" required>
                                                                            <option value="">Select Business</option>
                                                                        <?php foreach ($business as $busi) { ?>
                                                                        <option value="<?php echo $busi->id; ?>" <?php if($busi->id == $value->business_id) {
                                                                            echo "selected";
                                                                        } ?>>
                                                                            <?php echo $busi->title; ?>
                                                                        </option>
                                                                            <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Shop:</label>
                                                                            <select class="form-control shop_id" style="width:100%;" name="offer_created_by" id="shop_id" required>
                                                                        <option value="<?php echo $value->offer_created_by; ?>">
                                                                    <?php echo $value->shop_name; ?>
                                                                    </option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
        <div class="col-12">
            <div class="form-group">
                <label class="control-label">Description:</label>
                <textarea cols="92" rows="5" class="form-control" name="description"><?= $value->description; ?></textarea>
            </div>
        </div>
        
        
    </div>

</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
    <input type="submit" id="btnsubmit" class="btn btn-danger waves-light" type="submit" value="Update" />
</div>

</form>
<script type="text/javascript">
   function fetch_shop(business_id)
   {
    $.ajax({
        url: "<?php echo base_url('offers-coupons/fetch_shop'); ?>",
        method: "POST",
        data: {
            business_id:business_id
        },
        success: function(data){
            $(".shop_id").html(data);
        },
    });
   }
      // Function to check file size
      function checkFileSize() {
        var files = $('#productImage')[0].files;
        var maxSize = 100 * 1024; // 100 KB
        var submitButton = $('#btnsubmit');
        
        for (var i = 0; i < files.length; i++) {
            if (files[i].size > maxSize) {
                toastr.error("Offers image should be less than 100 KB.");
                submitButton.prop('disabled', true);
                $('#productImage').val('');
                return;
            }
        }
        submitButton.prop('disabled', false);
    }
    // Bind file size check to the file input field
    $('#productImage').on('change', checkFileSize);
</script>
