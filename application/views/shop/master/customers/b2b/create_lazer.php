<script type="text/javascript">
$(document).ready(function() {
    $(".validate-form").validate({
        rules: {
            remark:"required",
            date:{
                required:true,
            }
        },
        messages: {
            remark:"Please enter remark",
            date: {
                required : "Please select date.",
            }
        }
    }); 
});
</script>
<div class="card-content collapse show">
    <div class="card-body">
                                    
        <!-- form --><?//=$action_url;?>
        <form class="form submithisabkitab validate-form reload-page submit reload-tb" action="<?=$action_url?>" method="POST" enctype="multipart/form-data">
      
            <div class="form-body w-100">
                <div class="col-12 p-0">
                    <div class="card">
                        <div class="card-body">
                          <input type="hidden" name="customer_id" value="<?=$customer_id;?>">
                          <div class="row">
                            <div class="form-group col-md-6">
                            <label class="control-label">Received:</label>
                            <input type="number" class="form-control" placeholder="Enter received amount" name="credit">                          
                            </div>
                           
                            <div class="form-group col-md-6">
                            <label class="control-label">Udhar / Borrow / Debit:</label>
                            <input type="number" class="form-control" placeholder="Enter udhar / borrow / debit amount" name="debit">                          
                            </div>

                            <div class="form-group col-md-6">
                            <label class="control-label">Select Transaction Date:</label>
                            <input type="date" class="form-control" name="date" >                        
                            </div>

                            <div class="form-group col-md-6">
                            <label class="control-label">Remark :</label>
                            <input type="text" class="form-control" placeholder="Please Enter Remark" name="remark" >                        
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions text-right">
            <button type="reset" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-primary waves-light" ><i id="loader" class=""></i>Add</button>
            </div>
        </form>
        <!-- End: form -->

    </div>
</div>

