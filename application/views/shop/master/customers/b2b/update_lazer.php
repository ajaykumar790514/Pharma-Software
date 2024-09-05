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
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Received:</label>
                            <input type="number" class="form-control" value="<?=$value->credit;?>" name="credit">                          
                            </div>

                            <div class="form-group col-md-6">
                            <label class="control-label">Udhar / Borrow / Debit:</label>
                            <input type="number" class="form-control" value="<?=$value->debit;?>"  name="debit">                          
                            </div>

                            <div class="form-group col-md-6">
                            <label class="control-label">Select Transaction Date:</label>
                            <input type="date" class="form-control" name="date" value="<?=$value->tr_date;?>">                        
                            </div>

                            <div class="form-group col-md-6">
                            <label class="control-label">Remark :</label>
                            <input type="text" class="form-control" value="<?=$value->remark;?>" name="remark" >                        
                            </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions text-right">
            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-primary waves-light" ><i id="loader" class=""></i>Update</button>
            </div>
        </form>
        <!-- End: form -->

    </div>
</div>

