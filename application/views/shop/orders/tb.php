
    <style>
.fa {
  margin-left: -12px;
  margin-right: 8px;
}
.jsgrid-table
{
    width:202%;
}
h4
{
    color:blue;
}
.table-wrap
{
    white-space:nowrap;
}
.table-responsive::-webkit-scrollbar {
    -webkit-appearance: none;
}

.table-responsive::-webkit-scrollbar:vertical {
    width: 12px;
}

.table-responsive::-webkit-scrollbar:horizontal {
    height: 12px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, .5);
    border-radius: 10px;
    border: 2px solid #ffffff;
}

.table-responsive::-webkit-scrollbar-track {
    border-radius: 10px;  
    background-color: #ffffff; 
}
</style>
<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($rows)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
       
        <div class="col-md-12 text-right" style="float: left;">
            <?=$links?>
        </div>
    </div>
</div>
<div id="datatable">
    <div id="grid_table" class="table-responsive table-wrap">
        <table width="100%">
        <tr class="jsgrid-header-row">
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 5%;">ID</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Order ID</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Customer Name</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Order Date</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Total Value</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Status</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Payment Method</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Print Bill</th>
            </tr>
            
            <?php $row_id=0;$i=$page; foreach($rows as $value){ 
                 if($value->status==1)
                 {
                     $row_id=0;
                 }else{
                     $row_id=$value->Oid;
                 }?>
                <tr class="jsgrid-filter-row">
                <th class="jsgrid-cell jsgrid-align-center" ><a target="_blank" href="<?=base_url('orders/details/');?><?=$value->Oid?>"><?=$value->Oid?></a></th>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->orderid;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->customer_name;?><br><?php echo $value->customer_mobile;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo date_format_func($value->added) ;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo @$shop_details->currency.' '.$value->total_value;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->status_name;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php if($value->payment_method=='1'){ echo "COD";}else{ echo "Online";};?></td>
                <td class="jsgrid-cell jsgrid-align-center"><a onclick="printPopUp('<?=$row_id?>')"><i class="mdi mdi-printer text-warning" style="font-size:30px;cursor:pointer"></i></a></td>
            </tr> 
        
        
            <?php } ?>    
        </table>

            
    </div>
</div>
<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($rows)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
        <?=$links?>
    </div>
</div>

<script>
      function printPopUp(id){
        if (id === '0') {
       Swal.fire({
       icon: 'error',
       title: 'Oops...',
       text: 'You cannot print bill for this order!'
       })
       }else{
       var newwindow = window.open('orders/print-bill/'+id, '', 'height=1000,width=950');
       if (window.focus) {
       newwindow.focus();
        }
       return false;
       }
       }
</script>