<style>
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
        <span>Showing <?=$page+1?> to <?=$page+count($vendors)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
       
        <div class="col-md-12 text-right" style="float: left;">
            <?=$links?>
        </div>
    </div>
</div>
<div id="datatable">
    <div id="grid_table" class="table-responsive table-wrap">
        <table>
        <tr class="jsgrid-header-row">
                <th class="jsgrid-header-cell jsgrid-align-center"><button type="button" name="delete_all" id="delete_all" class="btn btn-danger btn-sm">Delete Selected</button></th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 5%;">S.No.</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Name</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Customer  Code</th>
                <th class="jsgrid-header-cell jsgrid-align-center">GSTIN</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Mobile</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Email</th>
                <th class="jsgrid-header-cell jsgrid-align-center">State</th>
                <th class="jsgrid-header-cell jsgrid-align-center">City</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Pincode</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Address</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Status</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Actions</th>
            </tr>
            
            <?php $i=$page; foreach($vendors as $value){ ?>
                <tr class="jsgrid-filter-row">
                <td class="jsgrid-cell jsgrid-align-center">
                    <input type="checkbox" class="delete_checkbox" value="<?= $value->id; ?>" id="multiple_delete<?= $value->id; ?>" />
                    <label for="multiple_delete<?= $value->id; ?>"></label>
                </td>
                <th class="jsgrid-cell jsgrid-align-center" ><?=++$i?></th>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->vendor_fname.' '.$value->vendor_lname;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->vendor_code;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->gstin;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->mobile;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->email;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->state_name;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->city_name;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->pincode;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->address;?></td>
                
                <td class="jsgrid-cell jsgrid-align-center" id="status<?php echo $value->id; ?>">
                                                            <?php if($value->isActive==1) { ?>
                                                        <button class="btn btn-success" onclick="change_status(<?php echo $value->id;?>)">Active</button>
                                                        <?php } else {?>
                                                            <button class="btn btn-danger" onclick="change_status(<?php echo $value->id;?>)">Inactive</button>
                                                            <?php }?>
                                                        </td>
                <td class="jsgrid-cell jsgrid-align-center">
                    

                    <a href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Update Customer ( <?=$value->fname?> )" data-url="<?=$update_url?><?=$value->id?>" >
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javscript:void(0)" onclick="delete_vendor(<?php echo $value->id;?>)">
                        <i class="fa fa-trash"></i>
                    </a>

                </td>
            </tr> 
        
        
            <?php } ?>    
        </table>

            
    </div>
</div>
<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($vendors)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
        <?=$links?>
    </div>
</div>
<script>
    function change_status(id)
    {
        $.ajax({
        url: "<?php echo base_url('shop-master-data/change_customer_status'); ?>",
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
<script>
    function delete_vendor(vid){
        if(confirm('Do you want to delete?') == true)
        {
            $.ajax({
                url: "<?php echo base_url('shop-master-data/customer/delete/'); ?>",
                method: "POST",
                data: {
                    vid:vid
                },
                success:function(data){
                    $("#tb").html(data);
                }
            });
        }
    }
        //multiple delete
        $('.delete_checkbox').click(function(){
    if($(this).is(':checked'))
    {
        $(this).closest('tr').addClass('removeRow');
    }
    else
    {
        $(this).closest('tr').removeClass('removeRow');
    }
    });
   $('#delete_all').click(function(){
        var checkbox = $('.delete_checkbox:checked');
        var table = 'vendors';
            if(checkbox.length > 0)
            {
            var checkbox_value = [];
            $(checkbox).each(function(){
                checkbox_value.push($(this).val());
            });
            $.ajax({
                url:"<?php echo base_url(); ?>shop-master-data/multiple_delete",
                method:"POST",
                data:{checkbox_value:checkbox_value,table:table},
                success:function(data)
                {
                    $('.removeRow').fadeOut(1500);
                }
            })
            }
            else
            {
            alert('Select atleast one record');
            }
   })
</script>