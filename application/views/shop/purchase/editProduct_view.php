<script type="text/javascript">
$(document).ready(function() {
    $(".needs-validation").validate({
        rules: {
            parent_id:"required",
            parent_cat_id:"required",
            description:"required",                                      
            name:"required",                                     
            product_code:"required",
            tax_id:"required",                                                       
            expiry_date:"required",                                                                                          
            mfg_date:"required", 
            url:{
               remote:"<?=$remote?>/url"
           },
        },
        messages: {
            url: {
                remote:"URL already exists Please change this URL!"
            },
        }
    }); 
});
</script>
<form class="edit_product needs-validation add-form redirect-page reload-location" action="<?=$action_url?>" method="post" enctype= multipart/form-data>

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
                <label class="control-label">Search Keyword:</label>
                <input type="text" class="form-control" name="search_keywords" value="<?php echo $value->search_keywords; ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label">Product Code:</label>
                <input type="text" class="form-control" name="product_code" value="<?php echo $value->product_code; ?>" readonly>
            </div>
        </div>
        
    </div>
    <div class="row">
       
            <div class="col-6">
                <div class="form-group">
                    <label class="control-label">Tax Slab:</label>
                    <select class="form-control select2" style="width:100%;" name="tax_id">
                    <option value="">Select Tax Slab</option>
                    <?php foreach ($tax_slabs as $slab) { ?>
                    <option value="<?php echo $slab->id; ?>,<?php echo $slab->slab; ?>" <?php if($slab->id == $value->tax_id){echo "selected";} ?>>
                        <?php echo $slab->slab; ?> %
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
            <div class="col-6" style="display:none">
                <div class="form-group">
                    <label class="control-label">Application</label>
                    <input type="file" name="application" class="form-control">
                </div>
                <?php if(!empty($value->application)) { ?>
                    <img src="<?php echo IMGS_URL.$value->application;?>" alt="<?php echo $value->name; ?>" height="50">
                <?php } ?> 
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Size Chart ( In CM )</label>
                    <input type="file" class="form-control" name="chart">
                </div>
                  <?php if(!empty($value->size_chart)) { ?>
                    <img src="<?php echo IMGS_URL.$value->size_chart;?>" alt="<?php echo $value->name; ?>" height="50">
                <?php } ?> 
            </div>
              <div class="col-6">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Size Chart ( In Inch )</label>
                    <input type="file" class="form-control" name="chart_inch">
                </div>
                  <?php if(!empty($value->size_chart_inch)) { ?>
                    <img src="<?php echo IMGS_URL.$value->size_chart_inch;?>" alt="<?php echo $value->name; ?>" height="50">
                <?php } ?>
            </div>
            <div class="col-6" style="display:none">
                <div class="form-group">
                <label class="control-label">Brand Name:</label>
                    <select class="form-control select2" style="width:100%;" name="brand_id">
                    <option value="">Select Brand</option>
                    <?php foreach ($brands as $brand) { ?>
                    <option value="<?php echo $brand->id; ?>,<?php echo $brand->name; ?>" <?php if($brand->id == $value->brand_id){echo "selected";} ?>>
                        <?php echo $brand->name; ?>
                    </option>
                    <?php } ?>
                    </select>
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
        <h3>SEO Friendly Meta</h3>
        <hr>
        <div class="row">
        <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Product URL :</label>
            <input type="text" class="form-control" name="url" value="<?=$value->url;?>" >
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Meta Title:</label>
            <input type="text" class="form-control" name="meta_title" value="<?=$value->meta_title;?>" >
        </div>
    </div>
    
    <div class="col-6">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Meta Keywords:</label>
            <input type="text" class="form-control" name="meta_keywords"  value="<?=$value->meta_keywords;?>" >
        </div>
    </div>
  
    <div class="col-12">
        <div class="form-group">
            <label for="recipient-name" class="control-label">Meta Description:</label>
            <textarea class="form-control" name="meta_description"  ><?=$value->meta_description;?></textarea>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <table id="propertyTable" class="table table-bordered">
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
                <?php $i = 1; foreach($property_val as $prop): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $prop->name; ?></td>
                    <td><?= $prop->is_selectable == '1' ? 'Display' : ($prop->is_selectable == '2' ? 'Filter' : 'Selectable'); ?></td>
                    <td>
                        <?php
                        if (empty($prop->value)) {
                            $query = $this->db->where('id', $prop->value_id)->get('product_props_value')->result();
                            echo $query[0]->value;
                        } else {
                            $desc = strip_tags($prop->value);
                            $desc = substr($desc, 0, 15);
                            echo "<span id='less{$prop->id}'>{$desc}</span><span id='more{$prop->id}' class='more'>{$prop->value}</span>";
                            if (strlen($prop->value) > 15) {
                                echo "<span id='dots{$prop->id}'>...</span><button class='btn btn-primary btn-sm' onclick='myFunction({$prop->id})' id='myBtn{$prop->id}'>Read more</button>";
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <input type="hidden" value="<?= $pid; ?>" id="pid">
                        <a href="javascript:void(0)" onclick="delete_prop_val(this, <?= $prop->id; ?>)"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="row">
        <div class="col-5">
        <div class="form-group">
        <label class="control-label">Properties:</label>
        <select class="form-control select2" style="width:100%;" name="props_id" id="propinput" onchange="filter_propsEdit()">
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

                <select class="form-control" id="prop_value" name="prop_value" style="width:100%;display:none;">
                    
                </select>
            </div>
        </div> 
        <div class="col-2">
            <button class="btn btn-danger" type="button" onclick="addPropertyEdit()" style="position: relative;top: 37px;">Add</button>
        </div>      
    </div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
    <button id="btnsubmit" type="submit" class="btn btn-primary waves-light"  ><i id="loader" class=""></i>Update</button>
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

 
function addPropertyEdit() {
    var selectedOption = $('#propinput').val().split(',');
    var propId = selectedOption[0];
    var propName = selectedOption[1];
    var propNameValue = selectedOption[2];
    var prop_type = $('#prop_value').val().split(',');
    var prop_typeID = prop_type[0];
    var prop_typeName = prop_type[1];

    // Check if the property already exists
    var exists = false;
    $('#propertyTable tbody tr').each(function() {
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

    var rowCount = $('#propertyTable tbody tr').length + 1;
    var newRow = '<tr>' +
        '<td>' + rowCount + '<input type="hidden" name="rowCount[]" value="' + rowCount + '"></td>' +
        '<td>' + propName + '<input type="hidden" name="propId[]" value="' + propId + '"></td>' +
        '<td>' + propNameValue + '<input type="hidden" name="propNameValue[]" value="' + propNameValue + '"></td>' +
        '<td>' + prop_typeName + '<input type="hidden" name="prop_typeID[]" value="' + prop_typeID + '"></td>' +
        '<td><a href="javascript:void(0)" onclick="deleteRowEdit(this)"><i class="fa fa-trash"></i></a></td>' +
        '</tr>';
    $('#propertyTable tbody').append(newRow);
    $('#valueinput').val('');
}

function deleteRowEdit(btn) {
    var row = $(btn).closest('tr');
    row.remove();
}

function delete_prop_val(btn, propid) {
    if (confirm('Do you want to delete?')) {
        $.ajax({
            url: '<?php echo base_url('purchase/delete_prop_val/'); ?>' + propid + '/<?= $pid; ?>',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Property Deleted Successfully..');
                    var row = $(btn).closest('tr');
                    row.remove();
                } else {
                    toastr.error('Failed to delete property value.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                toastr.error('Failed to delete property value.');
            }
        });
    }
}


function filter_propsEdit() {
        var prop_id = $("#propinput").val();
        var type = $("#propinput").find('option:selected');
        type = $(type).data('type');
        if (type == 2 || type == 3) {
            $("select[name='prop_value']").show();
            $("#valueinput").hide();
            $.ajax({
                url: "<?php echo base_url('purchase/get_properties_value'); ?>",
                method: "POST",
                data: {
                    prop_id:prop_id,
                },
                success: function(data){
                    console.log(data);
                    $("select[name='prop_value']").html(data);
                },
            });
        }else{
            $("select[name='prop_value']").hide();
            $("#valueinput").show();
        }
    }
</script>