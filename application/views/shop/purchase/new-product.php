<script type="text/javascript">
$(document).ready(function() {
    $(".needs-validation").validate({
        rules: {
            parent_id:"required",
            parent_cat_id:"required",
            description:"required",       
            tax_id:"required", 
            expiry_date:"required",                                                     
            mfg_date:"required", 
            NewPurchaseRate:"required",
            NewSellingRate:"required",
            brand_id:"required",
            NewLandingCost:"required",  
            unit_value:"required",
            ingredient_id:"required",
            unit_type_id:"required",    
            dpco:"required", 
            NewMrp:"required", 
            NewQty:"required", 
            product_code: {
                required:true,
                remote:"<?=$remote?>null/product_code"
            },
           name:{
               required:true,
               remote:"<?=$remote?>null/name"
           },
        },
        messages: {
            dpco:"Please select dpco or non dpco",
            ingredient_id:"Please select ingredient",
            brand_id:"Please select company name",
            unit_type_id:"Please select unit type",
            unit_value:"Please enter unit value",
            NewPurchaseRate:"Please enter purchase rate",
            NewSellingRate:"Please enter selling rate",
            NewLandingCost:"Please enter landing cost",       
            NewMrp:"Please enter mrp ", 
            NewQty:"Please enter qty",     
            product_code: {
                required : "Please enter product code!",
                remote : "Product code already exists!"
            },
            name: {
               required : "Please enter name !",
               remote : "Product Name already exists enter new product code!"
           },
          
        }
    }); 
});
</script>
<form class="add_product needs-validation add-form redirect-page reload-location" action="<?=$action_url?>" method="post" enctype= multipart/form-data>

    <div class="row">
