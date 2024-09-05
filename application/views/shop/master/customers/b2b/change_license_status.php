
<form class="ajaxsubmit needs-validation reload-page" action="<?=$action_url?>" method="post" enctype= multipart/form-data>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="control-label">Status:</label>
                <select  class="form-control" name="status" id="status" onchange="toggleRejectedComment(this)" >
                <option value="PENDING"  <?php if($status->status=='PENDING'){echo 'selected';} ;?> >PENDING</option>
                 <option value="VERIDIED" <?php if($status->status=='VERIDIED'){echo 'selected';} ;?>>VERIDIED</option>
                 <option value="REJECTED" <?php if($status->status=='REJECTED'){echo 'selected';} ;?>>REJECTED</option>
                </select>
            </div>
        </div>
        <div class="col-12 <?php if($status->status == 'REJECTED') echo ''; else echo 'd-none'; ?>" id="rejectedCommentDiv">
                <div class="form-group">
                    <label class="control-label">Rejected Comment:</label>
                    <textarea class="form-control"  name="rejected_comment" rows="4" cols="50"><?=@$status->rejected_comment;?></textarea>
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-danger waves-light" ><i id="loader" class=""></i>Change Status</button>
    <!-- <input type="submit" class="btn btn-danger waves-light" value="ADD" /> -->
</div>

</form>
            
<script>
    function toggleRejectedComment(selectElement) {
        var rejectedCommentDiv = document.getElementById('rejectedCommentDiv');
        if (selectElement.value === 'REJECTED') {
            rejectedCommentDiv.classList.remove('d-none');
        } else {
            rejectedCommentDiv.classList.add('d-none');
        }
    }
</script>
