<style>
    #customerDetailModal th {
        border-bottom: 1px solid #f3f1f1;
        border-right: 1px solid #f3f1f1;
    }
</style>

        <span>Showing <?=$page+1?> to <?=$page+count($customers)?> of <?=$total_rows?> entries</span>
        <div class="col-md-2" style="float: right; margin: 12px 0px;">
            <input type="text" class="form-control" name="tb-search" id="tb-search" value="<?=$search?>" placeholder="Search...">
        </div>
   
</div>
                                <div id="datatable">
                                    <div id="grid_table">
                                        <table class="jsgrid-table">
                                            <tr class="jsgrid-header-row">
                                                <th class="jsgrid-header-cell jsgrid-align-center">S.No.</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Business Details</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Name</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Mobile</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Email</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Document</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Created At</th>
                                                <th class="jsgrid-header-cell jsgrid-align-center">Credit Limit</th>
                                                <th colspan="2" class="jsgrid-header-cell jsgrid-align-center">Action</th>
                                                
                                            </tr>
                                            <?php
                                            $i=$page;
                                            $counter = 0;
                                            foreach ($customers as $customer) {
                                            ?>
                                                <tr class="jsgrid-filter-row">
                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <?php echo ++$i; ?>
                                                    </td>
                                                    <td class="jsgrid-cell jsgrid-align-center"><?=$customer->business_name;?></td>
                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <?php echo get_full_name($customer->fname, $customer->mname, $customer->lname); ?>
                                                    </td>
                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <?php echo $customer->mobile; ?>
                                                    </td>
                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <?php echo $customer->email; ?>
                                                    </td>
                                                    <!-- Button trigger modal -->


                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <span><?php echo $customer->title; ?></span>
                                                        <span><br>( <?php echo $customer->document_no; ?> )</span>
                                                        <span><br>
                                 <i class="fa fa-image" style="color:blue;margin-left:0.1rem;cursor:pointer" data-bs-toggle="modal" title="View document  front image" data-bs-target="#exampleModal1<?php echo $i;?>"></i>

                                   <i class="fa fa-image" title="View document back image" style="color:blue;margin-left:0.1rem;cursor:pointer" data-bs-toggle="modal"  data-bs-target="#exampleModal2<?php echo $i;?>"></i>

                                                        

                                                        <!-- Image 1 -->
                                                        <div class="modal fade" id="exampleModal1<?php echo $i;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->pic1;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        
                                                        <!-- Image 2 -->
                                                        <div class="modal fade" id="exampleModal2<?php echo $i;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-body">
                                                            <span><img src="<?=base_url('assets/photo/');?><?php echo $customer->pic2;?>" alt="" width="100%" height="100%"></span>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        
                                                      </span>
                                                        
                                                    </td>
                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <?php echo date('d M Y h:i A', strtotime($customer->added)); ?>
                                                    </td>
                                                    <td class="jsgrid-cell jsgrid-align-center">
                                                        <span><?php echo $customer->credit_limit;?></span>
                                                       <a herf="" onclick="return display_details(<?php echo $customer->customer_id; ?>, '<?php echo base_url('customers-b2b/add-credit-limit') ?>')" style="color:blue;font-size:1rem;cursor:pointer"><i class="fa fa-plus" aria-hidden="true" title="Credit"></i></a>
                                                    </td>
                                                    <td  class="jsgrid-cell jsgrid-align-center">
                                                        <button class="btn <?php echo get_customer_button_class($customer->business_status); ?>" onclick="return display_details(<?php echo $customer->customer_id; ?>, '<?php echo base_url('customers-b2b/busines-detail') ?>')">GST</button>
                                                        <button class="btn <?php echo get_customer_button_class($customer->pan_status); ?> my-1" onclick="return display_details(<?php echo $customer->customer_id; ?>, '<?php echo base_url('customers-b2b/pan-detail') ?>')">Pan</button>
                                                        </td>
                                                        <td style="display: flex;" class="jsgrid-cell jsgrid-align-center">
                                                        <!-- <button class="btn mt-1  <?php // echo get_customer_button_class($customer->license_status); ?>" onclick="return display_details(<?php // echo $customer->customer_id; ?>, '<?php // echo base_url('customers-b2b/license-detail') ?>')">License</button> -->
                                                        <a style="margin-left:-5px ;" href="<?=base_url('customers-b2b/license/'.$customer->customer_id.'/'.$menu_id);?>" class="mt-3 btn btn-primary text-white">License</a>
                                                        <a href="<?=base_url('customers-b2b/lazer/'.$customer->customer_id.'/'.$menu_id);?>" class="ml-2  mt-3 btn btn-success text-white">Leazer</a>
                                                        </td>
                                                    </td>
                                                    <!-- <td><center><button class="btn btn-primary" onclick="return display_details(<?php echo $customer->customer_id; ?>, '<?php echo base_url('customers-b2b/verify-detail') ?>')">Click Here</button></center></td> -->
                                                </tr>
                                            <?php }
                                            ?>

                                        </table>
                               <div class="row">
                               <div class="col-md-6 text-left">
                                    <span>Showing <?=$page+1?> to <?=$page+count($customers)?> of <?=$total_rows?> entries</span>
                                </div>
                                <div class="col-md-6 text-right" style="float: right;">
                                        <?=$links?>
                                </div>
                               </div>         
                              
                                    </div>
                                </div>
                          










