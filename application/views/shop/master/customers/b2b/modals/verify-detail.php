<style>
.table .table td, .table .table th {
    border: 1px solid #f3f1f1;
}
</style>
<div class="modal fade" id="customerDetailModal" tabindex="-1" role="dialog" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerDetailModalLabel">Pan Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('shop-customer-b2b/verify-detail') ?>" method="post" style="width: 100%;" onsubmit="return changeCustomerB2BStatus('pan-form')" id="pan-form">
                    <input type="hidden" id="customer_id" value="<?php echo $customer->customer_id ?>">
                    <input type="hidden" id="customer_detail_type" value="pan">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control" onchange="return toggleCommentBox(this.value);">
                            <option value="">Select Status</option>
                            <?php
                            $statusArr = ['VERIFIED', 'REJECTED', 'PENDING'];
                            foreach ($statusArr as $status) {
                                echo '<option value="' . $status . '" ' . ($status == $customer->status ? "selected" : "") . '>' . $status . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group <?php echo $customer->status == 'REJECTED' ? '' : 'd-none'; ?>" id="rejected_comment_div">
                        <label for="rejected_comment">Rejected Comment</label>
                        <textarea id="rejected_comment" class="form-control"><?php echo $customer->rejected_comment; ?></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
      </div>
    </div>
  </div>
</div>