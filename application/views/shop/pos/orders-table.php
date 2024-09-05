
<style type="text/css">
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 4px;
    height: 37px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #99abb4;
    line-height: 36px;
    font-weight: 400;
}
</style>
<!--============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">Dashboard</h3>
                      <?php echo $breadcrumb;?>
                    </div>
                    <!-- <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            
                            <div class="">
                                <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10">
                                    <i style="animation-play-state: paused !important;" class="mdi mdi-filter text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- .left-right-aside-column-->
                            <div class="card-body">
                                <h4 class="card-title">All Orders</h4>   
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="example-date-input" class="control-label">From date</label>
                                            <input class="form-control form-control-sm" type="date" value="<?php if(isset($_SESSION['order_table_filters']['from_date'])){ echo $_SESSION['order_table_filters']['from_date'];} ?>" id="from-date">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                        <label for="example-date-input" class="control-label">To date</label>
                                            <input class="form-control form-control-sm" type="date" value="<?php if(isset($_SESSION['order_table_filters']['to_date'])){ echo $_SESSION['order_table_filters']['to_date']; }?>" id="end-date">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label class="control-label">Order Status:</label>
                                            <select class="form-control form-control-sm" style="width:100%;" id="order-status" data-placeholder="Choose">
                                                <option value="">Select</option>
                                                <?php if(isset($orderStatus) && $orderStatus!==FALSE){ ?>
                                                    <?php foreach ($orderStatus as $status) { ?>
                                                    <option value="<?php echo $status['id']; ?>" <?php if(isset($_SESSION['order_table_filters']['status_ids']) &&  $status['id'] == $_SESSION['order_table_filters']['status_ids'][0]) {echo "selected"; } ?>>
                                                        <?php echo $status['name']; ?>
                                                    </option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                        <label for="example-date-input" class="control-label">Bill No. </label>
                                            <input class="form-control form-control-sm" type="text" value="<?php if(isset($_SESSION['order_table_filters']['orderid'])){ echo $_SESSION['order_table_filters']['orderid']; }?>" id="orderid">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label class="control-label">Customer Name:</label>
                                            <!-- <input class="form-control form-control-sm" type="text" value="<?php if(isset($_SESSION['order_table_filters']['customer_mobile'])){ echo $_SESSION['order_table_filters']['customer_mobile']; }?>" id="customer-mobile"> -->

                                           <select class="select2s form-control form-control-sm" style="width:100%" name="customer_mobile" id="customer-mobile">
                                            <option value="">-- Select Customer --</option>
                                              <?php if(@$customers) {
                                                foreach ($customers as $cusrow) { ?>
                                                    <option value="<?=$cusrow->mobile?>"
                                                    <?php if(@$_SESSION['order_table_filters']['customer_mobile'] == $cusrow->mobile) {echo "selected"; } ?>

                                                        >
                                                        <?=$cusrow->fname.''.$cusrow->lname?>
                                                    </option>
                                                <?php } } ?>
                                           </select>
                                        </div>
                                    </div>

                                    <!-- <div class="col-2"> -->
                                        <!-- <div class="form-group">
                                            <label class="control-label">Payment Method:</label>
                                            <select class="form-control form-control-sm" style="width:100%;" id="payment-method" data-placeholder="Choose">
                                                <option value="">Select</option>
                                                <option value="cod" <?php if(isset($_SESSION['order_table_filters']['payment_method'])){if($_SESSION['order_table_filters']['payment_method'] == 'cod'){echo "selected";}
                                                    }?>>COD</option>
                                                    <option value="online" <?php if(isset($_SESSION['order_table_filters']['payment_method'])){if($_SESSION['order_table_filters']['payment_method'] == 'online'){echo "selected";}
                                                    }?>>Online</option>
                                                
                                            </select>
                                        </div> -->
                                    <!-- </div> -->
                                    
                                    <div class="col-2 "  style="margin-top:17px">
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3 mr-3 mt-3 clear-filter" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>
                                        <button class="btn btn-primary btn-sm apply-filter">Apply Filters</button>
                                    </div>
                                </div>                     
                                <div class="contact-page-aside">
                                    <div id="table-responsive">
                                        
                                    </div>
                                    <!-- .left-aside-column-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
                <script>
                    /*$(document).ready(function() {
                        $('.printPopUp').click(function(e) {*/
                    function printPopUp(id){
                        if (id === 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'You cannot print bill for this order!'
                            })
                        }else{
                            var newwindow = window.open('pos_orders/print/bill/'+id, '', 'height=1000,width=950');
                            if (window.focus) {
                                newwindow.focus();
                            }
                            return false;
                        }
                    }        
                            /*e.preventDefault();
                            
                        });
                    });*/

                    // var product_list = function () {
                    //     var tmp = null;
                    //     $.ajax({
                    //         'async': false,
                    //         'type': "POST",
                    //         'global': false,
                    //         'url': "orders/getOrderStatus",
                    //         'success': function (data) {
                    //             tmp = data;
                    //             var ele = document.getElementById('product_list_add_new');
                    //             $.each(JSON.parse(data), function(index,value){
                    //                 ele.innerHTML = ele.innerHTML + '<option value="' + value.id + '">' + value.name + '</option>';
                    //             });
                    //         }
                    //     });
                    //     return tmp;
                    // }();

                    $("#table-responsive").jsGrid({
                        filtering: true,
                        width: "100%",
                        height:"auto", 
                        sorting:true,
                        paging:true,
                        pageLoading: true,
                        autoload:true,
                        pageSize:10,
                        pageButtonCount: 5,
                        controller: {
                            loadData :  function(filter){
                                var d = $.Deferred();
                                $.ajax({
                                    type:"GET",
                                    url: "pos_orders/getOrders",
                                    data: {filter:filter,shop_id:"<?php echo $user->id;?>"},
                                    dataType: "json"
                                }).done(function(response) {
                                    d.resolve(response);
                                });
                                return d.promise();
                            },
                           
                        },
                        fields: [
                            {
                                name:"id",
                                type:"hidden",
                                css:"hide"
                            },
                            // {
                            //     name: "order_id",
                            //     title: "ID",
                            //     itemTemplate: function(val ) { 
                            //         // return $("<a>").attr("href", 'orders/'+val.row_id).text(val.order);
                            //         return $("<a>").attr({
                            //         href: 'pos_orders/'+val.row_id,
                            //         target: '_blank',
                            //         }).text(val.order);
                                    
                            //     },
                            //     align: "center",
                            //     width: 120
                            // },
                            {
                                name: "orderid",
                                title: "Bill No.",
                                itemTemplate: function(val ) {
                                    // return $("<p>").text(val);
                                    return $("<a>").attr({
                                    href: 'pos_orders/details/'+val.row_id,
                                    target: '_blank',
                                    }).text(val.orderid);     
                                },
                                align: "center",
                                width: 120
                                 
                            },
                            // {
                            //     name: "invoice_no",
                            //     title: "Bill No",
                            //     itemTemplate: function(val ) {
                            //         return $("<p>").text(val);
                            //     },
                            //     align: "center",
                            //     width: 120
                            // },
                            // {
                            //     name: "shop_name",
                            //     title: "Shop Name",
                            //     itemTemplate: function(val ) {
                            //         return $("<p>").text(val);
                            //     },
                            //     align: "center",
                            //     width: 120
                            // },
                            {
                                name: "customer_name",
                                title: "Customer Name",
                                // type: "text",
                                // itemTemplate: function(val ) {
                                //     return $("<p>").text(val);
                                // },
                                align: "center",
                                width: 120
                            },
                            {
                                name: "datetime",
                                title: "Order Date",
                                itemTemplate: function(val ) {
                                    return $("<p>").text(val);
                                },
                                align: "center",
                                width: 120
                            },
                            // {
                            //     name: "delivery_slot",
                            //     title: "Delivery Slot",
                            //     itemTemplate: function(val ) {
                            //         return $("<p>").text(val);
                            //     },
                            //     align: "center",
                            //     width: 120
                            // },
                            {
                                name: "total_value",
                                title: "Total Value",
                                itemTemplate: function(val ) {
                                    return $("<p>").text(val);
                                },
                                align: "center",
                                width: 120
                            },
                            // {
                            //     name: "total_savings",
                            //     title: "Total Savings",
                            //     itemTemplate: function(val ) {
                            //         return $("<p>").text(val);
                            //     },
                            //     align: "center",
                            //     width: 120
                            // },
                            // {
                            //     name: "payment_method",
                            //     title: "Payment Method",
                            //     itemTemplate: function(val ) {
                            //         return $("<p>").text(val);
                            //     },
                            //     align: "center",
                            //     width: 120
                            // },
                            {
                                name: "status_name",
                                title: "Status",
                                // type: "select",
                                // width: 100,
                                // items:  JSON.parse(product_list),
                                // valueField: "id",
                                // textField: "name",
                                // validate:"required"
                            },
                            {
                                name: "due_date",
                                title: "Due Date",
                                itemTemplate: function(val ) {
                                    return $("<p>").text(val);    
                                },
                                align: "center",
                                width: 120
                                 
                            },
                            {
                                name: "print_bill",
                                title: "Print Bill",
                                itemTemplate: function(val ) {
                                    return $("<p>").attr("onclick","printPopUp("+val.row_id+")").append('<i class="mdi mdi-printer text-warning" style="font-size:30px;"></i>');
                                },
                                align: "center",
                                width: 120
                            }
                        ],
                    });

