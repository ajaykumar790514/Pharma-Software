<div class="row page-titles">
<div class="col-md-5 col-8 align-self-center">
<h3 class="text-themecolor">Dashboard</h3>
      <?php echo $breadcrumb?>
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
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <h3 class="card-title" id="Cash-Register" style="margin-left: -16px;">Cash Register</h3>
                                <h6 class="card-subtitle"></h6>
                            </div>
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <button class="float-right btn btn-sm btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" onclick="showModal1()" data-whatever="Add Cash Data">Add Cash Data</button>
                                <a href="javascript:void(0)" class="float-right btn btn-primary btn-sm mb-3 mr-3" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">From Date:</label>
                            <input type="date" class="form-control form-control-sm" name="from_date" id="from_date">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">To Date:</label>
                            <input type="date" onchange="loadtb()" class="form-control form-control-sm" name="to_date" id="to_date">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <fieldset class="vendors">
                                <input type='checkbox' onclick="loadtb(1)" name='is_Vendor' id="i_Vendor" />
                                <label for="i_Vendor" class="control-label">Supplier:</label>
                            </fieldset>
                            <label id="thi_Vendor" style="display: none;" class="control-label">Supplier:</label>
                            <select class="form-control form-control-sm" style="width:100%;" onchange="loadtb()" name="Vendord" id="Vendord" disabled>
                                <option value="">Select Supplier</option>
                                <?php foreach ($vendor as $value) { ?>
                                    <option value="<?php echo $value->sup_id; ?>">
                                        <?php echo $value->name; ?>(<?php echo $value->vendor_code; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <fieldset class="customers">
                                <input type='checkbox' name='is_Customer' onclick="loadtb(2)" id="i_Customer" />
                                <label for="i_Customer" class="control-label">Customer:</label>
                            </fieldset>
                            <label id="thi_Customer" style="display: none;" class="control-label form-control-sm">Customer:</label>
                            <select class="form-control" style="width:100%;" onchange="loadtb()" name="Customerd" id="Customerd" disabled>
                                <option value="">Select Customer</option>
                                <?php foreach ($customer as $value) { ?>
                                    <option value="<?php echo $value->cus_id; ?>">
                                        <?php echo $value->customer_name; ?> 
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-2">
                        <div class="form-group">
                            <fieldset class="accounts">
                                <input type='checkbox' name='is_accounts' onclick="loadtb(3)" id="i_accounts" />
                                <label for="i_accounts" class="control-label">Expense Accounts:</label>
                            </fieldset>
                            <label id="thi_accounts" style="display: none;" class="control-label">Expense Accounts:</label>
                            <select class="form-control" style="width:100%;" onchange="loadtb()" name="business_id" id="accounts" disabled>
                                <option value="">Select Expense Accounts</option>
                                <?php foreach ($accounts as $value) { ?>
                                    <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->title ?> 
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> -->
                </div>
                <div class="col-12 p-0" id="tb">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
<div class="modal fade text-left" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel21"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('body').on('change', '.cash-save-box input[type=checkbox]', function(event) {
                        var _this = $(this);
                        var _is_checked = event.target.checked ? 'checked' : 'unchecked';

                        $('.cash-save-box input[type=checkbox]').each(function() {
                            $(this).removeAttr('checked');
                            $(this).parents('.form-group').find('select').prop('disabled', true);
                            $(this).parents('.form-group').find('[tmp]').show();
                            $(this).parents('.form-group').find('fieldset').hide();
                        });

                        if (_is_checked === 'checked') {
                            setTimeout(function() {
                                _this.attr('checked', true);
                                _this.parents('.form-group').find('select').prop('disabled', false);
                                _this.parents('.form-group').find('[tmp]').hide();
                                _this.parents('.form-group').find('fieldset').show();
                            }, 500);
                        } else {
                            $('.cash-save-box input[type=checkbox]').each(function() {
                                $(this).parents('.form-group').find('select').prop('disabled', true);
                                $(this).parents('.form-group').find('[tmp]').hide();
                                $(this).parents('.form-group').find('fieldset').show();
                            });
                            _this.parents('.form-group').find('select').val('');
                        }
                    });
                });
            </script>

            <div class="modal-body">
                <div class="row cash-save-box">
                    <div class="col-6" id="vendor-div">
                        <div class="form-group">
                            <input type="hidden" name="Id" id="Id">
                            <fieldset class="vendor">
                                <input type='checkbox' name='is_Vendor' id="is_Vendor" />
                                <label for="is_Vendor" class="control-label">Supplier:</label>
                            </fieldset>
                            <label tmp id="this_Vendor" style="display: none;" class="control-label">Supplier:</label>
                            <select class="form-control" style="width:100%;" name="business_id" id="VendorId" disabled>
                                <option value="">Select Supplier</option>
                                <?php foreach ($vendor as $value) { ?>
                                    <option value="<?php echo $value->sup_id; ?>">
                                        <?php echo $value->name; ?>(<?php echo $value->vendor_code; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6" id="customer-div">
                        <div class="form-group">
                            <fieldset class="customer">
                                <input type='checkbox' name='is_Customer' id="is_Customer" />
                                <label for="is_Customer" class="control-label">Customer:</label>
                            </fieldset>
                            <label tmp id="this_Customer" style="display: none;" class="control-label">Customer:</label>
                            <select class="form-control" style="width:100%;" name="business_id" id="CustomerId" disabled>
                                <option value="">Select Customer</option>
                                <?php foreach ($customer as $value) { ?>
                                    <option value="<?php echo $value->cus_id; ?>">
                                        <?php echo $value->customer_name; ?> 
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6" id="bank-div">
                        <div class="form-group">
                            <fieldset class="vendor">
                                <input type='checkbox' name='is_Bank' id="is_Bank" />
                                <label for="is_Bank" class="control-label">Bank Account:</label>
                            </fieldset>
                            <label tmp id="this_Bank" style="display: none;" class="control-label">Bank Account:</label>
                            <select class="form-control" style="width:100%;" name="bank_id" id="BankId" disabled>
                                <option value="">Select Supplier</option>
                                <?php foreach ($bank_accounts as $key => $brow) { ?>
                                    <option value="<?php echo $brow->id; ?>"> <?php echo $brow->account_no; ?> (<?php echo $brow->branch_name; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-6" id="accounts-div">
                        <div class="form-group">
                            <fieldset class="account">
                                <input type='checkbox' name='is_Accounts' id="is_Accounts" />
                                <label for="is_Accounts" class="control-label">Expense Accounts:</label>
                            </fieldset>
                            <label tmp id="this_Accounts" style="display: none;" class="control-label">Expense Accounts:</label>
                            <select class="form-control" style="width:100%;" name="AccountsId" id="AccountsId" disabled>
                                <option value="">Select Expense Accounts</option>
                                <?php foreach ($accounts as $value) { ?>
                                    <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->title?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">Reference No:</label>
                            <input type="text" name="refno"  class="form-control" value="" id="refno">
                            <input type="text" name="refno" style="display:none;"  class="form-control" value="" id="editrefno">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">Amount:</label>
                            <input type="number" class="form-control" name="Amount" id="Amount" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">Payment Date:</label>
                            <input type="date" class="form-control" name="PaymentDate" id="PaymentDate">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">Dr./Cr.:</label>
                            <select class="form-control" name="dr_cr" id="dr_cr">
                                <option value="">-- Select --</option>
                                <option value="dr">Receipt</option>
                                <option value="cr">Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="control-label">Narration :</label>
                            <input type="text" class="form-control" name="narration" id="narration">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger waves-light" id="AddBtn" onclick="CashSubmit1()" value="">ADD</button>
                    <button type="button" class="btn btn-danger waves-light" style="display:none;" id="UpdateBtn" onclick="Cashupdate()">UPDATE</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
           $('#reset-data').click(function(){
                location.reload();
            })
    </script>

<script type="text/javascript">
    $(document).ready(function() {
        // console.log("ready!");
    });

    function showModal1() {
        $('#showModal .modal-body').show();
        $("#Amount").val('');
        $("#refno").val('');
        $("#VendorId").val('');
        $("#CustomerId").val('');
        $("#PaymentDate").val('');
        $("#myModalLabel21").text("Add Cash Data");
        $("#UpdateBtn").hide();
        $("#AddBtn").show();
        $("#editrefno").hide();
        $("#refno").show();
        $("#customer-div").show();
        $("#vendor-div").show();
    }

    // $('#is_Vendor').click(function(e) {
    //     ;
    //     if ($('#is_Vendor').is(':checked') == true) {
    //         $(".customer").hide();
    //         $("#this_Customer").show();
    //         $("#VendorId").removeAttr('disabled', false);
    //     } else {
    //         $("#VendorId").attr('disabled', true);
    //         $("#VendorId").val('');
    //         $(".customer").show();
    //         $("#this_Customer").hide();
    //     }
    // })

    // $('#is_Customer').click(function(e) {
    //     ;
    //     if ($('#is_Customer').is(':checked') == true) {
    //         $("#CustomerId").removeAttr('disabled', false);
    //         $("#this_Vendor").show();
    //         $(".vendor").hide();
    //     } else {
    //         $("#CustomerId").attr('disabled', true);
    //         $("#CustomerId").val('');
    //         $(".vendor").show();
    //         $("#this_Vendor").hide();
    //     }
    // })

    $('#i_Vendor').click(function(e) {
        if ($('#i_Vendor').is(':checked') == true) {
            $(".customers").hide();
            $("#thi_Customer").show();
            $(".accounts").hide();
            $("#thi_accounts").show();
            $("#Vendord").removeAttr('disabled', false);
        } else {
            $("#Vendord").attr('disabled', true);
            $("#Vendord").val('');
            $(".customers").show();
            $(".accounts").show();
            $("#thi_accounts").hide();
            $("#thi_Customer").hide();
        }
    })
   
    $('#i_Customer').click(function(e) {
        if ($('#i_Customer').is(':checked') == true) {
            $("#Customerd").removeAttr('disabled', false);
            $("#thi_Vendor").show();
            $(".vendors").hide();
            $(".accounts").hide();
            $("#thi_accounts").show();
        } else {
            $("#Customerd").attr('disabled', true);
            $("#Customerd").val('');
            $(".vendors").show();
            $(".accounts").show();
            $("#thi_accounts").hide();
            $("#thi_Vendor").hide();
        }
    })
    $('#i_accounts').click(function(e) {
        if ($('#i_accounts').is(':checked') == true) {
            $("#accounts").removeAttr('disabled', false);
            $("#thi_Vendor").show();
            $(".vendors").hide();
            $(".customers").hide();
            $("#thi_Customer").show();
        } else {
            $("#accounts").attr('disabled', true);
            $("#accounts").val('');
            $(".vendors").show();
            $(".accounts").show();
            $("#thi_accounts").hide();
            $("#thi_Vendor").hide();
            $(".customers").show();
            $("#thi_Customer").hide();
        }
    })
    $(document).on('click', '.pag-link', function(event) {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        var search = $('#tb-search').val();
        $.post($(this).attr('href'), {
                search: search
            })
            .done(function(data) {
                ;
                $('#tb').html(data);
            })
        return false;
    })
    $('#reset-page').click(function(e) {
        location.reload();
    });
    var checkref = '';
    var editcheckref = '';

    function loadtb(id) {
        $("#tb").html('<div class="text-center"><img src="loader.gif"></div>');
        var Vid = ''
        var Cid = ''
        var vendorid = $("#Vendord").val();
        var customerid = $("#Customerd").val();
        var accounts = $("#accounts").val();
        if ($('#i_Vendor').is(':checked') == true) {
            Vid = id
        } else {
            Vid = null;
        }
        if ($('#i_Customer').is(':checked') == true) {
            Cid = id
        } else {
            Cid = null;
        }
        if ($('#i_accounts').is(':checked') == true) {
            aid = id
        } else {
            aid = null;
        }
        $.ajax({
            url: "<?php echo base_url('cash-register/tb') ?>",
            method: "POST",
            data: {
                'fromdate': $("#from_date").val(),
                'todate': $("#to_date").val(),
                'vendor': Vid,
                'customer': Cid,
                'vendorid': vendorid,
                'customerid': customerid,
                'account': aid,
                'accountid': accounts,
            },
            success: function(data) {
                // console.log(data);
                $("#tb").html(data);
            }
        });
    }


    function showmodel(cashid) {
        $("#customer-div").show();
        $("#vendor-div").show();
        $.ajax({
            url: "<?php echo base_url('cash-register/edit'); ?>",
            method: "POST",
            data: {
                cashid: cashid,
            },
            success: function(data) {
                data_array = $.parseJSON(data)
                console.log(data_array);
                $("#Id").val(data_array[0].id);
                // $('.cash-save-box .form-group').find('select').prop('disabled',true);
                // $('.cash-save-box .form-group').find('[tmp]').show();
                // $('.cash-save-box .form-group').find('fieldset').hide();
                // if (data_array[0].txn_type == 'supplier') {
                //     $("#VendorId").val(data_array[0].customer_id);
                //     $("#customer-div").hide();
                // } else {
                //     $("#CustomerId").val(data_array[0].customer_id);
                //     $("#vendor-div").hide();
                // }
              if (data_array[0].user_type === 'supplier' && (data_array[0].txn_type === '2' || data_array[0].txn_type === '1')) {
                    $("#VendorId").removeAttr('disabled');
                    $("#VendorId").val(data_array[0].customer_id);
                    $("#customer-div").hide();
                    $("#accounts-div").hide();
                    $("#vendor-div").show();
                    $("#bank-div").hide();
                } else if (data_array[0].user_type === 'customer' && (data_array[0].txn_type === '2' || data_array[0].txn_type === '1')) {
                    $("#CustomerId").removeAttr('disabled');
                    $("#CustomerId").val(data_array[0].customer_id);
                    $("#vendor-div").hide();
                    $("#customer-div").show();
                    $("#accounts-div").hide();
                    $("#bank-div").hide();
                } else if (data_array[0].bank_account !== null) {
                    $("#BankId").removeAttr('disabled');
                    $("#BankId").val(data_array[0].bank_account);
                    $("#accounts-div").hide();
                    $("#vendor-div").hide();
                    $("#bank-div").show();
                    $("#customer-div").hide();
                } else {
                    $("#AccountsId").removeAttr('disabled');
                    $("#AccountsId").val(data_array[0].customer_id);
                    $("#accounts-div").show();
                    $("#bank-div").hide();
                    $("#vendor-div").hide();
                    $("#customer-div").hide();
                }

                // $("#VendorId").val(data_array[0].customer_id);
                // $("#CustomerId").val(data_array[0].customer_id);
                // $("#BankId").val(data_array[0].bank_account);
                // $("#AccountsId").val(data_array[0].customer_id);
                $("#Amount").val(data_array[0].amount);
                $("#editrefno").val(data_array[0].reference_no);
                $("#PaymentDate").val(data_array[0].PaymentDate);
                var type = data_array[0].type;
                var $select = $("#dr_cr");

                // Clear existing options except for the first one
                $select.find('option:not(:first)').remove();

                // Add options dynamically based on data_array[0].type
                if (type === 'Rcpt') {
                    // Append "Receipt" option if it doesn't exist
                    if ($select.find('option[value="dr"]').length === 0) {
                        $select.append('<option value="dr">Receipt</option>');
                        $select.append('<option value="cr">Payment</option>');
                    }
                    // Select the "Receipt" option
                    $select.val('dr');
                } else if (type === 'Pymt') {
                    // Append "Payment" option if it doesn't exist
                    if ($select.find('option[value="cr"]').length === 0) {
                        $select.append('<option value="cr">Payment</option>');
                        $select.append('<option value="dr">Receipt</option>');
                    }
                    // Select the "Payment" option
                    $select.val('cr');
                } else {
                    console.warn('Unknown type: ' + type);
                }
                // $("#dr_cr").val(data_array[0].dr_cr);
                $("#narration").val(data_array[0].narration);
                $("#editrefno").show();
                $("#refno").hide();
                $("#myModalLabel21").text("Update Cash Data");
                $("#UpdateBtn").show();
                $("#AddBtn").hide();
            }
        })
    }

    function resetForm() {
    $('#Id').val('');
    $('#refno').val('');
    $('#editrefno').val('');
    $('#Amount').val('');
    $('#PaymentDate').val('');
    $('#dr_cr').val('');
    $('#narration').val('');
    $("#VendorId").val('');
    $("#AccountsId").val('');
    $("#CustomerId").val('');
}

    function CashSubmit1() {
        var refval = $("#refno").val();
        $.ajax({
            url: "<?php echo base_url('cash-register/checkref_no') ?>",
            method: "POST",
            data: {
                "refval": refval,
            },
            success: function(data) {
                
                if (data == 1) {
                    checkref = 1
                    toastr.warning('Reference No Already Exists', 'Warning', 'positionclass:toast-bottom-full-width');
                    return false;
                }else{
                    var customerId = '';
                    var txntype = '';
                    // console.log($("#VendorId").val());
                    if ($("#VendorId").val() != '') {
                        customerId = $("#VendorId").val();
                        txntype = 1;
                    } 
                    else if ($("#CustomerId").val() != '') {
                        customerId = $("#CustomerId").val();
                        txntype = 2;
                    } else if ($("#BankId").val() != ''){
                        customerId = $("#BankId").val();
                        txntype = 3;
                    }else{
                        customerId = $("#AccountsId").val();
                        txntype = 8;
                    }
                    if ($("#VendorId").val() == '' && $("#CustomerId").val() == '' && $("#BankId").val() == ''&& $("#AccountsId").val() == '') {
                        toastr.warning('Please Select Vendor, Customer or Bank or Expense Accounts', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#Amount").val() == '') {
                        toastr.warning('Please Enter Amount', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#refno").val() == '') {
                        toastr.warning('Please Enter Reference No.', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#PaymentDate").val() == '') {
                        toastr.warning('Please Enter Payment Date.', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#dr_cr").val() == '') {
                        toastr.warning('Select Dr./Cr.', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    $.ajax({
                        url: "<?php echo base_url('cash-register/save') ?>",
                        method: "POST",
                        data: {
                            "Amount": $("#Amount").val(),
                            "refno": $("#refno").val(),
                            "customerId": customerId,
                            "txntype": txntype,
                            'PaymentDate': $("#PaymentDate").val(),
                            'dr_cr': $("#dr_cr").val(),
                            'narration': $("#narration").val()
                        },
                        beforeSend: function() {
                            $('#AddBtn').prop("disabled", true);
                            $('#AddBtn').html(
                                '<i class="fa fa-spinner fa-spin"></i> ADD'
                            );
                        },
                        complete: function() {
                            $('#AddBtn').prop("disabled", false);
                            $('#AddBtn').html('ADD');
                        },
                        success: function(data) {
                            if (data == 1) {
                                resetForm();
                                toastr.success('Saved Succesfully.', 'Success', 'positionclass:toast-bottom-full-width');
                                // location.reload();
                            }
                        }
                    });
       
                }
            }
        });
    
    }

    function Cashupdate() {
        $.ajax({
            url: "<?php echo base_url('cash-register/editcheckref_no') ?>",
            method: "POST",
            data: {
                "refval": $("#editrefno").val(),
                "id": $("#Id").val(),
            },
            success: function(data) {
                
                if (data == 1) {
                    editcheckref = 1
                    toastr.warning('Reference No Already Exists', 'Warning', 'positionclass:toast-bottom-full-width');
                    return false;
                }else{
                    var customerId = '';
                    var txntype = '';
                    if ($("#VendorId").val() != '') {
                        customerId = $("#VendorId").val();
                        txntype = 1;
                    } 
                    else if ($("#CustomerId").val() != '') {
                        customerId = $("#CustomerId").val();
                        txntype = 2;
                    } else if ($("#BankId").val() != ''){
                        customerId = $("#BankId").val();
                        txntype = 3;
                    }else{
                        customerId = $("#AccountsId").val();
                        txntype = 8;
                    }
                    // alert(customerId);
                    if ($("#VendorId").val() == '' && $("#CustomerId").val() == '' && $("#BankId").val() == ''&& $("#AccountsId").val() == '') {
                        toastr.warning('Please Select Vendor, Customer or Bank or Expense Accounts', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#Amount").val() == '') {
                        toastr.warning('Please Enter Amount', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#editrefno").val() == '') {
                        toastr.warning('Please Enter Reference No.', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#PaymentDate").val() == '') {
                        toastr.warning('Please Enter Payment Date.', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    if ($("#dr_cr").val() == '') {
                        toastr.warning('Select Dr./Cr.', 'Warning', 'positionclass:toast-bottom-full-width');
                        return;
                    }
                    var currentdate = new Date();
                    var datetime = (currentdate.getFullYear()) + "-" +
                        (currentdate.getMonth() + 1) + "-" +
                        currentdate.getDate() + " " +
                        currentdate.getHours() + ":" +
                        currentdate.getMinutes() + ":" +
                        currentdate.getSeconds();

                    $.ajax({
                        url: "<?php echo base_url('cash-register/update') ?>",
                        method: "POST",
                        data: {
                            "Id": $("#Id").val(),
                            "Amount": $("#Amount").val(),
                            "refno": $("#editrefno").val(),
                            "customerId": customerId,
                            "txntype": txntype,
                            'PaymentDate': $("#PaymentDate").val(),
                            'dr_cr': $("#dr_cr").val(),
                            'narration': $("#narration").val(),
                            'update': datetime
                        },
                        beforeSend: function() {
                            $('#UpdateBtn').prop("disabled", true);
                            $('#UpdateBtn').html(
                                '<i class="fa fa-spinner fa-spin"></i> UPDATE'
                            );
                        },
                        complete: function() {
                            $('#UpdateBtn').prop("disabled", false);
                            $('#UpdateBtn').html('UPDATE');
                        },
                        success: function(data) {
                            
                            if (data == 1) {
                                toastr.success('Updated Succesfully.', 'Success', 'positionclass:toast-bottom-full-width');
                                location.reload();
                            }

                        }
                    });
                }
            }
        });
       
    }
    $('.delete_checkbox').click(function() {
        if ($(this).is(':checked')) {
            $(this).closest('tr').addClass('removeRow');
        } else {
            $(this).closest('tr').removeClass('removeRow');
        }
    });
    $('#delete_all').click(function() {
        var checkbox = $('.delete_checkbox:checked');
        var table = 'cash_register';
        if (checkbox.length > 0) {
            var checkbox_value = [];
            $(checkbox).each(function() {
                checkbox_value.push($(this).val());
            });
            $.ajax({
                url: "<?=base_url()?>cash-register/multiple_delete",
                method: "POST",
                data: {
                    checkbox_value: checkbox_value,
                    table: table
                },
                success: function(data) {
                    $('.removeRow').fadeOut(1500);
                }
            })
        } else {
            alert('Select atleast one record');
        }
    })
</script>