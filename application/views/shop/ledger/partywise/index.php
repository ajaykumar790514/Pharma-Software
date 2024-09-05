<link rel="stylesheet" type="text/css" media="print" href="public/assets/css/ledger-prints.css" />
<style type="text/css">
    #datatable table th{
    border-top: 1px solid black;
    border-bottom: 1px solid black;
}
</style>
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
<!-- Row -->
<div class="row">
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 p-hide">
                        <div class="d-flex flex-wrap">
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <h3 class="card-title" id="test">Partywise Report</h3>
                                <h6 class="card-subtitle"></h6>
                            </div>

                           

                            
                        </div>
                    </div>


                    <div class="col-12 p-hide">
                        <form action="<?=$tb_url?>" class="tb-filter" method="post">
                        <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label class="control-label">From date:</label>
                                <input type="date" class="form-control form-control-sm" name="from_date" id="from_date" >
                            </div>
                            <div id="msg"></div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="control-label">To date:</label>
                                <input type="date" class="form-control form-control-sm" name="to_date" id="to_date" >
                            </div>
                        </div>

                        <div class="col-3" id="vendor-div">
                            <div class="form-group">
                                <input type="hidden" name="Id" id="Id">
                                <fieldset class="vendor">
                                    <input type='checkbox' name='is_Vendor' id="is_Vendor" />
                                    <label for="is_Vendor" class="control-label">Supplier:</label>
                                </fieldset>
                                <label id="this_Vendor" style="display: none;" class="control-label">Supplier:</label>
                                <select class="form-control form-control-sm" style="width:100%;" name="business_id" id="VendorId" disabled>
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($vendor as $value) { ?>
                                        <option value="<?php echo $value->sup_id; ?>">
                                            <?php echo $value->name; ?>(<?php echo $value->vendor_code; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3" id="customer-div">
                            <div class="form-group">
                                <fieldset class="customer">
                                    <input type='checkbox' name='is_Customer' id="is_Customer" />
                                    <label for="is_Customer" class="control-label">Customer:</label>
                                </fieldset>
                                <label id="this_Customer" style="display: none;" class="control-label">Customer:</label>
                                <select class="form-control form-control-sm" style="width:100%;" name="business_id" id="CustomerId" disabled>
                                    <option value="">Select Customer</option>
                                    <?php foreach ($customer as $value) { ?>
                                        <option value="<?php echo $value->cus_id; ?>">
                                            <?php echo $value->customer_name?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-2">
                            <div class="form-group">
                                <fieldset class="accounts">
                                    <input type='checkbox' name='is_accounts' id="is_accounts" />
                                    <label for="is_accounts" class="control-label">Expense Accounts:</label>
                                </fieldset>
                                <label id="thi_accounts" style="display: none;" class="control-label">Expense Accounts:</label>
                                <select class="form-control" style="width:100%;" name="business_id" id="accounts" disabled>
                                    <option value="">Select Expense Accounts</option>
                                    <?php foreach ($accounts as $value) { ?>
                                        <option value="<?php echo $value->id; ?>">
                                            <?php echo $value->title ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> -->

                        <div class="col-2" style="margin-top: 33px;">
                            <div class="form-group">
                                <button type="submit" class="float-right btn btn-sm btn-success">Filter</button>
                                <a href="javascript:void(0)" class="float-right btn btn-primary btn-sm mb-3 mr-3" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>
                            </div>
                        </div>
                        </div>
                       
                    </form>
                </div>




                    <div class="col-12" id="tb">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->

<!-- //###### ANKIT MAIN CONTENT  ######// -->
<input type="hidden" name="tb" value="<?=$tb_url?>">
<script>
           $('#reset-data').click(function(){
                location.reload();
            })
    </script>
<script type="text/javascript">
    
    $('#is_Vendor').click(function(e) {
        if ($('#is_Vendor').is(':checked')) {
            $("#VendorId").removeAttr('disabled');
            $(".customer, .accounts").hide();
            $("#this_Customer, #thi_accounts").show();
        } else {
            $("#VendorId").attr('disabled', true).val('');
            $(".customer, .accounts").show();
            $("#this_Customer, #thi_accounts").hide();
        }
    });

    $('#is_Customer').click(function(e) {
        if ($('#is_Customer').is(':checked')) {
            $("#CustomerId").removeAttr('disabled');
            $(".vendor, .accounts").hide();
            $("#this_Vendor, #thi_accounts").show();
        } else {
            $("#CustomerId").attr('disabled', true).val('');
            $(".vendor, .accounts").show();
            $("#this_Vendor, #thi_accounts").hide();
        }
    });

    $('#is_accounts').click(function(e) {
        if ($('#is_accounts').is(':checked')) {
            $("#accounts").removeAttr('disabled');
            $(".vendor, .customer").hide();
            $("#this_Vendor, #this_Customer").show();
        } else {
            $("#accounts").attr('disabled', true).val('');
            $(".vendor, .customer").show();
            $("#this_Vendor, #this_Customer").hide();
        }
    });
</script>

<?php $this->load->view('shop/reports/filters_js'); ?>


<!-- //###### ANKIT MAIN CONTENT  ######// -->

