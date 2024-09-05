<script type="text/javascript">
$(document).ready(function() {
    $(".needs-validation").validate({
        rules: {
           
            description:"required",                                               
            name:"required"                                    
           
        },
    }); 
});
</script>
<?php foreach($value as $row): ?> 
<form class="ajaxsubmit needs-validation reload-page" action="<?=$action_url?>" method="post" enctype= multipart/form-data>

    <div class="row">
    
        <div class="col-12">
            <div class="form-group">
                <label class="control-label">Title:</label>
                <input type="text" class="form-control" name="name" value="<?=$row->title ?>">
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="control-label">Description:</label>
                <textarea id="mytextarea" cols="92" rows="5" name="description"><?=$row->description ?></textarea>
            </div>
        </div>
    </div>

<div class="modal-footer">
    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-primary waves-light" ><i id="loader" class=""></i>Update</button>
    <!-- <input id="btnsubmit" type="submit" class="btn btn-primary waves-light" type="submit" value="UPDATE"> -->
</div>

</form>
<?php endforeach; ?>
<script src="<?=base_url()?>/public/assets/plugins/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#mytextarea'
  });
</script>