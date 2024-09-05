
<style type="text/css">
    body{
        font-size: 14px!important;
    }
</style>
<!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">Dashboard</h3>
                        <?php echo $breadcrumb;?>
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
                                                <th>₹ <?php echo $orderData[0]['total_value'] - $orderData[0]['tax']; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Tax</th>
                                                <th>₹ <?php echo $orderData[0]['tax']; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Total</td>
                                                <td>₹ <?php echo $orderData[0]['total_value']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Total Savings</td>
                                                <td>₹ <?php echo $orderData[0]['total_savings']; ?></td>
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
                                                <th><?php echo $orderData[0]['customer_name'].' (<span class="text-primary">'.$orderData[0]['customer_mobile'].'</span>)'; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Shop Name</th>
                                                <th><?php echo $orderData[0]['shop_name'].' (<span class="text-primary">'.$orderData[0]['shop_mobile'].'</span>)'; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <th><?php 
                                                        if($orderData[0]['same_as_billing']== 1){
                                                            echo $orderData[0]['random_address'];
                                                        }
                                                        else{
                                                            echo $orderData[0]['shipping_address'];
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
                                                    <?php
                                                        if(isset($orderStatus) && $orderStatus!==FALSE){
                                                            if($orderData[0]['status']==='4' || $orderData[0]['status']==='6' || $orderData[0]['status']==='1'){
                                                                if($orderData[0]['status']==='4'){
                                                                    echo '<option value="4">Completed</option>';
                                                                }else if($orderData[0]['status']==='1') {
                                                                    echo '<option value="1">Pending Payment</option>';
                                                                }else{
                                                                    echo '<option value="6">Cancelled</option>';
                                                                }
                                                            }else{
                                                                echo '<option value="">Select Order Status</option>';
                                                                foreach($orderStatus as $status){
                                                                    if($status['order'] >= $orderStatusData[0]['order']){
                                                                        echo '<option value="'.$status['id'].'" ';
                                                                        if($status['id']===$orderData[0]['status']){
                                                                            echo 'selected';
                                                                        }
                                                                        echo '>'.$status['name'].'</option>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"><button class="btn btn-danger float-right" id="status-update">Update Status</button></th>
                                            </tr>
                                        </thead>
                                        <!--<tbody>
                                        <tr>
                                                <td>Assign Delivery Man</td>
                                                <td>
                                                    <select class="select" id="order-delivery" style="width: 100%" data-placeholder="Choose">
                                                        <option value="">Select Order Status</option>
                                                    <?php
                                                    /*
                                                        if(isset($orderStatus) && $orderStatus!==FALSE){
                                                            foreach($orderStatus as $status){
                                                                echo '<option value="'.$status['id'].'" ';
                                                                if($status['id']===$orderData[0]['status']){
                                                                    echo 'selected';
                                                                }
                                                                echo '>'.$status['name'].'</option>';
                                                            }
                                                        }*/
                                                    ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button class="btn btn-warning float-right assign-delivery">Assign</button></td>
                                            </tr>
                                        </tbody>-->
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
                                            url: "pos_orders/updateOrderStatus",
                                            data: {item:{id: "<?php echo $orderData[0]['id'];?>",status:$('#order-status option:selected').val()}},
                                            'success': function (data) {
                                                // console.log(data);
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
                            alert('ese');
                        });
                    </script>
                    <div class="col-lg-8">
                        <div class="card">
                            <!-- .left-right-aside-column-->
                            <div class="card-body">
                                <h4 class="card-title">Order Items</h4>                        
                                <div class="contact-page-aside">
                                    <div class="table-responsive">
                                        <table id="demo-foo-addrow" class="table m-t-30 table-hover no-wrap contact-list table-sm" data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Product Name</th>
                                                    <th>Unit</th>
                                                    <th int>MRP</th>
                                                    <th>Discount</th>
                                                    <th int>Rate</th>
                                                    <th int>Qty</th>
                                                    <!-- <th>Amount</th> -->
                                                    <th>Tax</th>
                                                    <th int >Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $total=0;
                                                    if($orderItems!==FALSE){
                                                        $count=1;
                                                        foreach($orderItems as $items){

                                                            $dt1 = (@$items['offer_applied']) ? ($items['discount_type']==0) ? 'percentage' : 'fixed' : '';
                                                            $dt2 = (@$items['offer_applied2']) ? ($items['discount_type2']==0) ? 'percentage' : 'fixed' : '';

                                                            echo '<tr>';
                                                            echo '<td>'.$count.'</td>';
                                                            echo '<td>'.$items['product_name'].'<br> <strong>('.str_pad($items['product_code'], 6, '0', STR_PAD_LEFT).')</strong></td>';
                                                            echo '<td>'.$items['unit_value'].' '.$items['unit_type'].'</td>';
                                                            echo '<td int>₹ '.$items['price_per_unit'].'</td>';
                                                            echo '<td '.$dt1.' >'.$items['offer_applied'].'</td>';
                                                            echo '<td int>₹ '.$items['mrp'].'</td>';
                                                            echo '<td int>'.$items['qty'].'</td>';
                                                            // echo '<td>₹ '.$items['total_price'].'</td>';
                                                            echo '<td>'.$items['tax_value'].'%</td>';
                                                            echo '<td int >₹ '.$items['total_price'].'</td>';
                                                            
                                                            if (@$items['is_returned']==0) {
                                                            echo "<td> <a href='javascript:void(0)' return-item data-id='".$items['id']."' class='btn btn-warning btn-xs'>Return </a> </td>";
                                                            }
                                                            else{
                                                               echo "<td> Returned </td>"; 
                                                            }
                                                            
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

<script type="text/javascript">
    // const swalWithBootstrapButtons = Swal.mixin({
    //                                 customClass: {
    //                                     confirmButton: 'btn btn-success',
    //                                     cancelButton: 'btn btn-danger'
    //                                 },
    //                                 buttonsStyling: true
    //                             })
    // $('body').on('click','[return-item]',function(){
    //     var id = $(this).attr('data-id');
    //     $.ajax({
    //         url:'<?=base_url()?>pos-return-items',
    //         data:{id:id},
    //         dataType:'JSON',
    //         type:'POST',
    //         success:function(data){
    //             swalWithBootstrapButtons.fire(
    //                 data.res,
    //                 data.msg,
    //                 data.res,
    //             ).then((result) => {

    //                 //$("#grid_table").jsGrid("loadData");
    //                 location.reload();
    //             })
    //         }
    //     })
    //     // alert(id);
    // })

 $('body').on('click','[return-item]',function(){
    var id = $(this).attr('data-id');
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure to return this item',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, please!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            return $.ajax({
            url:'<?=base_url()?>pos-return-items',
            data:{id:id},
            dataType:'JSON',
            type:'POST',
            success:function(data){
                swalWithBootstrapButtons.fire(
                    data.res,
                    data.msg,
                    data.res,
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
            'You\'ve cancelled',
            'error'
            )
        }
    })
})
</script>