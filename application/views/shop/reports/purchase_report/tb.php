<style>
.fa {
  margin-left: -12px;
  margin-right: 8px;
}
.jsgrid-table
{
    width:166%;
}

h4
{
    color:blue;
}
#reset-data
{
    /*background-color:red;*/
}
.highlighted-row {
    background-color: #C0C0C0 !important;
}
.highlighted-row td {
    font-size: 0.9rem;
    border: 1px solid white;
}

</style>
<div class="row">
    <div class="col-md-9 text-left">
    </div>
     <div class="col-md-3 text-right">
      <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>
    
      <?php if(!empty($to_date)) { 
            if (!is_array($prod_id)) {
                $prod_id = [$prod_id];
            }
            
      ?>
       <a href="javascript:void(0)" onclick="exportToExcel('<?=$from_date;?>', '<?=$to_date;?>', '<?=$vendor_id;?>', '<?=$brand_id;?>', '<?=implode(',', $prod_id);?>', '<?=$search;?>')" class="btn btn-primary btn-sm mb-3"><i class="fas fa-arrow-up"></i> </a>
    <?php } ?>
    </div>
</div>

<!--<div class="row">-->
<!--    <div class="col-md-6 text-left">-->
        <!-- <span>Showing <?//=$page+1?> to <?//=$page+count($purchase_report)?> of <?//=$total_rows?> entries</span> -->
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
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">From date:</label>
                <input type="date" class="form-control form-control-sm" name="from_date" id="from_date" value="<?php if(!empty($from_date)){echo $from_date; }?>">
            </div>
            <div id="msg"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">To date:</label>
                <input type="date" class="form-control form-control-sm" name="to_date" id="to_date" value="<?php if(!empty($to_date)){echo $to_date; }?>" onchange="filter_purchase_report(this.value)">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
            <label class="control-label">Supplier:</label>
            <select class="form-control form-control-sm" style="width:100%;" name="vendor_id" id="vendor_id" onchange="filter_by_vendor(this.value)">
            <option value="">Select</option>
            <?php foreach ($vendor_list as $vendor) { ?>
            <option value="<?php echo $vendor->id; ?>" <?php if(!empty($vendor_id)) { if($vendor_id==$vendor->id) {echo "selected"; } }?>>
                <?php echo $vendor->fname.' '.$vendor->lname; ?>
            </option>
            <?php } ?>
            </select>
            </div>
        </div>
        <!-- <div class="col-2">
            <div class="form-group">
            <label class="control-label">Brand:</label>
            <select class="form-control" style="width:100%;" name="brand_id" id="brand_id" onchange="filter_by_brand(this.value)">
            <option value="">Select</option>
            <?php foreach ($brands as $brand) { ?>
            <option value="<?php echo $brand->id; ?>" <?php if(!empty($brand_id)) { if($brand_id==$brand->id) {echo "selected"; } }?>>
                <?php echo $brand->name; ?>
            </option>
            <?php } ?>
            </select>
            </div>
        </div> -->
        <div class="col-md-6">
            <div class="form-group ">
                <label class="control-label">Search:</label>
                <input type="text" class="form-control form-control-sm" name="tb-search" id="tb-search" value="<?php if($search!='null'){echo $search;}?>" placeholder="Search Invoice No / Supplier Name / Supplier Code / Supplier Mobile / Supplier GSTIN">
            </div>
        </div>
        <!-- <div class="col-3">
            <div class="form-group">
                <label class="control-label">Parent Categories:</label>
                <select class="form-control" style="width:100%;" name="parent_id" id="parent_id" onchange="fetch_sub_categories(this.value)">
                <option value="">Select</option>
                <?php foreach ($parent_cat as $parent) { ?>
                <option value="<?php echo $parent->id; ?>" <?php if(!empty($parent_id)) { if($parent_id==$parent->id) {echo "selected"; } }?>>
                    <?php echo $parent->name; ?>
                </option>
                <?php } ?>
                </select>
            </div>
        </div> -->

        <!-- <div class="col-3">
            <div class="form-group">
                <label class="control-label">Sub Categories:</label>
                
                <select class="form-control parent_cat_id" style="width:100%;" name="parent_cat_id" id="parent_cat_id"  onchange="filter_by_subcategory(this.value)">
                    <?php if($parent_cat_id!=='null') { ?>
                        <?php foreach ($sub_cat as $scat) { ?>
                        <option value="<?php echo $scat->id; ?>" <?php if(!empty($parent_cat_id)) { if($parent_cat_id==$scat->id) {echo "selected"; } }?>>
                            <?php echo $scat->name; ?>
                        </option>
                        <?php } ?>
                    <?php }?>                                  
                </select>
            </div>
        </div> -->
        <!-- <div class="col-3">
            <div class="form-group">
                <label class="control-label">Categories:</label>
                
                <select class="form-control child_cat_id" style="width:100%;" name="cat_id" id="cat_id" onchange="fetch_by_cat(this.value)">
                    <?php if($child_cat_id!=='null') { ?>
                        <?php foreach ($child_cat as $ccat) { ?>
                        <option value="<?php echo $ccat->id; ?>" <?php if($child_cat_id!='null') { if($child_cat_id==$ccat->id) {echo "selected"; } }?>>
                            <?php echo $ccat->name; ?>
                        </option>
                        <?php } ?>
                    <?php }?>                                  
                </select>
            </div>
        </div> -->
        <!--<div class="col-2 mt-4">-->
        <!--    <div class="form-group">-->
        <!--    <button class="btn btn-danger" id="reset-data">Reset</button>-->
        <!--    </div>-->
        <!--</div>-->
       