setTimeout(function() {
    $("#table-responsive table").addClass('table table-striped table-sm');
}, 50);

</script>


                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar" style="">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Advanced Filters <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li class="float-right"  style="padding-bottom:20px;">
                                        <button class="btn btn-warning clear-filter">Clear All</button>
                                        <button class="btn btn-primary apply-filter">Apply Filters</button>
                                </li>
                                <li><b>Filter By Date</b></li>
                                <li>
                                    <div class="form-group">
                                        <label for="example-date-input" class="col-2 col-form-label">From</label>
                                        <div class="col-10">
                                            <input class="form-control form-control-sm" type="date" value="<?php if(isset($_SESSION['order_table_filters']['from_date'])){ echo $_SESSION['order_table_filters']['from_date'];} ?>" id="from-date">
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-group">
                                        <label for="example-date-input" class="col-2 col-form-label">To</label>
                                        <div class="col-10">
                                            <input class="form-control form-control-sm" type="date" value="<?php if(isset($_SESSION['order_table_filters']['to_date'])){ echo $_SESSION['order_table_filters']['to_date']; }?>" id="end-date">
                                        </div>
                                    </div>
                                </li>
                                <li class="d-block m-t-30"><b>Order Status</b>
                            </li>
                                <li style="width: 100%">
                                    <select class="select" id="order-status" style="width: 100%" data-placeholder="Choose">
                                        <option value="">Select Order Status</option>
                                    <?php
                                        if(isset($orderStatus) && $orderStatus!==FALSE){ ?>
                                           
                                            <?php foreach ($orderStatus as $status) { ?>
                                                <option value="<?php echo $status['id']; ?>" <?php if(isset($_SESSION['order_table_filters']['status_ids']) &&  $status['id'] == $_SESSION['order_table_filters']['status_ids'][0]) {echo "selected"; } ?>>
                                                    <?php echo $status['name']; ?>
                                                </option>
                                                <?php } } ?>
                                    </select>
                                </li>
                                <li class="d-block m-t-30"><b>Payment Method</b></li>
                                <li style="width: 100%">
                                    <select class="select" id="payment-method" style="width: 100%" data-placeholder="Choose">
                                    <option value="">Select</option>
                                    <option value="cod" <?php if(isset($_SESSION['order_table_filters']['payment_method'])){if($_SESSION['order_table_filters']['payment_method'] == 'cod'){echo "selected";}

                                    }?>>COD</option>
                                    <option value="online" <?php if(isset($_SESSION['order_table_filters']['payment_method'])){if($_SESSION['order_table_filters']['payment_method'] == 'online'){echo "selected";}

                                    }?>>Online</option>
                                        <!-- <?php
                                            if($orderPaymentStatusMaster!==FALSE){
                                                foreach($orderPaymentStatusMaster as $paymentmode){
                                                    echo '<option value="'.$paymentmode['name'].'">'.$paymentmode['name'].'</option>';
                                                }
                                            }
                                        ?> -->
                                   </select>
                                </li>
                                <li>
                                    <div class="form-group">
                                        <label for="example-date-input" class="col-2 col-form-label"><b>Customer Mobile</b></label>
                                        <div class="col-10">
                                            <input class="form-control form-control-sm" type="text" value="<?php if(isset($_SESSION['order_table_filters']['customer_mobile'])){ echo $_SESSION['order_table_filters']['customer_mobile']; }?>" id="customer-mobile">
                                        </div>
                                    </div>
                                </li>
                                <li class="float-right"  style="padding-top:20px;">
                                    <button class="btn btn-warning clear-filter">Clear All</button>
                                    <button class="btn btn-primary apply-filter">Apply Filters</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <script>
                    $('.apply-filter').click(function(e){
                        $.ajax({
                                type:"POST",
                                url: "pos_orders/setOrderSessionFilters",
                                data: {start_date: $('#from-date').val(),end_date: $('#end-date').val(),status: $('#order-status option:selected').val(),payment_method:$('#payment-method option:selected').val(),customer_mobile: $('#customer-mobile').val(),orderid:$('#orderid').val()},
                                'success': function (data) {
                                    $("#table-responsive").jsGrid("loadData");
                                }
                            });
                    });
                    $('.clear-filter').click(function(e){
                        $('#from-date').val('');
                        $('#end-date').val('');
                        $('#order-status').val('');
                        $('#payment-method').val('');
                        $('#customer-mobile').val('').change();
                        $('#orderid').val('');
                        $.ajax({
                                type:"POST",
                                url: "pos_orders/clearOrderSessionFilters",
                                'success': function (data) {
                                    $("#table-responsive").jsGrid("loadData");
                                }
                            });
                    });
                </script>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ==============================================================