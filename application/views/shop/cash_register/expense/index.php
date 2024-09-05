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
                                <h3 class="card-title" id="Bank_Register" style="margin-left: -16px;">Expense Accounts</h3>
                                <h6 class="card-subtitle"></h6>
                            </div>
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                           
                                <button class="float-right btn-sm btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" onclick="showModal1()" data-whatever="Add Cash Data">Add Expense Data</button>
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
                    <div class="col-2">
                        <div class="form-group">
                            <label id="thi_Vendor"  class="control-label">Supplier:</label>
                            <select class="form-control form-control-sm" style="width:100%;" onchange="loadtb()" name="business_id" id="Vendord" >
                                <option value="">Select Supplier</option>
                                <?php foreach ($vendor as $value) { ?>
                                    <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->name; ?>(<?php echo $value->vendor_code; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-group">
                            <label id="thi_accounts"class="control-label">Expense Accounts:</label>
                            <select class="form-control form-control-sm" style="width:100%;" onchange="loadtb()" name="business_id" id="accounts" >
                                <option value="">Select Expense Accounts</option>
                                <?php foreach ($accounts as $value) { ?>
                                    <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->title ?> 
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="tb">

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
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6" id="vendor-div">
                            <div class="form-group">
                                <input type="hidden" name="Id" id="Id">
                                <label id="this_Vendor"  class="control-label">Supplier:</label>
                                <select class="form-control" style="width:100%;" name="business_id" id="VendorId" >
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($vendor as $value) { ?>
                                        <option value="<?php echo $value->id; ?>">
                                            <?php echo $value->name; ?>(<?php echo $value->vendor_code; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6" id="accounts-div">
                            <div class="form-group">
                                <label id="this_Accounts" class="control-label">Expense Accounts:</label>
                                <select class="form-control" style="width:100%;" name="AccountsId" id="AccountsId" >
                                    <option value="">Select Expense Accounts</option>
                                    <?php foreach ($accounts as $value) { ?>
                                        <option value="<?php echo $value->id; ?>">
                                        <?php echo $value->title?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
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
                                <input type="number" class="form-control" name="Amount" id="Amount" min="0" step="0.01" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Expense Date:</label>
                                <input type="date" class="form-control" name="PaymentDate" id="PaymentDate">
                            </div>
                        </div>


                     
                         <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Narration :</label>
                                <input type="text" class="form-control" name="narration" id="narration">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                    <button type="button" class="btn btn-primary waves-light" id="AddBtn" onclick="CashSubmit1()">ADD</button>
                    <button type="button" class="btn btn-danger waves-light" style="display:none;" id="UpdateBtn" onclick="Cashupdate()" value="">UPDATE</button>
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
    $(document).ready(function() {});
    $(document).on('click', '.pag-link', function(event) {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        var search = $('#tb-search').val();
        $.post($(this).attr('href'), {
                search: search
            })
            .done(function(data) {
                $('#tb').html(data);
            })
        return false;
    })

    function showModal1() {
        $('#showModal .modal-body').show();
        $("#Amount").val('');
        $("#refno").val('');
        $("#VendorId").val('');
        $("#PaymentDate").val('');
        $("#myModalLabel21").text("Add Expense Data");
        $("#UpdateBtn").hide();
        $("#AddBtn").show();
        $("#editrefno").hide();
        $("#refno").show();
        $("#vendor-div").show();
    }


    
    $('#reset-page').click(function(e) {
        location.reload();
    });

    function loadtb(id) {
        $("#tb").html('<div class="text-center"><img src="loader.gif"></div>');
        var vendorid = $("#Vendord").val();
        var accounts = $("#accounts").val();
        $.ajax({
            url: "<?php echo base_url('expense/tb') ?>",
            method: "POST",
            data: {
                'fromdate': $("#from_date").val(),
                'todate': $("#to_date").val(),
                'vendorid': vendorid,
                'accountid': accounts,
            },
            success: function(data) {
                $("#tb").html(data);
            }
        });
    }
    var checkref = '';
    var editcheckref = '';

 

    function showmodel(cashid) {
        $("#customer-div").show();
        $("#vendor-div").show();
        $.ajax({
            url: "<?php echo base_url('expense/edit'); ?>",
            method: "POST",
            data: {
                cashid: cashid,
            },
            success: function(data) {
                debugger;
                data_array = $.parseJSON(data)
                console.log(data_array);
                $("#Id").val(data_array[0].id);
                    $("#VendorId").val(data_array[0].customer_id);
                    $("#AccountsId").val(data_array[0].order_id);
                if (data_array[0].dr !== null && data_array[0].dr !=='0.00') {
                    $("#Amount").val(data_array[0].dr);
                } else {
                    $("#Amount").val(data_array[0].cr);
                }
                $("#editrefno").val(data_array[0].reference_no);
                $("#PaymentDate").val(data_array[0].PaymentDate);
                $("#narration").val(data_array[0].narration);
                $("#editrefno").show();
                $("#refno").hide();
                $("#myModalLabel21").text("Update Expense Data");
                $("#UpdateBtn").show();
                $("#AddBtn").hide();
            }
        })
    }

    function resetForm() {
    $('#Id').val('');
    $('#VendorId').val('');
    $('#AccountsId').val('');
    $('#refno').val('');
    $('#editrefno').val('');
    $('#Amount').val('');
    $('#PaymentDate').val('');
    $('#narration').val('');
}
    function CashSubmit1() {
        var refval = $("#refno").val();
        $.ajax({
            url: "<?php echo base_url('expense/checkref_no') ?>",
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
                    var txntype = 8;
                    if ($("#VendorId").val() == ''  && $("#AccountsId").val() == '') {
                        toastr.warning('Please Select Vendor or  Expense  Accounts', 'Warning', 'positionclass:toast-bottom-full-width');
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
                
                    $.ajax({
                        url: "<?php echo base_url('expense/save') ?>",
                        method: "POST",
                        data: {
                            "Amount": $("#Amount").val(),
                            "refno": $("#refno").val(),
                            "txn_type": txntype,
                            'PaymentDate': $("#PaymentDate").val(),
                            'narration': $("#narration").val(),
                            'VendorId': $("#VendorId").val(),
                            'AccountsId': $("#AccountsId").val()

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
            url: "<?php echo base_url('expense/editcheckref_no') ?>",
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
                        customerId = $("#VendorId").val();
                        txntype = 8;
                    if ($("#VendorId").val() == '' && $("#AccountsId").val() == '') {
                        toastr.warning('Please Select Vendor  or Expense Accounts', 'Warning', 'positionclass:toast-bottom-full-width');
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
                
                    var currentdate = new Date();
                    var datetime = (currentdate.getFullYear()) + "-" +
                        (currentdate.getMonth() + 1) + "-" +
                        currentdate.getDate() + " " +
                        currentdate.getHours() + ":" +
                        currentdate.getMinutes() + ":" +
                        currentdate.getSeconds();

                    $.ajax({
                        url: "<?php echo base_url('expense/update') ?>",
                        method: "POST",
                        data: {
                            "Id": $("#Id").val(),
                            "Amount": $("#Amount").val(),
                            "refno": $("#editrefno").val(),
                            "VendorId": customerId,
                            "txn_type": txntype,
                            'PaymentDate': $("#PaymentDate").val(),
                            'narration': $("#narration").val(),
                            'update': datetime,
                            'AccountsId': $("#AccountsId").val()
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
                url: "<?php echo base_url(); ?>expense/multiple_delete",
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
            toastr.warning('Select atleast one record.', 'Warning', 'positionclass:toast-bottom-full-width');
        }
    })
</script>