</div>

<div id="datatable">
    <?php if(!empty($to_date)) { 
         $total_value_with_tax_sum = $purchase_result->total_value;
         $total_value_without_tax_sum = $total_value_with_tax_sum - $purchase_result->total_tax;
        ?>
        <!--<div class="row mt-3">-->
            <!--<div class="col-md-2">-->
            <?php
            // if (!is_array($prod_id)) {
            //     $prod_id = [$prod_id];
            // }
            ?>
            <!--<button type="button" onclick="exportToExcel('<?=$from_date;?>', '<?=$to_date;?>', '<?=$vendor_id;?>', '<?=$brand_id;?>', '<?=implode(',', $prod_id);?>', '<?=$search;?>')" class="btn btn-sm btn-primary"><i class="fas fa-arrow-down"></i> Export to Excel</button>-->
            <!-- <a href="<?= base_url('reports/purchase_report/export_to_excel/'.$from_date.'/'.$to_date.'/'.$vendor_id."/".$search."/".$brand_id."/".$parent_id."/".$parent_cat_id."/".$child_cat_id); ?>" class="btn btn-primary btn-sm mb-3"><i class="fas fa-arrow-down"></i> Export to Excel</a> -->
           
            <!--</div>-->
            <!--<div class="col-md-3 mt-1">-->
            <!--<h4>Total without tax = <?=$shop_details->currency;?> <?= @round($total_value_without_tax_sum,2); ?></h4>-->
            <!--</div>-->
            <!--<div class="col-md-3 mt-1">-->
            <!--<h4>Total tax = <?=$shop_details->currency;?> <?= @round($purchase_result->total_tax,2); ?></h4>-->
            <!--</div>-->
            <!--<div class="col-md-3 mt-1">-->
            <!--<h4>Total with tax = <?=$shop_details->currency;?> <?= @round($purchase_result->total_value,2); ?></h4>-->
            <!--</div>-->
        <!--</div>-->
          <div class="row">
   
     <div class="col-md-9 mt-1 d-flex">
        <h5 class="text-themecolor">Total Without Tax = <b><?=$shop_details->currency;?> <?= @round($total_value_without_tax_sum,2); ?></b></h5>
         <h5 class="text-themecolor ml-4">Total Tax = <b><?=$shop_details->currency;?> <?= @round($purchase_result->total_tax,2); ?></b></h5>
         <h5 class="text-themecolor ml-4">Total = <b><?=$shop_details->currency;?> <?= @round($purchase_result->total_value,2); ?></b></h5>
    </div>
</div>
    <div id="grid_table" class="table-responsive">
        <table class="jsgrid-table" >
            <tr class="jsgrid-header-row">
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">S.No.</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Invoice Date</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Invoice No</th>
               
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Supplier Name</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Supplier Code</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">GSTIN</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Quantity</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Total without tax</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Total tax</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Total value with tax</th>
                
            </tr>
            
            <?php $i=$page; foreach($purchase_report as $value){
                 $getItem = $this->reports_model->getAllItem($value->purchase_id,$brand_id,$pro_id,$search);
                $total_tax = $value->total_tax;

                $total_without_tax = $value->total_amount - $value->total_tax;
                $total_value_with_tax = $value->total_amount;
                
                ?>
                
            <tr class="jsgrid-filter-row">
                <th class="jsgrid-cell jsgrid-align-center"><?=++$i?></th>
                <td class="jsgrid-cell jsgrid-align-center"><?= date_format_func($value->purchase_order_date);?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->purchase_order_no; ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->vendor_name; ?> <br> ( <?= $value->mobile; ?> ) </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->vendor_code; ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->gstin; ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->total_qty; ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?=$shop_details->currency;?> <?= round($total_without_tax,2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?=$shop_details->currency;?> <?= round($total_tax,2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?=$shop_details->currency;?> <?= round($total_value_with_tax,2); ?></td>
                
                
            </tr> 
            <tr style="height:40px;">
                <td class="jsgrid-cell jsgrid-align-center" colspan="10">    </td>
            </tr>
            <?php 
            $asciiValue = ord('A');
            foreach($getItem as $item):
            ?>
            
            <tr class="highlighted-row">
                <td  class=" jsgrid-align-center"><i class="fas fa-arrow-right"></i></td>
                <td class=" jsgrid-align-center" ><?=$item->name;?></td> 
                <td class=" jsgrid-align-center" ><?=$item->item_props_value;?></td> 
                <td class=" jsgrid-align-center"><?=$item->product_code;?></td> 
                <td class=" jsgrid-align-center">Qty - <?=$item->qty;?></td> 
                <td class=" jsgrid-align-center">Purchase Rate - <?=$shop_details->currency;?> <?=$item->unit_cost;?></td> 
                <td class=" jsgrid-align-center">Tax - <?=$shop_details->currency;?> <?=$item->total_tax;?></td> 
                <td class=" jsgrid-align-center">DIscount - <?=$shop_details->currency;?> <?=$item->discount_value;?></td> 
                <td class=" jsgrid-align-center">Landing Cost - <?=$shop_details->currency;?> <?=$item->landing_cost;?></td>
                <td class=" jsgrid-align-center">Total - <?=$shop_details->currency;?> <?=$item->total;?></td> 
            </tr>
            <?php $asciiValue++; ?>
            <?php endforeach; ?> 
            <tr style="height:40px;">
                <td  class="jsgrid-cell jsgrid-align-center" colspan="10">    </td>
            </tr>
            <?php } 
                           
            ?>  
 
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?=$page+1?> to <?=$page+count($purchase_report)?> of <?=$total_rows?> entries</span>
    </div>
    <div class="col-md-6 text-right">
        <?=$links?>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
     function exportToExcel(from_date,to_date,vendor_id,brand_id,prod_id,search) {
    // Make AJAX request
    // Convert the string to an array
        var prodIdArray = prod_id.split(',');
        console.log(prodIdArray);
    $.ajax({
        url: '<?=base_url();?>reports/purchase_report/export_to_excel_new/',
        type: 'POST',
        data: { from_date: from_date,to_date:to_date,vendor_id:vendor_id,brand_id:brand_id, prod_id:prodIdArray,search: search },
        success: function(response, status, xhr) {
            // Get filename from Content-Disposition header
            var filename = "";
            var disposition = xhr.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }

            // Set default filename if not found
            filename = filename || 'Purchase_Report.csv';

            // Open the CSV file in a new tab
            var blob = new Blob([response], { type: 'text/csv' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    });
}
   function filter_purchase_report(to_date)
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
        var vendor_id = $("#vendor_id").val();
        var search  = $('#tb-search').val();
        var brand_id = $("#brand_id").val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();
        if(from_date>to_date)
        {
            msg = "From date should be less than to date";
            document.getElementById('msg').style.color='red';
            document.getElementById('msg').innerHTML=msg;
            return;
        }
        $.ajax({
            url: "<?php echo base_url('reports/purchase_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                vendor_id:vendor_id,
                search:search,
                brand_id:brand_id,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
            },
            success: function(data){
                $("#tb").html(data);
            },
        });
   }
</script>

<script type="text/javascript">
   function filter_by_vendor(vendor_id)
   {
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
    var from_date = $("#from_date").val();
    var to_date = $('#to_date').val();
    var search  = $('#tb-search').val();
    var brand_id = $("#brand_id").val();
    var parent_id  = $('#parent_id').val();
    var parent_cat_id  = $('#parent_cat_id').val();
    var cat_id  = $('#cat_id').val();

    $.ajax({
        url: "<?php echo base_url('reports/purchase_report/tb'); ?>",
        method: "POST",
        data: {
            from_date:from_date,
            to_date:to_date,
            vendor_id:vendor_id,
            search:search,
            brand_id:brand_id,
            parent_id:parent_id,
            parent_cat_id:parent_cat_id,
            child_cat_id:cat_id,
        },
        success: function(data){
            $("#tb").html(data);
        },
    });
   };
   function filter_by_brand(brand_id)
   {
       $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');

        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var vendor_id = $("#vendor_id").val();
        var search  = $('#tb-search').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();

        $.ajax({
            url: "<?php echo base_url('reports/purchase_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                vendor_id:vendor_id,
                brand_id:brand_id,
                search:search,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
            },
            success: function(data){
                $("#tb").html(data);
            },
        });
   };
</script>
<script>
    $('#reset-data').click(function(){
        location.reload();
    });
    function fetch_sub_categories(parent_id)
   {
        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var vendor_id = $("#vendor_id").val();
        var brand_id = $("#brand_id").val();
        var search  = $('#tb-search').val();
    $.ajax({
        url: "<?php echo base_url('reports/fetch_sub_categories'); ?>",
        method: "POST",
        data: {
            parent_id:parent_id,    //cat1 id
        },
        success: function(data){
            $(".parent_cat_id").html(data);

            var cat_id = $('#parent_cat_id').val(); //cat2 id
            if(parent_id == '')
            {
                $.ajax({
                    url: "<?php echo base_url('reports/purchase_report/tb'); ?>",
                    method: "POST",
                    data: {
                        cat_id:cat_id,
                        parent_id:parent_id,
                        from_date:from_date,
                        to_date:to_date,
                        vendor_id:vendor_id,
                        brand_id:brand_id,
                        search:search,
                    },
                    success: function(data){
                    $("#tb").html(data);
                    
                    },
                });
            }
        },
    });
   };
   function filter_by_subcategory(parent_cat_id)
   {
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');

        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var search  = $('#tb-search').val();
        var vendor_id  = $('#vendor_id').val();
        var brand_id  = $('#brand_id').val();
        var parent_id  = $('#parent_id').val();

        $.ajax({
            url: "<?php echo base_url('reports/purchase_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                search:search,
                vendor_id:vendor_id,
                parent_cat_id:parent_cat_id,
                parent_id:parent_id,
                brand_id:brand_id
            },
            success: function(data){
                $("#tb").html(data);
                //ajax method for loading child categories
                    $.ajax({
                        url: "<?php echo base_url('reports/fetch_cat'); ?>",
                        method: "POST",
                        data: {
                            parent_cat_id:parent_cat_id
                        },
                        success: function(data){
                            $(".child_cat_id").html(data);
                        },
                    });
            },
        });
  
   };
   function fetch_by_cat(child_cat_id)
   {
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');

        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var search  = $('#tb-search').val();
        var vendor_id  = $('#vendor_id').val();
        var brand_id  = $('#brand_id').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();

        $.ajax({
            url: "<?php echo base_url('reports/purchase_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                search:search,
                vendor_id:vendor_id,
                brand_id:brand_id,
                parent_cat_id:parent_cat_id,
                parent_id:parent_id,
                child_cat_id:child_cat_id,
            },
            success: function(data){
                $("#tb").html(data);
            },
        });
  
   }
</script>

