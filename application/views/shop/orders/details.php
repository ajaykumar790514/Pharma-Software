<!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Orders Details</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="orders">Orders</a></li>
                            <li class="breadcrumb-item active">#<?php echo $orderData[0]['id'];?></li>
                        </ol>
                    </div><!--
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>THIS MONTH</small></h6>
                                    <h4 class="m-t-0 text-info">$58,356</h4></div>
                                <div class="spark-chart">
                                    <div id="monthchart"></div>
                                </div>
                            </div>
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>LAST MONTH</small></h6>
                                    <h4 class="m-t-0 text-primary">$48,356</h4></div>
                                <div class="spark-chart">
                                    <div id="lastmonthchart"></div>
                                </div>
                            </div>
                            <div class="">
                                <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
                            </div>
                        </div>
                    </div>-->
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ORDER SUMMARY: <strong>#<?php echo $orderData[0]['id'];?></strong></h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td style="border-top: none !important; padding: .75rem; vertical-align: bottom; border-bottom: 1px solid #dee2e6;">Order Added<br>Order Date</td>
                                                <td style="border-top: none !important; padding: .75rem; vertical-align: bottom; border-bottom: 1px solid #dee2e6;"><?php echo $orderData[0]['added']; ?> <br> <?php echo $orderData[0]['datetime']; ?></td>
                                            </tr>    
                                            <tr>
                                                <th>Total items</th>
                                                <th><?php if($orderItems!==FALSE){echo count($orderItems);}else{echo '0';}?></th>
                                            </tr>
                                            <tr>
                                                <th>Total Before Tax</th>
                                                <th><?= $shop_currency; ?> <?php echo $orderData[0]['total_value'] - $orderData[0]['tax']; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Tax</th>
                                                <th><?= $shop_currency; ?> <?php echo $orderData[0]['tax']; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            <tr>
                                                <td>Total Savings</td>
                                                <td><?= $shop_currency; ?> <?php echo $orderData[0]['total_savings']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <th><?= $shop_currency; ?> <?php echo $orderData[0]['total_value']; ?></th>
                                            </tr>
                                            <tr>
                                                <td>Remarks</td>
                                                <td><?php echo $orderData[0]['remark']; ?></td>
                                            </tr>
                                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><strong>Order Details</strong></h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Customer Name</th>
                                                <th><?php echo $orderData[0]['customer_name'].' (<span class="text-primary">'.$orderData[0]['customer_email'].'</span>)'; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Shop Name</th>
                                                <th><?php echo $orderData[0]['shop_name'].' (<span class="text-primary">'.$orderData[0]['shop_mobile'].'</span>)'; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <th><?php 
                                                        if($orderData[0]['address_id']===''){
                                                            echo $orderData[0]['random_address'];
                                                        }else{
                                                            echo $customerAddress[0]['house_no'].', '.$customerAddress[0]['apartment_name'].', '.$customerAddress[0]['address'];
                                                        }   
                                                    ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Payment Method</td>
                                                <td><?php echo $orderData[0]['payment_method']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Transaction Id</td>
                                                <td><?php echo $orderData[0]['razorpay_payment_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Bank Name</td>
                                                <td><?php echo $orderData[0]['bank_name']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><strong>Order Status</strong></h4>
                                <div class="table-responsive">
                                <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>
                                                    <select class="select" id="order-status" style="width: 100%" data-placeholder="Choose">
                                              <?php  $rs=$this->order_status_master_model->getCurrentStatus($orderData[0]['id']);
                                                             
                                                $orderStatusNew=$this->order_status_master_model->getRowsNew($rs->order)?>
                                                        <?php foreach($orderStatusNew as $orstatus):?>
                                                        <option value="<?=$orstatus->id;?>" ><?=$orstatus->name;?></option>
                                                    <?php  endforeach;?>
                                                    <?php
                                                    ?>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"><button class="btn btn-danger float-right" id="status-update">Update Status</button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                                <td>Assign Delivery Man</td>
                                                <td>
                                                    <?php 
                                                    $disabled = "disabled";
                                                    if(in_array($orderData[0]['status'], ['17','2','3','20'])  ){
                                                        $disabled = "";
                                                    } ?>
                                                    <select id="delivery_boy" class="select" <?=$disabled?>>
                                                        <option value=""> -- Select --</option>
                                                        <?php 
                                                        foreach ($delivery_boys as $dbvalue) {

                                                            $dbselected ='';
                                                            if (@$orderAssign[0]['delivery_boy_id']==$dbvalue->id) {
                                                                $dbselected = 'selected';
                                                            }
                                                            echo "<option value='".$dbvalue->id."' $dbselected >";
                                                            echo $dbvalue->full_name.' ('.$dbvalue->contact_number.')';
                                                            echo "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button class="btn btn-warning float-right assign-delivery" <?=$disabled?>  >Assign</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        // $('#order-status').change(function(e){
                        //     e.preventDefault();
                        //     if($('#order-status option:selected').val() === '2'){
                        //         Swal.fire({
                        //           title: 'Please enter the Invoice number for this order',
                        //           input: 'text',
                        //           inputAttributes: {
                        //             autocapitalize: 'off'
                        //           }, 
                        //           confirmButtonText: 'Update',
                        //           showLoaderOnConfirm: true,
                        //           preConfirm: (login) => {
                        //             return $.ajax({
                        //                     type:"POST",
                        //                     url: "orders/updateOrderBillNo",
                        //                     data: {id: "<?php echo $orderData[0]['id'];?>",bill_no:login},
                        //                 });
                        //           },
                        //           allowOutsideClick: () => !Swal.isLoading()
                        //         }).then((result) => {
                        //           if (result.isConfirmed) {
                        //             Swal.fire({
                        //               title: 'Invoice number updated',
                        //             })
                        //           }
                        //         })
                        //     }
                        // });
                        $('#status-update').click(function(e){
                                const swalWithBootstrapButtons = Swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success',
                                        cancelButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: true
                                })

                                swalWithBootstrapButtons.fire({
                                    title: 'Are you sure to update the status to '+$('#order-status option:selected').text()+' ?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, please!',
                                    cancelButtonText: 'No, cancel!',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        return $.ajax({
                                            type:"POST",
                                            url: "orders/updateOrderStatus",
                                            data: {item:{id: "<?php echo $orderData[0]['id'];?>",status:$('#order-status option:selected').val()}},
                                            'success': function (data) {

                                                swalWithBootstrapButtons.fire(
                                                    'Success!',
                                                    'Status has been updated.',
                                                    'success',
                                                ).then((result) => {
                                                    //$("#grid_table").jsGrid("loadData");
                                                    location.reload();
                                                })
                                            }
                                        });
                                    } else if (
                                        /* Read more about handling dismissals below */
                                        result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                        swalWithBootstrapButtons.fire(
                                        'Cancelled',
                                        'You\'ve cancelled the transaction',
                                        'error'
                                        )
                                    }
                                })
                            
                        });
                       $('.assign-delivery').click(function(e){
                                const swalWithBootstrapButtons = Swal.mixin({
                                    customClass: {
                                        confirmButton: 'btn btn-success',
                                        cancelButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: true
                                })

                                swalWithBootstrapButtons.fire({
                                    title: 'Are you sure to assign this order to '+$('#delivery_boy option:selected').text()+' ?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, please!',
                                    cancelButtonText: 'No, cancel!',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        return $.ajax({
                                            type:"POST",
                                            url: "orders/updateDeliveryBoy",
                                            data: {item:{id: "<?php echo $orderData[0]['id'];?>",delivery_boy:$('#delivery_boy option:selected').val()}},
                                            'success': function (data) {
                                                console.log(data);
                                                swalWithBootstrapButtons.fire(
                                                    'Success!',
                                                    'Status has been updated.',
                                                    'success',
                                                ).then((result) => {
                                                    //$("#grid_table").jsGrid("loadData");
                                                    // location.reload();
                                                })
                                            }
                                        });
                                    } else if (
                                        /* Read more about handling dismissals below */
                                        result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                        swalWithBootstrapButtons.fire(
                                        'Cancelled',
                                        'You\'ve cancelled the transaction',
                                        'error'
                                        )
                                    }
                                })
                            
                        });
                    </script>
                    <div class="col-lg-8">
                        <div class="card">
                            <!-- .left-right-aside-column-->
                            <div class="card-body">
                                <h4 class="card-title">Order Items</h4>                        
                                <div class="contact-page-aside">
                                    <div class="table-responsive">
                                        <table id="demo-foo-addrow" class="table m-t-30 table-hover no-wrap contact-list" data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Product Name</th>
                                                    <th>Unit</th>
                                                    <th>Rate</th>
                                                    <th>Qty</th>
                                                    <th>Amount</th>
                                                    <th>Tax</th>
                                                    <th>Offer Applied (if any)</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $total=0;
                                                    if($orderItems!==FALSE){
                                                        $count=1;
                                                        foreach($orderItems as $items){
                                                            echo '<tr>';
                                                            echo '<td>'.$count.'</td>';
                                                            echo '<td><img src="'.$items['img'].'" style="width:100px; max-height:100px;">&nbsp;&nbsp;'.$items['product_name'].' <strong>('.str_pad($items['product_code'], 6, '0', STR_PAD_LEFT).')</strong></td>';
                                                            echo '<td>'.$items['unit_value'].' '.$items['unit_type'].'</td>';
                                                            echo '<td>'.$shop_currency.' '.$items['price_per_unit'].'</td>';
                                                            echo '<td>'.$items['qty'].'</td>';
                                                            echo '<td>'.$shop_currency.' '.$items['total_price'].'</td>';
                                                            echo '<td>'.$items['tax_value'].'%</td>';
                                                            echo '<td>'.$items['offer_applied'].'</td>';
                                                            echo '<td>'.$shop_currency.' '.$items['total_price'].'</td>';
                                                            echo '</tr>';
                                                            $count++;
                                                            $total+=$items['total_price'];
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                    <!-- .left-aside-column-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->