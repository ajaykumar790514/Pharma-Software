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
        <!-- <span>Showing <?//=$page+1?> to <?//=$page+count($sales_report)?> of <?//=$total_rows?> entries</span> -->
    </div>
    <div class="col-md-6 text-right">
        <div class="col-md-4" style="float: left; margin: 12px 0px;">
            <!-- <input type="text" class="form-control form-control-sm" name="tb-search" id="tb-search" value="<?=$search?>" placeholder="Search..."> -->
        </div>
        <div class="col-md-8 text-right" style="float: left;">
            <!-- <?=$links?> -->
        </div>
       
    </div>
</div>
<div class="row">
        <div class="col-3">
            <div class="form-group">
                <label class="control-label">From date:</label>
                <input type="date" class="form-control form-control-sm" name="from_date" id="from_date" value="<?php if(!empty($from_date)){echo $from_date; }?>">
            </div>
            <div id="msg"></div>
        </div>

        <div class="col-3">
            <div class="form-group">
                <label class="control-label">To date:</label>
                <input type="date" class="form-control form-control-sm" name="to_date" id="to_date" value="<?php if(!empty($to_date)){echo $to_date; }?>" onchange="filter_sales_report(this.value)">
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
            <label class="control-label">Payment Mode:</label>
            <select class="form-control form-control-sm" style="width:100%;" name="pm_id" id="pm_id" onchange="filter_by_payment_mode(this.value)">
                <option value="">Select</option>
                <option value="cod" <?php if(!empty($pmid)){if($pmid == 'cod'){echo "selected";} }?>>COD</option>
                <option value="online" <?php if(!empty($pmid)){if($pmid == 'online'){echo "selected";} }?>>Online</option>
                
            </select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
            <label class="control-label">Status:</label>
                <select class="form-control form-control-sm" style="width:100%;" name="status_id" id="status_id" onchange="filter_by_status(this.value)">
                    <option value="">Select</option>
                    <?php foreach ($order_status as $status) { ?>
                    <option value="<?php echo $status->id; ?>" <?php if(!empty($status_id)) { if($status_id==$status->id) {echo "selected"; } }?>>
                        <?php echo $status->name; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <!-- <div class="col-2">
            <div class="form-group">
            <label class="control-label">Brand:</label>
            <select class="form-control form-control-sm" style="width:100%;" name="brand_id" id="brand_id" onchange="filter_by_brand(this.value)">
            <option value="">Select</option>
            <?php foreach ($brands as $brand) { ?>
            <option value="<?php echo $brand->id; ?>" <?php if(!empty($brand_id)) { if($brand_id==$brand->id) {echo "selected"; } }?>>
                <?php echo $brand->name; ?>
            </option>
            <?php } ?>
            </select>
            </div>
        </div> -->
        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Products:</label>
                <select class="form-control form-control-sm" style="width:100%;" name="product_id" id="product_id" onchange="filter_by_product(this.value)">
                <option value="">Select</option>
                <?php if(!empty($to_date)){
                    $products = array();
                    foreach($sales_report as $val){ 
                       
                            $prod_id = $val->prod_id;
                            if(!in_array($val->prod_id,$products))
                            {
                                array_push($products,$prod_id);
                ?>
                <option value="<?= $val->prod_id; ?>" <?php if($product_id!='null') { if($product_id==$val->product_id) {echo "selected"; } }?>>
                    <?= $val->product_name; ?>
                </option>
                <?php } }}?>
                </select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Parent Categories:</label>
                <select class="form-control form-control-sm" style="width:100%;" name="parent_id" id="parent_id" onchange="fetch_sub_categories(this.value)">
                <option value="">Select</option>
                <?php foreach ($parent_cat as $parent) { ?>
                <option value="<?php echo $parent->id; ?>" <?php if(!empty($parent_id)) { if($parent_id==$parent->id) {echo "selected"; } }?>>
                    <?php echo $parent->name; ?>
                </option>
                <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Sub Categories:</label>
                
                <select class="form-control form-control-sm parent_cat_id" style="width:100%;" name="parent_cat_id" id="parent_cat_id"  onchange="filter_by_subcategory(this.value)">
                    <?php if($parent_cat_id!=='null') { ?>
                        <?php foreach ($sub_cat as $scat) { ?>
                        <option value="<?php echo $scat->id; ?>" <?php if(!empty($parent_cat_id)) { if($parent_cat_id==$scat->id) {echo "selected"; } }?>>
                            <?php echo $scat->name; ?>
                        </option>
                        <?php } ?>
                    <?php }?>                                  
                </select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Categories:</label>
                
                <select class="form-control form-control-sm child_cat_id" style="width:100%;" name="cat_id" id="cat_id" onchange="fetch_products_by_cat(this.value)">
                    <?php if($child_cat_id!=='null') { ?>
                        <?php foreach ($child_cat as $ccat) { ?>
                        <option value="<?php echo $ccat->id; ?>" <?php if($child_cat_id!='null') { if($child_cat_id==$ccat->id) {echo "selected"; } }?>>
                            <?php echo $ccat->name; ?>
                        </option>
                        <?php } ?>
                    <?php }?>                                  
                </select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Search:</label>
                <input type="text" class="form-control form-control-sm" name="tb-search" id="tb-search" value="<?php if($search!='null'){echo $search;}?>" placeholder="Search...">
            </div>
        </div>
        <!-- <div class="col-2 mt-4">
            <div class="form-group">
                <input type='checkbox' name='subscription' value='1' id="subscription" onclick="filter_by_subscription()"/>
                <label for="subscription">Subscription</label>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label class="control-label">Plan type:</label>
                <select class="form-control form-control-sm" style="width:100%;" name="plan_type_id" id="plan_type_id" onchange="filter_by_plan_type(this.value)" disabled>
                <option value="">Select</option>
                <?php //foreach ($plan_types as $plan) { ?>
                <option value="<?php // echo $plan->id; ?>" <?php // if($plan_type_id!='null') { if($plan_type_id==$plan->id) {echo "selected"; } }?>>
                    <?php //echo $plan->plan; ?>
                </option>
                <?php //} ?>
                </select>
            </div>
        </div> -->
        <div class="col-2">
            <div class="form-group" style="margin-top:33px">
            <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3 mr-3" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>
            <?php if(!empty($to_date)) { ?>
            <a href="<?= base_url('reports/sales_report/export_to_excel/'.$from_date.'/'.$to_date.'/'.$pmid.'/'.$status_id.'/'.$search."/".$brand_id."/".$parent_id."/".$parent_cat_id."/".$child_cat_id."/".$product_id."/".$subscription."/".$plan_type_id); ?>" class="btn btn-primary btn-sm mb-3"><i class="fas fa-arrow-up"></i></a>
            <?php }?>
            </div>
        </div>
        <input type="hidden" id="subscription_value" value="<?= $subscription; ?>">
</div>

<div class="row">
   
   <div class="col-md-9 mt-1 d-flex">
   <?php 
         $total_value_with_tax_sum = @$sales_result->total_value;
         $total_value_without_tax_sum = $total_value_with_tax_sum - @$sales_result->total_tax;
        ?>
      <h5 class="text-themecolor">Total without tax  = <b>₹ <?= @round($sales_result->total_tax,2); ?></b></h5>
       <h5 class="text-themecolor ml-4">Total tax = <b>₹  <?=@round($low_stock_result->total_stock,2); ?></b></h5>

       <h5 class="text-themecolor ml-4">Total with tax = <b>₹ <?= @round($total_value_with_tax_sum,2); ?></b></h5>
  </div>
</div>
<div id="datatable">
    <?php if(!empty($to_date)) { ?>
    <div id="grid_table" class="table-responsive table-wrap">
        <table >
            <tr class="jsgrid-header-row">
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">S.No.</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Image</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Order date</th>
                
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Product Code</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Product Name</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Customer name</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Customer number</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Invoice No.</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Brand</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Hsn/Sac</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Qty</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Unit Price without tax</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Unit Price with tax</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">UP/EXUP</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Tax rate</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Igst rate</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Cgst rate</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Sgst rate</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Igst value</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Cgst value</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Sgst value</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Total without tax</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Total tax</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Total value with tax</th>
                
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Payment Method</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Razorpay OrderId</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Bank Name</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Parent Category</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Sub Category</th>
                <th class="jsgrid-header-cell jsgrid-align-center table-heading">Category</th>
            </tr>
            
            <?php $i=$page; foreach($sales_report as $value){ 

                $sale_rate = $value->price_per_unit;
                $tax =  $value->tax_value;
                $inclusive_tax = $sale_rate - ($sale_rate * (100/ (100 + $tax)));

                $unit_price_without_tax =  $sale_rate - $inclusive_tax;
                $total_without_tax = $unit_price_without_tax * $value->qty;
                

                if($value->is_igst == 1)
                {
                    $igst = $value->tax_value;
                    $cgst = 0;$sgst = 0;
                    $cgst_val = 0;$sgst_val = 0;
                    $igst_val = $inclusive_tax;
                }
                else if($value->is_igst == 0)
                {
                    $cgst = $value->tax_value/2;
                    $sgst =$value->tax_value/2;
                    $cgst_val = $inclusive_tax/2;
                    $sgst_val = $inclusive_tax /2;
                    $igst=0;$igst_val=0;
                }

                $total_tax = $inclusive_tax*$value->qty;
                $total_value_with_tax = $total_without_tax + $total_tax;

                ?>
                <?php 
                    $category_list = [];
                    foreach ($cat_pro_map as $cat) {
                        if ($cat->pro_id == $value->prod_id) {
                            $category_list[] = '(' . $cat->name . ')';
                        } 
                    }
                    
                    ?>
            <tr class="jsgrid-filter-row">
                <th class="jsgrid-cell jsgrid-align-center"><?=++$i;?></th>
                <th class="jsgrid-cell jsgrid-align-center"><img style="cursor: pointer;"  height="35px" src="<?php echo IMGS_URL.$value->thumbnail?>" alt="" data-toggle="modal" data-target="#exampleModal<?php echo $i;?>"></th>
                <td class="jsgrid-cell jsgrid-align-center"><?= date_format_func($value->order_date);?> </td>
               
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->product_code; ?> </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->product_name; ?> </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->fname .' '.$value->mname.' '.$value->lname ?> </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->mobile; ?> </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->orderid; ?> </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->brand_name; ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->sku; ?> </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->qty; ?> </td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($unit_price_without_tax,2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($value->price_per_unit,2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">UP</td>
                <td class="jsgrid-cell jsgrid-align-center"><?= round($value->tax_value, 2); ?> %</td>
                <td class="jsgrid-cell jsgrid-align-center"><?= round($igst, 2); ?> %</td>
                <td class="jsgrid-cell jsgrid-align-center"><?= round($cgst, 2); ?> %</td>
                <td class="jsgrid-cell jsgrid-align-center"><?= round($sgst, 2); ?> %</td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($igst_val, 2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($cgst_val, 2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($sgst_val, 2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($total_without_tax,2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($total_tax,2); ?></td>
                <td class="jsgrid-cell jsgrid-align-center">₹ <?= round($total_value_with_tax,2); ?></td>
                
                <td class="jsgrid-cell jsgrid-align-center">
                <?php if($value->payment_method == 'cod')
                {
                    echo 'COD';
                }
                else
                {
                    echo "Razorpay";
                } ?>
                </td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->razorpay_order_id; ?></td>
                <td class="jsgrid-cell jsgrid-align-center"><?= $value->bank_name; ?></td>
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
                        <h5 class="modal-title" id="exampleModalLabel"><b><?php echo $value->product_name;?></b></h5>
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
        <span>Showing <?=$page+1?> to <?=$page+count($sales_report)?> of <?=$total_rows?> entries</span>
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
   function filter_sales_report(to_date)
   {
    if($("#subscription").prop('checked') == true)
    {
        var subscription  = 'true';
    }
    else
    {
        var subscription  = 'false';
    }
    var product_id  = $('#product_id').val();
    var plan_type_id  = $('#plan_type_id').val();
    if(document.getElementById('from_date').value == 0)
    {
        alert('Please Select From Date');
        document.getElementById('from_date').focus();
        $('#to_date').prop('value',0);
        return false;
    }
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
    var from_date = $("#from_date").val();
    var pm_id = $("#pm_id").val();
    var status_id  = $('#status_id').val();
    var brand_id  = $('#brand_id').val();
    var search  = $('#tb-search').val();
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
        url: "<?php echo base_url('sales-report/tb'); ?>",
        method: "POST",
        data: {
            from_date:from_date,
            to_date:to_date,
            pm_id:pm_id,
            status_id:status_id,
            brand_id:brand_id,
            search:search,
            parent_id:parent_id,
            parent_cat_id:parent_cat_id,
            child_cat_id:cat_id,
            subscription:subscription,
            product_id:product_id,
            plan_type_id:plan_type_id,
        },
        success: function(data){
            $("#tb").html(data);

            //ajax method for loading main categories
            // if(parent_cat_id != '')
            // {
                var parent_cat_id = $('#parent_cat_id').val();
                    $.ajax({
                        url: "<?php echo base_url('reports/fetch_cat'); ?>",
                        method: "POST",
                        data: {
                            parent_cat_id:parent_cat_id
                        },
                        success: function(data){
                            $(".child_cat_id").html(data);
                            if($("#subscription_value").val() == 'true')
                            {
                                $("#plan_type_id").prop('disabled',false);
                                $("#subscription").prop('checked', true);
                            }
                            else
                            {
                                $("#plan_type_id").prop('disabled',true);
                                $("#subscription").prop('checked', false);
                            }
                        },
                    });
            // }
        },
    });
   }
</script>


<script type="text/javascript">
   function filter_by_payment_mode(pm_id)
   {
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
    if($("#subscription").prop('checked') == true)
    {
        var subscription  = 'true';
    }
    else
    {
        var subscription  = 'false';
    }
    var product_id  = $('#product_id').val();
    var plan_type_id  = $('#plan_type_id').val();
    var from_date = $("#from_date").val();
    var to_date = $('#to_date').val();
    var status_id  = $('#status_id').val();
    var brand_id  = $('#brand_id').val();
    var search  = $('#tb-search').val();
    var parent_id  = $('#parent_id').val();
    var parent_cat_id  = $('#parent_cat_id').val();
    var cat_id  = $('#cat_id').val();

    $.ajax({
        url: "<?php echo base_url('reports/sales_report/tb'); ?>",
        method: "POST",
        data: {
            from_date:from_date,
            to_date:to_date,
            pm_id:pm_id,
            status_id:status_id,
            brand_id:brand_id,
            search:search,
            parent_id:parent_id,
            parent_cat_id:parent_cat_id,
            child_cat_id:cat_id,
            subscription:subscription,
            product_id:product_id,
            plan_type_id:plan_type_id,
        },
        success: function(data){
            $("#tb").html(data);
            if($("#subscription_value").val() == 'true')
            {
                $("#plan_type_id").prop('disabled',false);
                $("#subscription").prop('checked', true);
            }
            else
            {
                $("#plan_type_id").prop('disabled',true);
                $("#subscription").prop('checked', false);
            }
        },
    });
   };
   function filter_by_status(status_id)
   {
       $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
        if($("#subscription").prop('checked') == true)
        {
            var subscription  = 'true';
        }
        else
        {
            var subscription  = 'false';
        }
        var product_id  = $('#product_id').val();
        var plan_type_id  = $('#plan_type_id').val();
        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var pm_id = $("#pm_id").val();
        var brand_id  = $('#brand_id').val();
        var search  = $('#tb-search').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();
        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                status_id:status_id,
                brand_id:brand_id,
                search:search,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
                subscription:subscription,
                product_id:product_id,
                plan_type_id:plan_type_id,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
            },
        });
   };
   function filter_by_brand(brand_id)
   {
       $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
        if($("#subscription").prop('checked') == true)
        {
            var subscription  = 'true';
        }
        else
        {
            var subscription  = 'false';
        }
        var product_id  = $('#product_id').val();
        var plan_type_id  = $('#plan_type_id').val();
        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var pm_id = $("#pm_id").val();
        var search  = $('#tb-search').val();
        var status_id  = $('#status_id').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();
        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                status_id:status_id,
                brand_id:brand_id,
                search:search,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
                subscription:subscription,
                product_id:product_id,
                plan_type_id:plan_type_id,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
            },
        });
   };
   function filter_by_product(product_id)
   {
        if($("#subscription").prop('checked') == true)
        {
            var subscription  = 'true';
        }
        else
        {
            var subscription  = 'false';
        }
        var plan_type_id  = $('#plan_type_id').val();
       $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');

        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var pm_id = $("#pm_id").val();
        var search  = $('#tb-search').val();
        var status_id  = $('#status_id').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();
        var brand_id  = $('#brand_id').val();
        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                status_id:status_id,
                brand_id:brand_id,
                search:search,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
                product_id:product_id,
                subscription:subscription,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
            },
        });
   };
   function filter_by_subscription()
   {
    if($("#subscription").prop('checked') == true)
    {
        
        var subscription  = 'true';
    }
    else
    {
        var subscription  = 'false';
    }
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');

        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var pm_id = $("#pm_id").val();
        var search  = $('#tb-search').val();
        var status_id  = $('#status_id').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();
        var brand_id  = $('#brand_id').val();
        var product_id  = $('#product_id').val();
        var plan_type_id  = $('#plan_type_id').val();
        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                status_id:status_id,
                brand_id:brand_id,
                search:search,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
                product_id:product_id,
                subscription:subscription,
                plan_type_id:plan_type_id,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
                
            },
        });
   };
   function filter_by_plan_type(plan_type_id)
   {
    if($("#subscription").prop('checked') == true)
    {
        var subscription  = 'true';
    }
    else
    {
        var subscription  = 'false';
    }
    $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');

        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var pm_id = $("#pm_id").val();
        var search  = $('#tb-search').val();
        var status_id  = $('#status_id').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var cat_id  = $('#cat_id').val();
        var brand_id  = $('#brand_id').val();
        var product_id  = $('#product_id').val();
        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                status_id:status_id,
                brand_id:brand_id,
                search:search,
                parent_id:parent_id,
                parent_cat_id:parent_cat_id,
                child_cat_id:cat_id,
                product_id:product_id,
                subscription:subscription,
                plan_type_id:plan_type_id,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
                
            },
        });
   };
   function filter_by_subcategory(parent_cat_id)
   {
        $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
        if($("#subscription").prop('checked') == true)
        {
            var subscription  = 'true';
        }
        else
        {
            var subscription  = 'false';
        }
        var product_id  = $('#product_id').val();
        var plan_type_id  = $('#plan_type_id').val();
        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var search  = $('#tb-search').val();
        var status_id  = $('#status_id').val();
        var pm_id  = $('#pm_id').val();
        var parent_id  = $('#parent_id').val();
        var brand_id  = $('#brand_id').val();

        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                search:search,
                status_id:status_id,
                parent_cat_id:parent_cat_id,
                parent_id:parent_id,
                brand_id:brand_id,                
                product_id:product_id,
                subscription:subscription,
                plan_type_id:plan_type_id,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
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
   function fetch_products_by_cat(child_cat_id)
   {
        $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
        if($("#subscription").prop('checked') == true)
        {
            var subscription  = 'true';
        }
        else
        {
            var subscription  = 'false';
        }
        var product_id  = $('#product_id').val();
        var plan_type_id  = $('#plan_type_id').val();
        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var search  = $('#tb-search').val();
        var status_id  = $('#status_id').val();
        var pm_id  = $('#pm_id').val();
        var parent_id  = $('#parent_id').val();
        var parent_cat_id  = $('#parent_cat_id').val();
        var brand_id  = $('#brand_id').val();

        $.ajax({
            url: "<?php echo base_url('reports/sales_report/tb'); ?>",
            method: "POST",
            data: {
                from_date:from_date,
                to_date:to_date,
                pm_id:pm_id,
                search:search,
                status_id:status_id,
                parent_cat_id:parent_cat_id,
                parent_id:parent_id,
                child_cat_id:child_cat_id,
                brand_id:brand_id,
                subscription:subscription,
                product_id:product_id,
                plan_type_id:plan_type_id,
            },
            success: function(data){
                $("#tb").html(data);
                if($("#subscription_value").val() == 'true')
                {
                    $("#plan_type_id").prop('disabled',false);
                    $("#subscription").prop('checked', true);
                }
                else
                {
                    $("#plan_type_id").prop('disabled',true);
                    $("#subscription").prop('checked', false);
                }
            },
        });
  
   }
   $('#reset-data').click(function(){
        location.reload();
    })
</script>
<script type="text/javascript">


   function fetch_sub_categories(parent_id)
   {
        var from_date = $("#from_date").val();
        var to_date = $('#to_date').val();
        var pm_id  = $('#pm_id').val();
        var status_id  = $('#status_id').val();
        var brand_id  = $('#brand_id').val();
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
                    url: "<?php echo base_url('reports/sales_report/tb'); ?>",
                    method: "POST",
                    data: {
                        cat_id:cat_id,
                        parent_id:parent_id,
                        from_date:from_date,
                        to_date:to_date,
                        pm_id:pm_id,
                        status_id:status_id,
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


   $('#reset-data').click(function(){
        location.reload();
    })
</script>