<input type="hidden" value="<?=@$UnitCost;?>" name="UnitCost">
        <div class="col-4">
            <div class="form-group">
            <label class="control-label">Categories:</label>
                <div class="parent_cat_id" id="parent_cat_id" style="height: 250px;overflow: scroll;">
                    <?php 
                        foreach($parent_cat as $row){
                            //echo $row->name;
                            $checked1 = '';
                            foreach($cat_pro_map as $row_cat_id){ 
                                if ($row_cat_id->cat_id == $row->id) {
                                    $checked1 = 'checked';
                                }
                            }
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?= $row->id; ?>" name="cat_id[]" id="defaultCheck<?= $row->id; ?>" <?=$checked1;?>>
                        <label class="form-check-label" for="defaultCheck<?= $row->id; ?>"><?= $row->name; ?></label>
                    </div>
                    <?php
                        foreach($categories as $row2){
                            if ($row->id == $row2->is_parent) {
                                //echo $row2->name;
                                $checked2 = '';
                                foreach($cat_pro_map as $row_cat_id){ 
                                    if ($row_cat_id->cat_id == $row2->id) {
                                        $checked2 = 'checked';
                                    }
                                }
                    ?>
                    <div class="form-check ml-4">
                        <input class="form-check-input" type="checkbox" value="<?= $row2->id; ?>" name="cat_id[]" onclick="select_parent_cat(this, <?= $row->id; ?>)" id="defaultCheck<?= $row2->id; ?>" <?=$checked2;?>>
                        <label class="form-check-label" for="defaultCheck<?= $row2->id; ?>"><?= $row2->name; ?></label>
                    </div>
                    <?php
                            
                            foreach($categories as $row3){
                                if ($row2->id == $row3->is_parent) {
                                    //echo $row3->name;
                                    $checked = '';
                                    foreach($cat_pro_map as $row_cat_id){ 
                                        if ($row_cat_id->cat_id == $row3->id) {
                                            $checked = 'checked';
                                        }
                                    }
                    ?>
                    <div class="form-check ml-5">
                        <input class="form-check-input" type="checkbox" value="<?= $row3->id; ?>" name="cat_id[]" onclick="select_parent_cat(this, <?= $row->id; ?>, <?= $row2->id; ?>)" id="defaultCheck<?= $row3->id; ?>" <?=$checked;?>>
                        <label class="form-check-label" for="defaultCheck<?= $row3->id; ?>"><?= $row3->name; ?></label>
                    </div>
                    <?php
                                
                                }
                            }

                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="control-label">Product Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $value->name; ?>">
            </div>
        </div>
        <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Product Image:</label>
                    <input type="file" name="img[]" class="form-control"
                          size="55550" accept=".png, .jpg, .jpeg, .gif ,.webP, .svg" multiple="" required>
                </div>
            </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Search Keyword:</label>
                <input type="text" class="form-control" name="search_keywords" value="<?php echo $value->search_keywords; ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Product Code:</label>
                <input type="text" class="form-control" name="product_code" value="<?php echo $value->product_code; ?>" >
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Product Quantity:</label>
                <input type="number" class="form-control" name="unit_value" value="<?php echo $value->unit_value; ?>">
            </div>
        </div>
        <div class="col-6">
        <div class="form-group">
        <label class="control-label">Quantity Type:</label>
        <select class="form-control select2" style="width:100%;" name="unit_type_id">
        <option value="">Select Quantity Type</option>
        <?php foreach ($unit_type as $unit) { ?>
        <option value="<?php echo $unit->id; ?>,<?php echo $unit->name; ?>" <?php if($unit->id == $value->unit_type_id){echo "selected";} ?>>
            <?php echo $unit->name; ?>
        </option>
        <?php } ?>
        </select>
    </div>
        </div>
       
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Tax Slab:</label>
                    <select class="form-control select2" style="width:100%;" name="tax_id">
                    <option value="">Select Tax Slab</option>
                    <?php foreach ($tax_slabs as $slab) { ?>
                    <option value="<?php echo $slab->id; ?>,<?php echo $slab->slab; ?>" <?php if($slab->id == $value->tax_id){echo "selected";} ?>>
                        <?php echo $slab->slab; ?>
                    </option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Hsn/Sac Code:</label>
                    <input type="text" class="form-control" name="sku" value="<?php echo $value->sku; ?>">
                </div>
            </div>
<!--
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Application</label>
                    <input type="file" name="application" class="form-control">
                </div>
                <?php if(!empty($value->application)) { ?>
                    <img src="<?php echo IMGS_URL.$value->application;?>" alt="<?php echo $value->name; ?>" height="50">
                <?php } ?> 
            </div>
-->
            <div class="col-6">
                <div class="form-group">
                <label class="control-label">Company Name:</label>
                    <select class="form-control select2" style="width:100%;" name="brand_id">
                    <option value="">Select Company</option>
                    <?php foreach ($brands as $brand) { ?>
                    <option value="<?php echo $brand->id; ?>,<?php echo $brand->name; ?>" <?php if($brand->id == $value->brand_id){echo "selected";} ?>>
                        <?php echo $brand->name; ?>
                    </option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                <label class="control-label">Select Ingredients:</label>
                    <select class="form-control select2" style="width:100%;" name="ingredient_id">
                    <option value="">Select Ingredients</option>
                    <?php foreach ($ingredients as $ingredient) { ?>
                    <option value="<?php echo $ingredient->id; ?>,<?php echo $ingredient->title; ?>" <?php if($ingredient->id == $value->ingredient_id){echo "selected";} ?>>
                        <?php echo $ingredient->title; ?>
                    </option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                    <div class="form-group">
                    <label class="control-label">DPCO / Non DPCO:</label>
                        <select class="form-control select2" style="width:100%;" name="dpco">
                        <option value="">Select Category</option>
                            
                        <?php foreach ($dpco as $item) { ?>
                        <option value="<?php echo $item->id; ?>,<?php echo $item->title; ?>" <?php if($item->id == $value->dpco){echo "selected";} ?>>
                            <?php echo $item->title; ?>
                        </option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            <div class="col-6">
                <div class="form-group">
                <input type='checkbox' name='is_return' id="is_return" <?php if($value->is_return == '1' ){echo "checked";}  ?>/>
                <label for="is_return">Return Available</label>
                </div>
            </div>
    </div>  
    
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="control-label">Description:</label>
                <textarea id="editor" cols="92" rows="5" name="description"><?=$value->description?></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">

<div class="col-4">
    <div class="form-group">
        <label for="recipient-name" class="control-label">Purchase Rate:</label>
        <input type="number" class="form-control" name="NewPurchaseRate"  id="NewPurchaseRate" required value='0'>
    </div>
</div>

<div class="col-4">
    <div class="form-group">
        <label for="recipient-name" class="control-label">Landing Cost:</label>
        <input type="number" class="form-control" name="NewLandingCost" id="NewLandingCost" readonly required value='0'>
    </div>
</div>

<div class="col-4">
    <div class="form-group">
        <label for="recipient-name" class="control-label">MRP:</label>
        <input type="number" class="form-control"  id="NewMrp" name="NewMrp"  value='0' required>
    </div>
</div>

<div class="col-4">
<div class="form-group">
    <label for="recipient-name" class="control-label">Select Offer / Discount:</label>
    <select name="NewOffer" id="NewOffer" class="form-control select2" style="width:100%;">
        <option >--Select Offer--</option>
        <?php foreach($offers as $offer):?>
        <option value="<?=$offer->id;?>"><?=$offer->title;?> ( <?php if($offer->discount_type==1){ echo $offer->value."%";}elseif($offer->discount_type==0){echo $offer->value."OFF";} ;?> )</option>
        <?php endforeach;?>    
    </select>
</div>
</div>
<div class="col-4">
    <div class="form-group">
        <label for="recipient-name" class="control-label">Selling Rate:</label>
        <input type="number" readonly class="form-control" id="NewSellingRate" name="NewSellingRate" value='0' required>
    </div>
</div>
<div class="col-4">
    <div class="form-group">
        <label for="recipient-name" class="control-label">Stock Quantity:</label>
        <input type="number" class="form-control" id="NewQty" name="NewQty" value='0' min="0"  required>
    </div>
</div>
<input type="hidden" name="NewTaxRate" id="NewTaxRate" class="NewTaxRate">
   <input type="hidden" name="NewTaxAmount" id="NewTaxAmount" class="NewTaxAmount">
   <input type="hidden" name="NewOfferValue" id="NewOfferValue" class="NewOfferValue">
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-primary waves-light"  ><i id="loader" class=""></i>Submit</button>
</div>

</form>
<script type="text/javascript">
   function validateInputs() {
      var firstInputValue = $('.final_total').val();
      var secondInputValue = $(".advanced").val();
      if (parseInt(secondInputValue) > parseInt(firstInputValue)) {
        $(".advanced").val(firstInputValue),
       alert_toastr("error","Sorry subtract quantity not less than 0")
      } else {
        
      }
    }
      $(document).ready(function(){
        $("#sub_inventory").click(function(){
          $(".add_qty").hide();
          $(".sub_qty").show();
          $("#add_inventory").show();
          $("#sub_inventory").hide();
        });
        $("#add_inventory").click(function(){
          $(".add_qty").show();
          $(".sub_qty").hide();
          $("#add_inventory").hide();
          $("#sub_inventory").show();
        });
      });
   function fetch_category(parent_id)
   {
    //    alert(business_id);
    $.ajax({
        url: "<?php echo base_url('master-data/fetch_category'); ?>",
        method: "POST",
        data: {
            parent_id:parent_id
        },
        success: function(data){
            $(".parent_cat_id").html(data);
        },
    });
   }
  
</script>
  
    <script>
CKEDITOR.replace( 'editor', {
toolbar: [
{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
'/',
{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
'/',
{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
{ name: 'others', items: [ '-' ] },
]
});

 





    
$(document).ready(function() {
    $('select[name="tax_id"]').change(function() {
        ProductCalculation();
    });
    $('select[name="NewOffer"]').change(function() {
        ProductCalculation2(); 
    });
    $('#NewPurchaseRate').on('blur', function() {
        ProductCalculation();
    });
    $('#NewMrp').on('blur', function() {
    var newMrp = parseFloat($('#NewMrp').val()) || 0;
    var NewPurchaseRate = parseFloat($('#NewPurchaseRate').val()) || 0;
    var NewLandingCost = parseFloat($('#NewLandingCost').val()) || 0;
    
    if (newMrp < NewPurchaseRate) {
        toastr.error("MRP must be greater than or equal to Purchase Rate.");
        $('#NewMrp').val(NewLandingCost);
        $('#NewSellingRate').val(NewLandingCost);
        return;
    }
    $('#NewSellingRate').val(newMrp.toFixed(2));
    ProductCalculation2();
    });

    function ProductCalculation() {
    var ValuePer = 0;
    var ValueRate = 0;
    var OValue = 0;
    var OType = 0; 
    var TaxValue = $('select[name="tax_id"]').val();
    var values = TaxValue.split(',');
    var value1 = values[0]; 
    var value2 = values[1];
    var LandingPerValue=0;
    var LandingValue=0;
    var newPurchaseRate = parseFloat($('#NewPurchaseRate').val()) || 0;
    var newMrp = parseFloat($('#NewMrp').val()) || 0;
    var NewSellingRate = parseFloat($('#NewSellingRate').val()) || 0;
    var NewLandingCost = parseFloat($('#NewLandingCost').val()) || 0;
   
    LandingPerValue = ( newPurchaseRate * value2 )/100;
    LandingValue = newPurchaseRate+LandingPerValue;
    $('#NewLandingCost').val(LandingValue.toFixed(2));
    $('#NewMrp').val(LandingValue.toFixed(2));
    $('#NewSellingRate').val(LandingValue.toFixed(2));
    $('#NewTaxRate').val(value2);
    $('#NewTaxAmount').val(LandingPerValue.toFixed(2));
}

   function ProductCalculation2()
    {
      var newMrp = parseFloat($('#NewMrp').val()) || 0;
      var newOffer = parseFloat($('#NewOffer').val()) || 0; 
      var NewLandingCost = parseFloat($('#NewLandingCost').val()) || 0;
    if (newOffer) { 
            OfferValueGet(newOffer, function(OfferValue, DiscountType) {
                OValue = OfferValue; 
                OType = DiscountType; 
                calculateValue(); 
            });
        } else {
            calculateValue(); 
        }
     function calculateValue() {
        if (OValue > 0) {
            if (OType == 1) {
                ValuePer = (newMrp * OValue) / 100;
                ValueRate = newMrp - ValuePer;
            } else if (OType == 0) {
                ValuePer = OValue;
                ValueRate = newMrp - ValuePer;
            } else {
                ValuePer = 0;
                ValueRate = newMrp;
            }
        } else {
            ValuePer = 0;
            ValueRate = newMrp;
        }
        var FinalSel = ValueRate;
        if (FinalSel < NewLandingCost) {
        toastr.error("Selling Rate  should not less than Landing Price.");
        // $('#NewOffer').val('--Select Offer--').trigger('change.select2');
        return;
       }
        $('#NewOfferValue').val(ValuePer.toFixed(2));
        $('#NewSellingRate').val(FinalSel.toFixed(2));
    }
    }


    function OfferValueGet(id, callback) {
        $.ajax({
            url: '<?php echo base_url('purchase/getOffetValue');?>',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.res) { 
                    var getResult = data.row.value;
                    var getDiscountType = data.row.discount_type;
                    callback(getResult, getDiscountType);
                }
            },
            error: function(xhr, status, error) {
                callback('0', '0'); // Handle error case
            }
        });
    }
});
</script>