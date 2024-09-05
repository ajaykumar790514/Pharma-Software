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
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-responsive table-secondary">
                    <tr>
                        <th>Document Type</th>
                        <td><?php echo $customer->title ?? ''; ?></td>
                    </tr>
                    <tr>
                        <th>Document</th>
                        <td><img src="<?=base_url('assets/photo/'.$customer->ownership_doc);?>" alt="" width="50" height="50" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                        <img src="<?=base_url('assets/photo/'.$customer->ownership_doc2);?>" alt="" width="50" height="50" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                        <img src="<?=base_url('assets/photo/'.$customer->ownership_doc3);?>" alt="" width="50" height="50" data-bs-toggle="modal" data-bs-target="#exampleModal3">
                        <img src="<?=base_url('assets/photo/'.$customer->ownership_doc4);?>" alt="" width="50" height="50" data-bs-toggle="modal" data-bs-target="#exampleModal4">
                       </td>
                        <!-- Image 1 -->
                        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->ownership_doc;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                           <!-- Image 1 -->
                        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->ownership_doc2;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                                                            <!-- Image 1 -->
                        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->ownership_doc3;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                                                            <!-- Image 1 -->
                        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->ownership_doc4;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                       
                    </tr>
                    <tr>
                        <th>Name On Pan</th>
                        <td><?php echo $customer->name_on_pan ?? ''; ?></td>
                    </tr>
                    <tr>
                        <th>Pan Document</th>
                        <td><img src="<?=base_url('assets/photo/'.$customer->pan_doc);?>" alt="" width="50" height="50" data-bs-toggle="modal" data-bs-target="#exampleModalpan"></td>
                        <div class="modal fade" id="exampleModalpan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->pan_doc;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $customer->status ?? ''; ?></td>
                    </tr>
                    <tr>
                        <th>Added Date</th>
                        <td><?php echo date('d M Y h:i A', strtotime($customer->added)); ?></td>
                    </tr>
                    <tr>
                        <th>Updated Date</th>
                        <td><?php echo date('d M Y h:i A', strtotime($customer->updated)); ?></td>
                    </tr>
                </table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('shop-customer-b2b/change_status') ?>" method="post" style="width: 100%;" onsubmit="return changeCustomerB2BStatus('pan-form')" id="pan-form">
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
                    <div class="form-group <?php echo $customer->status == 'REJECTED' ? '' : 'd-none'; ?>"" id="rejected_comment_div">
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