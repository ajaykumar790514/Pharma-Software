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
            NewLandingCost:"required",       
            NewMrp:"required", 
            NewQty:"required", 
            props_id:"required",   
            prop_value2:"required",
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
               remote : "Product Name already exists!"
           },
          
        }
    }); 
});
</script>
<form class="add_product needs-validation reload-tb" action="<?=base_url('purchase/add-new-product');?>" method="post">
   
      <div class="modal-body">
      <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="control-label">Categories:</label>
                <div class="parent_cat_id" id="parent_cat_id" style="height: 250px;overflow: scroll;">
                    <?php 
                        foreach($parent_cat as $row){
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?= $row->id; ?>" name="cat_id[]" id="defaultCheck<?= $row->id; ?>">
                        <label class="form-check-label" for="defaultCheck<?= $row->id; ?>"><?= $row->name; ?></label>
                    </div>
                    <?php
                        foreach($categories as $row2){
                            if ($row->id == $row2->is_parent) {
                                
                    ?>
                    <div class="form-check ml-4">
                        <input class="form-check-input" type="checkbox" value="<?= $row2->id; ?>" name="cat_id[]" onclick="select_parent_cat(this, <?= $row->id; ?>)" id="defaultCheck<?= $row2->id; ?>">
                        <label class="form-check-label" for="defaultCheck<?= $row2->id; ?>"><?= $row2->name; ?></label>
                    </div>
                    <?php
                            
                            foreach($categories as $row3){
                                if ($row2->id == $row3->is_parent) {
                                    
                    ?>
                    <div class="form-check ml-5">
                        <input class="form-check-input" type="checkbox" value="<?= $row3->id; ?>" name="cat_id[]" onclick="select_parent_cat(this, <?= $row->id; ?>, <?= $row2->id; ?>)" id="defaultCheck<?= $row3->id; ?>" >
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
    </div>
        <div class="row">

            <div class="col-12">
                <div class="form-group">
                    <label class="control-label">Product Name:</label>
                    <input type="text" class="form-control name" name="name" value="<?=$name;?>">
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
                    <input type="text" class="form-control" name="search_keywords">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Product Code:</label>
                    <input type="text" class="form-control code" name="product_code" >
                </div>
            </div>
            
           <div class="col-6">
             <div class="form-group">
                    <label class="control-label">Tax Slab:</label>
                    <select class="form-control select2" style="width:100%;" name="tax_id">
                   <option >Select Tax Slab</option>
                   <?php foreach ($tax_slabs as $value) { ?>
                    <option value="<?php echo $value->id; ?>,<?php echo $value->slab; ?>" <?php if($value->is_select=='1'){echo "selected";} ;?> >
                        <?php echo $value->slab; ?>%
                    </option>
                    <?php } ?>
                    </select>
                </div>
            </div> 
            
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Hsn/Sac Code:</label>
                    <input type="text" class="form-control" name="sku" >
                </div>
            </div>
            <div class="col-6"></div>
             <div class="col-6">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Size Chart ( In CM )</label>
                    <input type="file" class="form-control" name="chart">
                </div>
            </div>
             <div class="col-6">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Size Chart ( In Inch )</label>
                    <input type="file" class="form-control" name="chart_inch">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="control-label">Description:</label>
                    <textarea id="editor2" cols="92" rows="5" class="form-control" name="description"></textarea>
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
     
        </div>
        <h3>SEO Friendly Meta</h3>
        <hr>
        <div class="row">
   
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Meta Title:</label>
            <input type="text" class="form-control" name="meta_title" required>
        </div>
    </div>
    
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Meta Keywords:</label>
            <input type="text" class="form-control" name="meta_keywords"  required>
        </div>
    </div>
  
    <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Meta Description:</label>
            <textarea class="form-control" name="meta_description"  required></textarea>
        </div>
    </div>
    <hr>
   <input type="hidden" name="NewTaxRate" id="NewTaxRate" class="NewTaxRate">
   <input type="hidden" name="NewTaxAmount" id="NewTaxAmount" class="NewTaxAmount">
   <input type="hidden" name="NewOfferValue" id="NewOfferValue" class="NewOfferValue">
        </div>
        <div class="row">
            <div class="col-12">
                <table id="propertyTable3" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Property Name</th>
                            <th>Property Value</th>
                            <th>Property Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table body will be dynamically populated -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
        <div class="col-5">
        <div class="form-group">
        <label class="control-label">Properties:</label>
        <select class="form-control select2" style="width:100%;" name="props_id" id="propinput" onchange="filter_props()">
        <option value="">Select Property</option>
        <?php $properties    = $this->master_model->get_data('product_props_master','active','1');
         foreach ($properties as $prop) { ?>
        <option value="<?php echo $prop->id; ?> , <?php echo $prop->name; ?> ,<?= $prop->is_selectable == '1' ? 'Display':''; ?>
             <?= $prop->is_selectable == '2' ? 'Filter':''; ?>
             <?= $prop->is_selectable == '3' ? 'Selectable':''; ?>" data-type="<?php echo $prop->is_selectable; ?>">
            <?php echo $prop->name; ?> (   <?= $prop->is_selectable == '1' ? 'Display':''; ?>
             <?= $prop->is_selectable == '2' ? 'Filter':''; ?>
             <?= $prop->is_selectable == '3' ? 'Selectable':''; ?>)
        </option>
        <?php } ?>
        </select>
    </div>
        </div>
        <div class="col-5">
            <div class="form-group">
                <label class="control-label">Property Value</label>
                <textarea name="value" class="form-control"  id="valueinput"></textarea>

                <select class="form-control" id="prop_value2" name="prop_value2" style="width:100%;display:none;">
                    
                </select>
            </div>
        </div> 
        <div class="col-2">
            <button class="btn btn-danger" type="button" onclick="addProperty()" style="position: relative;top: 37px;">Add</button>
        </div>      
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="btnsubmit" type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>



      <script src="//cdn.ckeditor.com/4.10.0/full-all/ckeditor.js"></script>
<script>
    $(document).ready(function() {
    var $roundoffSection = $("#roundoff_section").clone();
    $roundoffSection.find("[name='roundoff']").attr("id", "edit_roundoff");
    $("#roundoff_edit").attr("data-content", $roundoffSection.html());
    
    $('#roundoff_edit').popover({
        html: true,
        trigger: 'click',
        placement: 'bottom'
    }).on('shown.bs.popover', function () {
        $("#edit_roundoff").focus();
        $('[data-popover-close]').on('click', function() {
            $('#roundoff_edit').popover('hide');
        });
    });
});

function setRoundoff() {
}

    
function addProperty() {
    var selectedOption = $('#propinput').val().split(',');
    var propId = selectedOption[0];
    var propName = selectedOption[1];
    var propNameValue = selectedOption[2];
    var prop_type = $('#prop_value2').val().split(',');
    var prop_typeID = prop_type[0];
    var prop_typeName = prop_type[1];
    
    // Check if the property already exists
    var exists = false;
    $('#propertyTable3 tbody tr').each(function() {
        var existingPropName = $(this).find('input[name="propId[]"]').val();
        var existingPropType = $(this).find('input[name="prop_typeID[]"]').val();
        if (existingPropName === propId || existingPropType === prop_typeID) {
            exists = true;
            return false; // Exit the loop early
        }
    });
    
    if (exists) {
        toastr.error('Property already exists!');
        return; // Exit the function early
    }

    var rowCount = $('#propertyTable3 tbody tr').length + 1;
    var newRow = '<tr>' +
        '<td>' + rowCount + '<input type="hidden" name="rowCount[]" value="' + rowCount + '"></td>' +
        '<td>' + propName + '<input type="hidden" name="propId[]" value="' + propId + '"></td>' +
        '<td>' + propNameValue + '<input type="hidden" name="propNameValue[]" value="' + propNameValue + '"></td>' +
        '<td>' + prop_typeName + '<input type="hidden" name="prop_typeID[]" value="' + prop_typeID + '"></td>' +
        '<td><button class="btn btn-danger" onclick="deleteRow(this)">Delete</button></td>' +
        '</tr>';
    $('#propertyTable3 tbody').append(newRow);
    $('#valueinput').val('');
}


function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

        function filter_props() {
        var prop_id = $("#propinput").val();
        var type = $("#propinput").find('option:selected');
        type = $(type).data('type');
        if (type == 2 || type == 3) {
            $("select[name='prop_value2']").show();
            $("#valueinput").hide();
            $.ajax({
                url: "<?php echo base_url('purchase/get_properties_value'); ?>",
                method: "POST",
                data: {
                    prop_id:prop_id,
                },
                success: function(data){
                    console.log(data);
                    $("select[name='prop_value2']").html(data);
                },
            });
        }else{
            $("select[name='prop_value2']").hide();
            $("#valueinput").show();
        }
    }
   function select_parent_cat(btn,cat_id1,cat_id2){
    // console.log(btn);
    $('#defaultCheck'+cat_id1).prop('checked', true);
    $('#defaultCheck'+cat_id2).prop('checked', true);
   }
CKEDITOR.replace( 'editor2', {
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