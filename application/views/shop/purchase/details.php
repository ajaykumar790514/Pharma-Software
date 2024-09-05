<!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">Dashboard</h3>
                     <?php echo $breadcrumb;?>
                    </div>
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
                                <h4 class="card-title">PURCHASE ORDER NO: <strong>#<?php echo $orderData[0]['purchase_order_no'];?></strong></h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                                <td style="border-top: none !important; padding: .75rem; vertical-align: bottom; border-bottom: 1px solid #dee2e6;"><!--Order Added<br>-->Order Date</td>
                                                <td style="border-top: none !important; padding: .75rem; vertical-align: bottom; border-bottom: 1px solid #dee2e6;"><?php echo ($orderData[0]['purchase_order_date']); ?><?php //echo uk_time($orderData[0]['added']); ?> <br> <?php //echo $orderData[0]['datetime']; ?></td>
                                            </tr>    
                                            <tr>
                                                <th>Total items</th>
                                                <th><?php if($orderItems!==FALSE){echo count($orderItems);}else{echo '0';}?></th>
                                            </tr>
                                            <tr>
                                                <th>Gross Total</th>
                                                <th><?= $shop_currency; ?> <?php echo bcdiv(($orderData[0]['gross_total']), 1, 2); ?></th>
                                            </tr>
                                            <tr>
                                                <th>Flat Discount ( <span class="text-primary"><?=$orderData[0]['flat_discount'];?> %</span> )</th>
                                                <th><?= $shop_currency; ?> <?php echo bcdiv(($orderData[0]['flat_discount_value']), 1, 2); ?></th>
                                            </tr>
                                            <tr>
                                                <th>Total Before Tax</th>
                                                <th><?= $shop_currency; ?> <?php echo bcdiv(($orderData[0]['total_amount'] - $orderData[0]['total_tax']), 1, 2); ?></th>
                                            </tr>
                                            <tr>
                                                <th>Tax</th>
                                                <th><?= $shop_currency; ?> <?php echo  bcdiv(($orderData[0]['total_tax']), 1, 2); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th style="font-size: 1.3rem">Total</th>
                                                <th style="font-size: 1.3rem"><?= $shop_currency; ?> <?php echo bcdiv(($orderData[0]['total_amount']), 1, 2);  ?></th>
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
                                                <th>Supplier Name</th>
                                                <th><?php echo $orderData[0]['customer_name'].' (<span class="text-primary">'.$orderData[0]['customer_email'].'</span>)'; ?></th>
                                            </tr>
                                             <tr>
                                                <th>Supplier Number</th>
                                                <th><?php echo '<span class="text-primary">'.$orderData[0]['booking_contact'].'</span>'; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Shop Name</th>
                                                <th><?php echo $orderData[0]['shop_name'].' (<span class="text-primary">'.$orderData[0]['shop_mobile'].'</span>)'; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                                <td>Shipping Date</td>
                                                <td><?php echo date('d-m-Y',strtotime($orderData[0]['shipping_date'])); ?></td>
                                            </tr>
                                             <tr>
                                                <td>Shipping Note</td>
                                                <td><?php echo $orderData[0]['shipping_note']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Remark</td>
                                                <td><?php echo $orderData[0]['remark']; ?></td>
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
                                              <?php  $rs=$this->purchase_status_master_model->getCurrentStatus($orderData[0]['id']);
                                                             
                                                $orderStatusNew=$this->purchase_status_master_model->getRowsNew($rs->order)?>
                                                        <?php foreach($orderStatusNew as $orstatus):?>
                                                        <option value="<?=$orstatus->id;?>" ><?=$orstatus->name;?></option>
                                                    <?php  endforeach;?>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"><button class="btn btn-danger float-right" id="status-update">Update Status</button></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
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
                                            url: "purchase/updateOrderStatus",
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
                    </script>
                    <div class="col-lg-8">
                        <div class="card">
                            <!-- .left-right-aside-column-->
                            <div class="card-body">
                                <h4 class="card-title">Purchase Items</h4>                        
                                <div class="contact-page-aside">
                                    <div class="table-responsive">
                                        <table id="demo-foo-addrow" class="table m-t-30 table-hover no-wrap contact-list" data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Product Name</th>
                                                    <th>Rate</th>
                                                    <th>Qty</th>
                                                    <!--<th>Discount (if any)</th>-->
                                                    <th>Amount</th>
                                                    <th>Tax</th>
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
                                                            echo '<td><img src="'.$items['img'].'" style="width:100px; max-height:100px;">&nbsp;&nbsp;'?><?php  echo (strlen($items['product_name']) > 20) ? wordwrap($items['product_name'], 20, "<br />\n", true) : $items['product_name'];?><?php echo' <strong>('.str_pad($items['product_code'], 6, '0', STR_PAD_LEFT).')</strong><br>'?>     
                                                          <?php 
                                                                echo '<span class="text-danger" style="width:100%">'.$items['item_props_value'].'</span>  ';
                                                            ?>
                                                           <?php '</td>';
                                                            echo '<td>'.$shop_currency.' '.bcdiv(($items['price_per_unit']), 1, 2).'</td>';
                                                            echo '<td>'.$items['qty'].'</td>';
                                                            ?>
                                                            <?php  //echo '<td>'.$items['discount'].'%'.' ( '.$shop_currency.' '.bcdiv( $items['discount_value'], 1, 2).' )</td>';?>
                                                            <?php 
                                                            echo '<td>'.$shop_currency.' '.bcdiv(($items['total_price']-$items['tax_value']), 1, 2).'</td>';
                                                            echo '<td>'.$shop_currency.' '.$items['tax_value'].'</td>';
                                                               echo '<td><b>'.$shop_currency.' '.bcdiv( $items['total_price'], 1, 2).'</td>';
                                                                echo '</b></tr>';
                                                            $count++;
                                                           
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