<style>
.table .table td, .table .table th {
    border: 1px solid #f3f1f1;
}
</style>
<div class="modal fade" id="customerDetailModal" tabindex="-1" role="dialog" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerDetailModalLabel">Credit  Limit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-responsive table-secondary">
                    <tr>
                        <th>Customer Name</th>
                        <td><?php echo $customer->fname ?? ''; ?>  <?php echo $customer->mname ?? ''; ?>  <?php echo $customer->lname ?? ''; ?></td>
                    </tr>
                    <tr>
                        <th>Credit Limit</th>
                        <td><?php echo $customer->credit_limit ?? ''; ?></td>
                    </tr>
                </table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('customers-b2b/set_credit_limit') ?>" method="post" style="width: 100%;" onsubmit="return changeCustomerB2BCreditLimit('pan-form')" id="pan-form">
                    <input type="hidden" id="customer_id" value="<?php echo $customer->customer_id ?>">
                    <div class="form-group">
                        <label for="status">Current Credit Limit</label>
                        <input name="credit_limit" id="credit_limit"type="text" class="form-control" value="<?php echo $customer->credit_limit ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Set Credit Limit</button>
                    </div>
                </form>
      </div>
    </div>
  </div>
</div>