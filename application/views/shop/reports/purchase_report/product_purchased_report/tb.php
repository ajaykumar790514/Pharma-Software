<style>
.fa {
  margin-left: -12px;
  margin-right: 8px;
}
#reset-data
{
    /*background-color:red;*/
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
    <div class="col-md-9 text-left">
    </div>
     <div class="col-md-3 text-right">
      <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>
    
      <?php if(!empty($to_date)) { ?>
       <a href="<?=base_url('reports/product_purchased_report/export_to_excel/'.$from_date.'/'.$to_date)?>"  class="btn btn-primary btn-sm mb-3"><i class="fas fa-arrow-up"></i> </a>
    <?php } ?>
    </div>
</div>


<!--<div class="row">-->
<!--    <div class="col-md-6 text-left">-->
        <!-- <span>Showing <?=$page+1?> to <?=$page+count($product_purchased_report)?> of <?=$total_rows?> entries</span> -->
<!--    </div>-->
<!--    <div class="col-md-6 text-right">-->
<!--        <div class="col-md-4" style="float: left; margin: 12px 0px;">-->
            <!-- <input type="text" class="form-control" name="tb-search" id="tb-search" value="<?=$search?>" placeholder="Search..."> -->
<!--        </div>-->
<!--        <div class="col-md-8 text-right" style="float: left;">-->
            <!-- <?=$links?> -->
<!--        </div>-->
        

<!--    </div>-->
<!--</div>-->

<div class="row">
        <div class="col-md-3">
            <div class="form-group">
            <label class="control-label">From date:</label>
            <input type="date" class="form-control form-control-sm" name="from_date" id="from_date" value="<?=$from_date?>">
            </div>
            <div id="msg"></div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
            <label class="control-label">To date:</label>
            <input type="date" class="form-control form-control-sm" name="to_date" id="to_date" value="<?=$to_date?>" onchange="filter_purchased_product(this.value)">
            </div>
        </div>
        <!--<div class="col-md-2 mt-4 d-flex align-items-end">-->
        <!--    <div class="form-group">-->
        <!--    <button class="btn btn-sm btn-danger" id="reset-data">Reset</button>-->
        <!--    </div>-->
        <!--</div>-->
        </div>
        <!-- <div class="col-4">
            <div class="form-group">
            <label class="control-label">Status:</label>
            <select class="form-control" style="width:100%;" name="status_id" id="status_id" onchange="filter_by_status(this.value)">
            <option value="">Select</option>
            <?php foreach ($order_status as $status) { ?>
            <option value="<?php echo $status->id; ?>" <?php if(!empty($statusid)) { if($statusid==$status->id) {echo "selected"; } }?>>
                <?php echo $status->name; ?>
            </option>
            <?php } ?>
            </select>
            </div>
        </div> -->
</div>
<div id="datatable">
    <?php if(!empty($to_date)) { ?>
    <div id="grid_table" class="table-responsive table-wrap">
        <table >
            <tr class="jsgrid-header-row">
                <th class="jsgrid-header-cell jsgrid-align-center w-10">S.No.</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Image</th>
                <th class="jsgrid-header-cell jsgrid-align-center w-25">Product Name</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Model</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Quantity</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Total</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Parent Category</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Sub Category</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Category</th>
            </tr>
            
            <?php $i=$page; foreach($product_purchased_report as $value){ ?>
                <?php 
                    $category_list = [];
                    foreach ($cat_pro_map as $cat) {
                        if ($cat->pro_id == $value->prod_id) {
                            $category_list[] = '(' . $cat->name . ')';
                        } 
                    }
                  
                    
                    ?>
            <tr class="jsgrid-filter-row">
                <th class="jsgrid-cell jsgrid-align-center"><?=++$i?></th>
                <th class="jsgrid-cell jsgrid-align-center"><img style="cursor: pointer;"  height="35px" src="<?php echo IMGS_URL.$value->thumbnail?>" alt="" data-toggle="modal" data-target="#exampleModal<?php echo $i;?>"></th>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->prod_name;?> (<?php echo $value->unit_value.' '.$value->unit_type;?>)</td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->product_code;?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?php echo $value->quantity.' '.$value->unit_type;?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?php echo $value->total;?></td>
                <td class="jsgrid-cell jsgrid-align-center">
                    <b> 
                        <?php echo @$category_list[0];?>
                    </b>
                </td>
                <td class="jsgrid-cell jsgrid-align-center">
                    <b> 
                        <?php echo @$category_list[1];?>
                    </b>
                </td>
                <td class="jsgrid-cell jsgrid-align-center">
                    <b> 
                        <?php echo @$category_list[2];?>
                    </b>
                </td>
            </tr> 
            <div class="modal fade" id="exampleModal<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><b><?php echo $value->prod_name;?></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                    <img   src="<?php echo IMGS_URL.$value->thumbnail?>" alt="" class="img-fluid" >
                    </div>
                    </div>
                </div>
                </div>
            <?php } ?>    
        </table>

            
    </div>
</div>
<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($product_purchased_report)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
        <?=$links?>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
      function read_more(i) {
    $('.prev' + i).hide();
    $('.hidden' + i).show();
    $('#read-more' + i).hide();
    $('#read-less' + i).show();
}

function read_less(i) {
    $('.prev' + i).show();
    $('.hidden' + i).hide();
    $('#read-more' + i).show();
    $('#read-less' + i).hide();
}

   function filter_purchased_product(to_date)
   {
    if(document.getElementById('from_date').value == 0)
{
    alert('Please Select From Date');
    document.getElementById('from_date').focus();
    $('#to_date').prop('value',0);
    return false;
}
$("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
    var from_date = $("#from_date").val();
    if(from_date>to_date)
    {
        msg = "From date should be less than to date";
        document.getElementById('msg').style.color='red';
        document.getElementById('msg').innerHTML=msg;
        return;
    }
    $.ajax({
        url: "<?php echo base_url('reports/product_purchased_report/tb'); ?>",
        method: "POST",
        data: {
            from_date:from_date,
            to_date:to_date
        },
        success: function(data){
            $("#tb").html(data);
        },
    });
   }
</script>
<script>
           $('#reset-data').click(function(){
                location.reload();
            })
    </script>