<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<div id="modal-div"></div>
<script>
    $(document).on('click', '.pag-link', function(event){
    document.body.scrollTop = 0; 
    document.documentElement.scrollTop = 0;
    var search = $('#tb-search').val();
    $.post($(this).attr('href'),{search:search})
    .done(function(data){
        $('#list').html(data);
    })
    return false;
})
</script>

<script>
    function display_details(customer_id, send_url) {
        $.ajax({
            url: send_url,
            method: "POST",
            data: {
                id: customer_id
            },
            success: function(data) {
                const dataArr = JSON.parse(data);
                if (dataArr.status) {
                    $('#modal-div').html(dataArr.data);
                    $('#customerDetailModal').modal('show');
                } else {
                    alert(dataArr.message);
                }
            }
        });
    }
    function toggleCommentBox(status) {
        if(status == 'REJECTED') {
            $('#rejected_comment_div').removeClass('d-none');
        } else {
            $('#rejected_comment_div').addClass('d-none');
        }
    }
    function changeCustomerB2BStatus(formId) {
        var customerId = $('#'+formId+' #customer_id').val();
        var customerDetailType = $('#'+formId+' #customer_detail_type').val();
        var status = $('#'+formId+' #status').val();
        var rejectedComment = $('#'+formId+' #rejected_comment').val();
        if(status == '') {
            alert('Please select status');
            return false;
        }
        if(status == 'REJECTED' && rejectedComment == '') {
            alert('Please enter comment');
            return false;
        }
        const sendData = {
            customer_id: customerId,
            customer_detail_type: customerDetailType,
            status: status,
            rejected_comment: rejectedComment
        }
        
        const sendUrl = $('#'+formId).attr('action');
        $.ajax({
            url: sendUrl,
            method: "POST",
            data: sendData,
            success: function(data) {
                const dataArr = JSON.parse(data);
                alert(dataArr.message);
                if (dataArr.status) {
                    $('#customerDetailModal').modal('hide');
                    location.reload();
                }
            }
        });

        return false;
    }
      function changeCustomerB2BCreditLimit(formId) {
        var customerId = $('#'+formId+' #customer_id').val();
        var credit_limit = $('#'+formId+' #credit_limit').val();
        const sendData = {
            customer_id: customerId,
            credit_limit: credit_limit
        }
        //alert(customerId);
        const sendUrl = $('#'+formId).attr('action');
        $.ajax({
            url: sendUrl,
            method: "POST",
            data: sendData,
            success: function(data) {
                const dataArr = JSON.parse(data);
                alert(dataArr.message);
                if (dataArr.status) {
                    $('#customerDetailModal').modal('hide');
                    location.reload();
                }
            }
        });

        return false;
    }
</script>