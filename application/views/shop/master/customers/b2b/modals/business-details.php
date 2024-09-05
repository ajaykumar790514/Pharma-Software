<style>
    .table .table td,
    .table .table th {
        border: 1px solid #f3f1f1;
    }
</style>
<div class="modal fade" id="customerDetailModal" tabindex="-1" role="dialog" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerDetailModalLabel">Business Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-responsive table-secondary">
                            <tr>
                                <th>GST</th>
                                <td><?php echo $customer->gst ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Address 1</th>
                                <td><?php echo $customer->address_line1 ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Address 2</th>
                                <td><?php echo $customer->address_line2 ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td><?php echo $customer->city ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td><?php echo $customer->state ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td><?php echo $customer->pincode ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td><?php echo $customer->country ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Business Name</th>
                                <td><?php echo $customer->business_name ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Shop Name</th>
                                <td><?php echo $customer->shop_name ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Shop Photo</th>
                                <td><img src="<?=base_url('assets/photo/'.$customer->shop_photo);?>" alt="" width="70" height="70" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                    <?php // echo $customer->shop_photo ?? ''; ?></td>
                                        <!-- Image 1 -->
                        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->shop_photo;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                            </tr>
                            <tr>
                                <th>Opening Time</th>
                                <td><?php echo $customer->opening_time ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Closing Time</th>
                                <td><?php echo $customer->closing_time ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Lunch Start Time</th>
                                <td><?php echo $customer->lunch_start_time ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Lunch End Time</th>
                                <td><?php echo $customer->lunch_end_time ?? ''; ?></td>
                            </tr>
                            <tr>
                                <th>Opening Days</th>
                                <td>
                                    <table class="table">
                                        <tr>
                                            <th>Monday</th>
                                            <th>Tuesday</th>
                                            <th>Wednesday</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo empty($customer->mon) ? 'Closed' : ucfirst($customer->mon); ?></td>
                                            <td><?php echo empty($customer->tue) ? 'Closed' : ucfirst($customer->tue); ?></td>
                                            <td><?php echo empty($customer->wed) ? 'Closed' : ucfirst($customer->wed); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Thursday</th>
                                            <th>Friday</th>
                                            <th>Saturday</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo empty($customer->thu) ? 'Closed' : ucfirst($customer->thu); ?></td>
                                            <td><?php echo empty($customer->fri) ? 'Closed' : ucfirst($customer->fri); ?></td>
                                            <td><?php echo empty($customer->sat) ? 'Closed' : ucfirst($customer->sat); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Sunday</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo empty($customer->sun) ? 'Closed' : ucfirst($customer->sun); ?></td>
                                        </tr>
                                    </table>
                                </td>
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
                <form action="<?php echo base_url('shop-customer-b2b/change_status') ?>" method="post" style="width: 100%;" onsubmit="return changeCustomerB2BStatus('business-form')" id="business-form">
                    <input type="hidden" id="customer_id" value="<?php echo $customer->customer_id ?>">
                    <input type="hidden" id="customer_detail_type" value="business">
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