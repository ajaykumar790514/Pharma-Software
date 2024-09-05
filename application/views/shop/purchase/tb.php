<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($purchase)?> of <?=$total_rows?> entries</span>
    </div>
</div>

<div id="datatable">
    <div id="grid_table">
        <table class="jsgrid-table">
            <tr class="jsgrid-header-row">
               <th class="jsgrid-header-cell jsgrid-align-center">Sr No</th>
               <th class="jsgrid-header-cell jsgrid-align-center">Invoice Date</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Invoice No</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Supplier Name</th>
                
                <th class="jsgrid-header-cell jsgrid-align-center">Invoice Amt ( <?=$shop_details->currency;?> )</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Status</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Invoice Print</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Action</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Notes</th>
            </tr>
            
            <?php $i=$page; foreach($purchase as $value){ ?>
            <tr class="jsgrid-filter-row">
                <th class="jsgrid-cell jsgrid-align-center"><?=++$i?></th>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo date_format_func($value->purchase_order_date) ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><a target="_blank" href="<?=base_url('purchase/details/'.$value->purchase_id);?>"><?php echo $value->purchase_order_no;?></a></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->vendor_name;?> <br>  ( <?php echo $value->mobile;?>  )</td>
               
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->total_amount;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->status_name;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><i class="mdi mdi-printer text-warning" style="font-size:30px;"></i></td>
                <td class="jsgrid-cell jsgrid-align-center">
                    

                    <a href="<?=$update_url?><?=$value->purchase_id?>"  >
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javscript:void(0)" onclick="delete_purchase(<?php echo $value->purchase_id;?>)"><i class="fa fa-trash"></i>
                    </a>


                </td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->remark;?></td>
            </tr> 
        
        
            <?php } ?>    
        </table>

            
    </div>
</div>
<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($purchase)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
        <?=$links?>
    </div>
</div>
<script>
    function delete_purchase(pid) {
    swal({
        title: "Are you sure?",
        text: "Once deleted records will not recover!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $('#tb').load("<?php echo base_url('purchase/delete_purchase/')?>"+pid );
            toastr.success('Product Deleted Successfully..')
        } else {
            toastr.warning('Product Deletion Cancelled');
        }
    });
}


</script>
<script>
    function change_status(id)
    {
        $.ajax({
        url: "<?php echo base_url('shop-master-data/change_delivery_boy_status'); ?>",
        method: "POST",
        data: {
            id:id
        },
        success:function(data){
            $("#status"+id).html(data);
        }
    });
    }
</script>