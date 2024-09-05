<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<style>
  .parsley-errors-list li {
    color: red;
}
.card-header{
    padding: 0.5rem 0.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, .03);
    border-bottom: 1px solid rgba(0, 0, 0, .125);
    height: 45px;
}
    #spinner-div {
  position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 20%;
  left: 0;
  text-align: center;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 99999;
}
</style>
<div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor">Dashboard</h3>
                     <?php echo $breadcrumb;?>
                    </div>
                </div>
<div class="modal " id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="productModalBody">
        ...
      </div>
    </div>
  </div>
</div>

<div class="modal " id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="productModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel1">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="productDuplicate">
        ...
      </div>
    </div>
  </div>
</div>



<div class="modal " id="new-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="create-new-product">
        ...
      </div>
    </div>
  </div>
</div>

<div class="modal " id="new-product-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body" id="product-data">
        ...
      </div>
    </div>
  </div>
</div>


<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- Row -->
<div class="row">
   <div id="spinner-div" class="pt-5">
    <div class="spinner-border text-primary" role="status">
    </div>
</div>
<?php 
	$attributes = array('id' => 'edit_new_purchase','data-parsley-validate' => true,'class' => 'form-horizontal');
	echo form_open_multipart('', $attributes);?>
	<p><?php echo validation_errors(); ?></p>
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
        <div class="card-header">
                <div class="row">
                <div class="col-12">
                <div class="d-flex flex-wrap">
                    <div class="float-left col-md-6 col-lg-6 col-sm-6">
                    <h4 class="card-title" id="test">Supplier Details</h4>
                    </div>
                <div class="float-left col-md-6 col-lg-6 col-sm-6">
                <a class="float-right btn  btn-sm btn-danger" href="<?php echo base_url('purchase/'.$menu_id);?>">Back</a>
                  </div>
                </div>
              </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                 
                    <div class="col-3">
                       <div class="row">
                       <div class="col-12">
                        <label for="supplier">Select Supplier <span class="text-danger">*</span></label>
                        <select name="supplier" id="supplier" class="form-control select2" required>
                        </select>
                    </div>
                       </div>
                       <input type="hidden" class="supplier2" id="supplier2" name="supplier2" value="<?php echo $purchase['supplier_id'];?>">
                       <input type="hidden" class="supplierName" id="supplierName" value="<?php echo $purchase['vendor_name'];?>">
                    </div>
                    <div class="col-3">
                       <div class="row">
                        <div class="col-12">
                        <label for="">Purchase Order Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" name="purchase_order_date" id="purchase_order_date" value="<?php echo $purchase['purchase_order_date'];?>"  max="<?php echo date('Y-m-d') ;?>" required>
                        </div>
                      </div>
                   </div>
                     <div class="col-3">
                     <div class="row">
                        <div class="col-12">
                        <label for="">Purchase Order No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="purchase_order_no" id="purchase_order_no" placeholder="Enter no"  value="<?php echo $purchase['purchase_order_no'];?>"  required>
                        </div>
                      </div>
                    </div>
                    <div class="col-3">
                    <div class="row">
                        <div class="col-12">
                        <label for="">Shipping  Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo $purchase['shipping_date'];?>" max="<?php echo date('Y-m-d') ;?>" name="shipping_date" id="shipping_date"  required>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mt-3">
                     <div class="row">
                        <div class="col-12">
                            <span>Place of Supply: - </span>
                            </div>
                            <div class="col-12">
                            <span id="name">Name: - <?php echo $purchase['vendor_name'];?></span>
                            </div>
                            <div class="col-12">
                            <span id="gst">GSTIN: - <?php echo $purchase['gstin'];?></span>
                            </div>
                            <div class="col-12">
                            <span id="address">Billing Address <?php echo $purchase['address'].' '.$purchase['state_name'].' '.$purchase['city_name'].' '.$purchase['pincode'];?></span>
                            </div>
                            <div class="col-12">
                            <span id="mobile">Mobile <?php echo $purchase['mobile'];?></span>
                        </div>
                     </div>
                    </div>
                    <div class="col-6 mt-3">
                     <div class="row">
                        <div class="col-12">
                        <label for="">Shipping  Note <span class="text-danger">*</span></label>
                        <textarea name="shipping_note" id="shipping_note" class="form-control form-control-sm"  required><?php echo $purchase['shipping_note'];?></textarea>
                        </div>
                     </div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header pl-3 pt-2"><h4>Product Details</h4></div>
            <div class="card-body">
                <div class="row">
                 <div class="col-md-12 overflow-hidden mt-4">
                    <div class="table-responsive">
                     <table class="table table-sm m-table table-bordered table-hover mb-0 rooms_tb" id="product_table">
                      <thead>
                        <tr class="text-center">
                        <th class="paction">  <a  title="Add New" class="btn btn-outline-info m-btn m-btn--icon m-btn--icon-only m-btn--pill add-row" data-add-item id="add_item"> <i class="fa fa-plus" aria-hidden="true"></i></a></th>
                        <th class="psrno">#</th>
                        <th class="pitemcode">Item Code/Barcode<span style="font-size:1.25rem;" class="text-danger">*</span></th>
                        <th class="pname">Product Name<small style="font-size:1.25rem; color:red;"
                        class="text-danger">*</small></th>
                        <th class="pqty">Qty<small style="font-size:1.25rem; color:red;"
                        class="text-danger">*</small></th>
                        <th class="pqty  ">UOM</th>
                        <th class="pprice">Unit Cost<small style="font-size:1.25rem; color:red;"
                        class="text-danger">*</small></th>
                        <th class="ptax text-center" style="width: 100px;">Tax</th>
                        <th class="plcost" style="width: 100px;">If Offer</th>
                        <th class="plcost">Landing Cost</th>
                        <th class="plcost ">Margin (%)<small style="font-size:1.25rem; color:red;"
                        class="text-danger">*</small></th>
                        <th class="ptotal text-right">Total<small style="font-size:1.25rem; color:red;"
                        class="text-danger">*</small></th>
                        </tr>
                        </thead>
                        <tbody style="height: 10px !important; overflow: scroll; "  data-purchase-list="">
                        <?php  $i=1;foreach($purchase_item as $item):
                             $selling_rate = $item['mrp'];
                             if($item['discount_type']=='1')
                             {
                                 $selling_per = ($item['qty']*$selling_rate * $item['offer_upto'])/100;
                                 $selling_rate = ($item['qty']*$selling_rate) - $selling_per;
                                 $per = $item['offer_upto'];
                                 
                             }else
                             if($item['discount_type']=='0'){
                                 $selling_per = $item['offer_upto'];
                                 $per = $item['offer_upto'];
                                 $selling_rate = ($item['qty']*$selling_rate) - $item['offer_upto'];
                             }else
                             {
                                 $selling_per =0;
                                 $selling_rate = $item['qty']*$selling_rate;
                             }
                              $current_qty = $item['inventory_qty'];
                              $purchase_qty = $item['qty'];
                              $diff_qty = $purchase_qty - $current_qty;
                              if ($diff_qty < 0) {
                                 $diff_qty = 0;
                               }

                             
                             ?>
                             <input type="hidden" value="<?php echo $item['item_id'];?>" name="exist_item[]">
                             <input type="hidden" value="<?php echo $item['id'];?>" name="purchaseId[]" id="purchaseId"> 
                            
                             <input type="hidden" value="<?php echo $item['inventtory_id'];?>" 
                             name="inventtory_id[]">   
                             <input type="hidden" value="<?php echo $diff_qty;?>" name="diff_qty[]"> 
                        <tr class="text-center rooms_row" data-purchase-item="template">
                        <td class="paction mt-2" style="display: flex;">
                        <a  id="remove_item" data-item-remove=""  class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill remove-row"><i class="fa fa-times"></i></a>
                      
                        </td>
                        <td class="psrno"><span id="itemIndex" data-item-index><?php echo  $i;?></span>
                         <input type="hidden" id="indexNo" class="indexNo" value="1" name="indexNo[]" ></td>
                        <td class="pitemcode">
                        <div class="p-0 text-right m--font-bolder form-group mb-0">
                        <input type="text" class="form-control form-control-sm" name="itemCode[]" id="itemCode" placeholder="ItemCode" required value="<?php echo $item['product_code'];?>"  data-placement="top"/>
                        </div>  
                        <input type="hidden" value="<?php echo $item['id'];?>" id="purchase_item_id"> 
                        <input type="hidden" value="<?php echo $item['purchase_id'];?>" id="purchase_id"> 
                        </td>
                        <td align="center" class="pname productVarientId-container" width="200px">
                        <select class="form-control  productVarientId" id="productVarientId" name="productVarientId[]"
                        placeholder="Select Product" required>
                        <option value="<?php echo $item['item_id'];?>"><?php echo $item['name'];?></option>
                        </select>
                        <button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" data-product-id="<?php echo $item['item_id'];?>" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2 btn-sm btn-primary" title="Duplicate Product" id="duplicate_item"  data-product-id="<?php echo $item['item_id'];?>"><i class="mdi mdi-content-duplicate"></i></button>
                        </td>
                        <td class="pqty">
                        <div class="p-0 text-right m--font-bolder form-group mb-0">
                        <div class='d-flex align-items-center'>
                        <div>
                        <input type="text" class="form-control form-control-sm text-center quantity1 qty" name="quantity[]" id="quantity" onkeydown="check(event,this)"
                        placeholder="Qty"  data-placement="top" data-debitnoteqty="0" required value="<?php echo $item['qty'];?>"/>
                       </div>
                       </div>
                       </td>
                       <td class="puom ">
                       <div class="p-0 text-right m--font-bolder form-group mb-0">
                       <input type="text" readonly="readonly" class="form-control form-control-sm"
                       name="uom[]" id="uom" placeholder="UOM" data-placement="top" required value="<?php echo $item['unit_type'];?>"/>
                       </div>
                       </td>
                       <td class="pprice">
                       <div class="p-0 text-right m--font-bolder form-group mb-0">
                       <input type="text" class="form-control form-control-sm unitCost" name="unitCost[]" id="unitCost"  onkeydown="check(event,this)"  required value="<?php echo $item['unit_cost'];?>"/>
                       </div>
                       </td>
                      <td align="center" class="ptax" style="width:100px;text-align:center">
                      <div class="p-0">
                      <span data-item-cess-amount="" id="data-item-cess-amount"><span style='font-size:10px;color:blue'>GST <?php echo $item['tax_value'] ;?> %</span><br>
                      <span data-item-tax-amount=""
                      id="data-item-tax-amount" style="font-size:14px;"><?=$shop_details->currency;?>. 0</span> 
                      <input type="hidden" name="taxAmount[]" id="taxAmount" value="<?php echo $item['tax_value'];?>" />
                      <input type="hidden" name="taxRate[]" id="taxRate" value="<?php echo $item['tax'];?>" />
                      <input type="hidden" name="mrpp[]" id="mrpp" value="<?php echo $item['mrp'];?>" />
                      <input type="hidden" name="SellRate[]" id="SellRate" value="<?php echo $item['mrp'];?>" />
                      <input type="hidden" name="discount_value[]" id="discount_value" value="<?php echo $item['discount_value'];?>" />
                     </div>
                     </td>
                     <td class="plcost">
                     <div class="p-0">
                    <span data-item-cess-amount="" id="data-item-offer-name" style="font-size:10px;color:blue"><?php echo $item['offer_upto'];?> %</span><br>
                       <span data-item-tax-amount=""
                      id="data-item-offer-amount" style="font-size:14px;"><?=$shop_details->currency;?>. <?php echo $selling_per;?></span>
                      <input type="hidden" name="offerCost[]" id="offerCost" value="<?php echo $selling_per;?>"  />
                     </div>
                     </td>
                     <td class="plcost">
                     <input  type="text" class="form-control form-control-sm text-right"
                     name="landingCost[]" id="landingCost" readonly="readonly" value="<?php echo $item['landing_cost'];?>" required/>
                     </td>
                     <td class="plcost ">
                     <input  type="text" class="form-control form-control-sm text-right"
                     name="margin[]" id="margin" readonly="readonly"
                     value="0" required value="<?php echo $item['margin'];?>" />
                     <input type="hidden" id="catMargin" class="catMargin" value="<?php echo $item['mrp'];?>">
                    </td>
                     <td class="ptotal">
                     <input  type="text" class="form-control form-control-sm text-right"
                     name="netAmount[]" required id="netAmount" onkeydown="check(event,this)" placeholder="Amout" data-item-amount="" value="<?php echo $item['total'];?>" value="0"  />
                     </td>
                     </tr>
                     <?php $i++; endforeach;?>
                     </tbody>
                     <tfoot>
                     <tr>
                     <th></th>
                     <th></th>
                     <th></th>
                     <th class="text-center">Total</th>
                     <th class="text-center"><span class="m--font-boldest" id="product_qty_total"><?php echo $purchase['total_qty'];?></span>
                     </th>
                     <th></th><th></th>
                     <th class="text-right">Rs.<span id="tax_total"><?php echo $purchase['total_tax'];?></span>
                     </th>
                     <th></th>
                     <th></th>
                     <th class="text-right "></th>
                     <th><span class="m--font-boldest float-right" id="product_sub_total"><?php echo $purchase['total_amount']+$purchase['total_tax'];?></span></th>
                    </tr>
                    </tfoot>
                    </table>
                 </div>
              </div>
           </div>
        </div>
     </div>
   </div>
   <div class="col-lg-12 col-md-12">
     <div class="card">
       <div class="card-header pl-3 pt-2"><h4>Product Total</h4></div>
          <div class="card-body">
          <div class="m-portlet mb-0">
             <div class="m-form__actions card-body-sm">
                <div class="row">
                   <div class="col-lg-8 col-md-8 col-sm-12">
                     <div>
                     <label class="pt-0">Note</label>
                     <div class="col-md-12 p-0">
                     <div class="input-group ">
                     <textarea class="form-control form-control-sm" name="notes" placeholder="Enter Note" rows="2"><?php echo $purchase['remark'];?></textarea>
                     </div>
                     </div>
                     </div>
                     </div>
                     <div class="col-lg-4 col-md-4 col-sm-12" id="tax_summary_hide">
                     <div class="col-md-12 p-0">
                      <table class="table table-sm table-bordered text-right mb-0">
                        <tbody>
                         <tr>
                         <th>Flat Discount</th>
                         <td class="mb-0 form-group">
                         <div class="d-flex justify-content-end">
                         <input type="number" id="flatDiscount" oninput="calculate_amount()" name="flatDiscount" onkeydown="check(event,this)"  value="<?php echo $purchase['flat_discount'];?>" class="form-control form-control-sm rounded-0 flatDiscount col-3 text-right" value="0"    >
                         <button class="btn btn-sm btn-primary" fdprocessedid="mxziam">%</button>
                         </div>
                         </td>
                         </tr>
                         <tr>
                         <th>Gross Amount</th>
                         <td><h6 class="mb-0 gross_amount" id="gross_amount"><?php echo $purchase['gross_total'];?></h6>
                          <input type="hidden" value="<?php echo $purchase['gross_total'];?>" name="GrossTotal" id="GrossTotal" class="GrossTotal"></td>
                         </tr>
                         <tr>
                         <th><h6 class="mb-0">Discount</h6></th>
                         <td><h6 class="mb-0" id="total_disc"><?php echo $purchase['flat_discount_value'];?></h6>
                          <input type="hidden" name="FinalDiscount" id="FinalDiscount" value="<?php echo $purchase['flat_discount_value'];?>"></td>
                         </tr>
                         <tr>
                         <th><h6 class="mb-0">Taxable Amount</h6></th>
                         <td><h6 class="mb-0 AmountwithoutTax" id="taxable_amount"><?php echo $purchase['total_amount']+$purchase['total_tax'];?></h6>
                        <input type="hidden" value="<?php echo $purchase['total_amount']+$purchase['total_tax'];?>" name="FinalTaxWithAmount" id="FinalTaxWithAmount"></td>
                         </tr>
                         <tr>
                         <th class="m--font-info">
                         <a href="#tax_summary_table"  data-toggle="collapse" class="m-link text-primary m-link--info m-link--state">Tax</a>
                         </th>
                         <td><h6 class="mb-0 tax_amount" id="tax_amount"><?php echo $purchase['total_tax'];?></h6>
                           <input type="hidden" name="FinalTax" id="FinalTax" value="<?php echo $purchase['total_tax'];?>"></td>
                         </tr>
                         <tr>
                         <th>
                         <a tabindex="-1" href="javascript:void(0);"
                          data-toggle="m-popover" data-trigger="click" title="Roundoff" data-html="true"
                          id="roundoff_edit" data-content="" class="m-link text-primary m-link--info m-link--state">Roundoff</a>
                              </th>
                              <td><h6 class="mb-0" id="round_off">0.0</h6></td>
                               </tr>
                               <tr>
                               <th><h4 class="mb-0">Net Amount</h4></th>
                               <td><h4 class="mb-0 net_amount" id="net_amount"><?php echo $purchase['total_amount'];?></h4>
                                  <input type="hidden" id="FinalTotal" name="FinalTotal" value="<?php echo $purchase['total_amount'];?>">
                                  <input type="hidden" id="FinalQty" name="FinalQty" value="<?php echo $purchase['total_qty'];?>"></td>
                                </tr>
                                                    
                               </tbody>
                             </table>
                          </div>
                         </div>
                      </div>
                     </div>
                 </div>
          </div>
          <div class="card-footer">
            <div class="row">
                <div class="col-11"></div>
                <div class="col-1">
                    <button type="submit" name="submit" id="add-purchase" class="btn btn-primary">Submit</button>
                </div>
            </div>
          </div>
     </div>   
   </div>
   </form>
              
</div>
</div>



<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->

<!-- //###### ANKIT MAIN CONTENT  ######// -->
<div class="modal  text-left" id="showModal-xl"  role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel21">......</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              
          </div>
          <!-- <div class="modal-footer">
              <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
      </div>
  </div>
</div>


<div class="modal  text-left" id="showModal"  role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel21">......</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              
          </div>
          <!-- <div class="modal-footer">
              <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
      </div>
  </div>
</div>
<input type="hidden" name="ProductName" id="ProductName" class="ProductName">
<div class="modal " id="new-product"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form class="add_product needs-validation reload-tb" action="<?=base_url('purchase/add-new-product');?>" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
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
                    <input type="text" class="form-control name" name="name">
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
                    <textarea id="editor" cols="92" rows="5" class="form-control" name="description"></textarea>
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

                <select class="form-control" id="prop_value" name="prop_value" style="width:100%;display:none;">
                    
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
    </div>
  </div>
</div>
<style>
.more {display: none;}
</style>
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
            prop_value:"required",
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
<script src="//cdn.ckeditor.com/4.10.0/full-all/ckeditor.js"></script>
<script>
function addProperty() {
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
        var existingPropName = $(this).find('input[name="propNameValue[]"]').val();
        var existingPropType = $(this).find('input[name="prop_typeID[]"]').val();
        if (existingPropType === prop_typeID) {
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
        '<td><button class="btn btn-danger" onclick="deleteRow(this)">Delete</button></td>' +
        '</tr>';
    $('#propertyTable tbody').append(newRow);
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
   function select_parent_cat(btn,cat_id1,cat_id2){
    // console.log(btn);
    $('#defaultCheck'+cat_id1).prop('checked', true);
    $('#defaultCheck'+cat_id2).prop('checked', true);
   }
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
</script>
<script src="https://parsleyjs.org/dist/parsley.js"></script>
<script>
    $(document).ready(function() {
    var CI_ROOT = "<?=base_url();?>";    
    $('#edit_new_purchase').parsley();
    $('#edit_new_purchase').on('submit', function(event){
       $('#spinner-div').show();
        event.preventDefault();
        let formdata = new FormData(this);
        if ($('#edit_new_purchase').parsley().isValid()) {
            $.ajax({
                method: 'POST',
                url: CI_ROOT + 'purchase/edit-purchase/<?php echo $purchase_id;?>',
                data: formdata,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                   $('#spinner-div').hide();
                    if (response.status === 'error') {
                        Swal.fire("Error", response.message, "error");
                    } else if (response.status === 'success') {
                        Swal.fire("Success", response.message, "success");
                        window.location.href = CI_ROOT + 'purchase/'+ <?=@$menu_id;?>;
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire("Error", "An error occurred while processing your request. Please try again later.", "error");
                }
            });
        }
    });
});
    $(document).ready(function() {
        INSERTSUPPLIER = "1";
        $("#supplier").select2({
                    placeholder: "Search Supplier",
                    allowClear: 1,
                    ajax: {
                        url: '<?php echo base_url();?>purchase/search_supplier',
                        dataType: "json",
                        type: "POST",
                        delay: 250,
                        data: function (e) {
                            var name1=e.term;
                            $('.supplierName').val(name1);
                            return {
                                contactname: e.term,
                                page: e.page
                            }
                        },
                        processResults: function (e, t) {
                            return t.page = t.page || 1, {
                                results: e.items,
                                pagination: {
                                    more: 30 * t.page < e.total_count
                                }
                            }
                        },
                        cache: !0
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 1,
                    language: {
                        noResults: function () {
                            if(INSERTSUPPLIER == 1){
                                var searchValue = $("#supplierName").val();
                                var url1 = '<?=base_url('purchase/supplier-modal/');?>' + searchValue;
                                return '<div class="m-demo-icon"> <button class="float-right btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Add Supplier" data-url="'+url1+'" ><i class="mdi mdi-plus"></i>Add Supplier</button></div>';	
                            }else{
                                return "";
                            }
                            
                        
                        }
                        },
                    templateResult: function (e) {
                        if (e.loading) return e.text;
                        return e.text
                    },
                    templateSelection: function (data, container) {
                        $(data.element).attr('data-contact-type', data.contact_type);
                        return data.text;
                    }
                }).on('select2:select', function (e) {
                    var selectedSupplierId = e.params.data.id;
                    $.ajax({
                        url: '<?php echo base_url();?>purchase/get_supplier_details',
                        type: 'POST',
                        dataType: 'json',
                        data: { supplierId: selectedSupplierId },
                        success: function (response) {
                            console.log(response);
                            if(response.success)
                            {
                                
                            $('#name').html('<span>Name:- <b>'+response.row.name+'</b></span>');
                            $('#gst').html('<span>GSTIN:- <b>'+response.row.gstin+'</b></span>');
                            $('#address').html('<span>Billing Address:- <b>'+response.row.address+' '+ response.row.pincode +' '+response.row.city_name+' '+response.row.state_name+'</b></span>');
                            $('#mobile').html('<span>Mobile:- <b>'+response.row.mobile+'</b></span>');
                            $("#product_table tbody").removeClass('');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching supplier details:", error);
                        }
                    });
                });
      
    });
    <?php if(isset($purchase['supplier_id']) && isset($purchase['vendor_name'])): ?>
    $(document).ready(function() {
        var supplierId = <?php echo $purchase['supplier_id']; ?>;
        var supplierName = '<?php echo $purchase['vendor_name']; ?>';
        
        if ($('#supplier option[value="' + supplierId + '"]').length === 0) {
            var option = new Option(supplierName, supplierId, true, true);
            $('#supplier').append(option).trigger('change');
        }
    });
<?php endif; ?>


    
</script>
<script type="text/javascript">
    
$('#showModal-xl').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 
    var recipient = button.data('whatever') 
    var data_url  = button.data('url') 
    var modal = $(this)
    $('#showModal-xl .modal-title').text(recipient)
    $('#showModal-xl .modal-body').load(data_url);
})

$('#showModal').on('show.bs.modal', function (event) {
    
    var button = $(event.relatedTarget) 
    var recipient = button.data('whatever') 
    var data_url  = button.data('url') 
    var modal = $(this)
    $('#showModal .modal-title').text(recipient)
    $('#showModal .modal-body').load(data_url);
})

$(document).on('click','[data-dismiss="modal"]', function(event) {
    $('#showModal .modal-body').html('');
    $('#showModal .modal-body').text('');
})


$(document).on('click', '.pag-link', function(event){
    document.body.scrollTop = 0; 
    document.documentElement.scrollTop = 0;
    var search = $('#tb-search').val();
    $.post($(this).attr('href'),{search:search})
    .done(function(data){
        $('#tb').html(data);
    })
    return false;
})

$(document).on("submit", '.add_supplier', function(event) {
    var element = document.getElementById("loader");
        element.className = 'fa fa-spinner fa-spin';
        $("#btnsubmit").prop('disabled', true);
    event.preventDefault(); 
    $this = $(this);
      debugger;
    if ($this.hasClass("needs-validation")) {
        if (!$this.valid()) {
            return false;
        }
    }
    $.ajax({
      url: $this.attr("action"),
      type: $this.attr("method"),
      data:  new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      success: function(data){
        console.log(data);
        // return false;
            debugger;
        data = JSON.parse(data);
        
        if (data.res=='success') {
            var element = document.getElementById("loader");
                element.classList.remove("fa-spinner");
             
                // Add the new option to the select dropdown
                $('#supplier').append($('<option>', {
                    value: data.row.id,
                    text: data.row.name,
                    selected: true 
                }));
                $('#supplier').trigger('change');
                $('#name').html('<span>Name:- <b>' + data.row.name + '</b></span>');
                $('#gst').html('<span>GSTIN:- <b>' + data.row.gstin + '</b></span>');
                $('#address').html('<span>Billing Address:- <b>' + data.row.address + ' ' + data.row.pincode + ' ' + data.row.city_name + ' ' + data.row.state_name + '</b></span>');
                $('#mobile').html('<span>Mobile:- <b>' + data.row.mobile + '</b></span>');
                $('#supplier2').val(data.row.id);

                $('#showModal').modal('hide');

        }
        alert(data.msg);
      }
    })
    return false;
})
</script>
<!-- //###### ANKIT MAIN CONTENT  ######// -->

<script type="text/javascript">
var timer;
        var timeout = 500;
        $(document).on('keyup', '#tb-search', function(event){
            if(event.keyCode == 13)
            {
                $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
            clearTimeout(timer);
            timer = setTimeout(function(){
                var search  = $('#tb-search').val();
                // console.log(search);
                var tbUrl = $('[name="tb"]').val();
                $.post(tbUrl,{search:search})
                .done(function(data){
                    $('#tb').html(data);
                    if($('#tb-search').val()!== '')
                    {
                        document.getElementById("tb-search").focus();
                        var search  = $('#tb-search').val();
                        $('#tb-search').val('');
                        $('#tb-search').val(search);
                    }  
                })
            }, timeout);

            return false;
        }
        })
</script>

<script type="text/javascript">
   function fetch_city(state)
   {
    $.ajax({
        url: "<?php echo base_url('shop-master-data/fetch_city'); ?>",
        method: "POST",
        data: {
            state:state
        },
        success: function(data){
            $(".city").html(data);
        },
    });
   }
   $(document).ready(function() {
   

    $('body').on('click', '.add-row', function(e) {
        var index = 1;
        $('.rooms_tb>tbody>tr').each(function() {
            index++;
            
        });
        var newRow = $('<tr data-row-id="' + (index) + '" class="text-center rooms_row" data-purchase-item="template">');
        newRow.append('<td class="paction mt-2" style="display: flex;"><a id="remove_item" data-item-remove="" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill remove-row"><i class="fa fa-times"></i></a></td>');
        newRow.append('<td class="psrno"><span id="itemIndex" data-item-index>' + (index) + '</span><input type="hidden" id="indexNo" class="indexNo" value="1" name="indexNo[]"></td>');
        newRow.append('<td class="pitemcode"><div class="p-0 text-right m--font-bolder form-group mb-0"><input type="text" class="form-control form-control-sm" name="itemCode[]" id="itemCode" placeholder="ItemCode" required  data-placement="top"/> </div> </td>');
        newRow.append('<td width="200px" align="center" class="pname productVarientId-container"><select class="form-control select2 productVarientId" id="productVarientId" name="productVarientId[]" placeholder="Select Product" required></select><br><button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" onclick="setitem()" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2 btn-sm btn-primary" title="Duplicate Product" id="edit_item" onclick="duplicate()" ><i class="mdi mdi-content-duplicate"></i></button></td>');
        newRow.append('<td class="pqty"><div class="p-0 text-right m--font-bolder form-group mb-0"><div class="d-flex align-items-center"><div><input type="text" class="form-control form-control-sm text-center quantity1 qty" name="quantity[]" id="quantity" onkeydown="check(event,this)" placeholder="Qty" value="0" class="" data-placement="top" data-debitnoteqty="0" required/> </div></div></td>');
        newRow.append('<td class="puom "><div class="p-0 text-right m--font-bolder form-group mb-0"><input type="text" readonly="readonly" class="form-control form-control-sm" name="uom[]" id="uom" placeholder="UOM" class="" data-placement="top" required/></div></td>');
        newRow.append('<td class="pprice"><div class="p-0 text-right m--font-bolder form-group mb-0"> <input type="text" class="form-control form-control-sm unitCost" name="unitCost[]" id="unitCost"  onkeydown="check(event,this)" value="0" required/> </div> </td>');
        newRow.append('<td class="ptax" style="width: 100px;text-align:center"><div class="p-0"><span class="m--font-info" style="font-size:10px;" data-item-tax-name=""  id="data-item-tax-name"></span><span data-item-cess-amount="" id="data-item-cess-amount"></span><br><span data-item-tax-amount="" id="data-item-tax-amount" style="font-size:10px;"><?=$shop_details->currency;?>. 0</span> <input type="hidden" name="taxAmount[]" id="taxAmount" /> <input type="hidden" name="taxRate[]" id="taxRate" /> <input type="hidden" name="mrpp[]" id="mrpp" /> <input type="hidden" name="SellRate[]" id="SellRate" />  <input type="hidden" name="discount_value[]" id="discount_value" /> </div> </td>');
        newRow.append('<td class="plcost"><div class="p-0"><span data-item-cess-amount="" id="data-item-offer-name"></span><br><span data-item-tax-amount=""  id="data-item-offer-amount" style="font-size:14px;"><?=$shop_details->currency;?>. 0</span>  <input type="hidden" name="offerCost[]" id="offerCost" /> </div></td>');
        newRow.append('<td class="plcost"><input  type="text" class="form-control form-control-sm text-right" name="landingCost[]" id="landingCost" readonly="readonly" value="0" required/> </td>');
        newRow.append('<td class="plcost "><input  type="text" class="form-control form-control-sm text-right" name="margin[]" id="margin" readonly="readonly" value="0" required /> <input type="hidden" id="catMargin" class="catMargin"></td>');
        newRow.append('<td class="ptotal"><input  type="text" class="form-control form-control-sm text-right" name="netAmount[]" required id="netAmount" onkeydown="check(event,this)" placeholder="Amout" data-item-amount=""  value="0" class=""  /></td>');
        $('.rooms_tb tbody').append(newRow);
        
        // Initialize select2 for the dynamically added select element
        $(newRow).find('.select2').select2();
        
        calculate_amount();
        INSERTPORDUCT = "1";
    $('select[name="productVarientId[]"]').select2({
                    placeholder: "Search Product",
                    allowClear: 2,
                    ajax: {
                        url: '<?php echo base_url();?>purchase/search_product',
                        dataType: "json",
                        type: "POST",
                        delay: 250,
                        data: function (e) {
                            var name1=e.term;
                            $('.ProductName').val(name1);
                            return {
                                contactname: e.term,
                                page: e.page
                            }
                        },
                        processResults: function (e, t) {
                            return t.page = t.page || 1, {
                                results: e.items,
                                pagination: {
                                    more: 30 * t.page < e.total_count
                                }
                            }
                        },
                        cache: !0
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 2,
                    language: {
                        noResults: function () {
                            if(INSERTPORDUCT == 1){
                                var searchValue = $("#ProductName").val();
                                $('.name').val(searchValue);
                                var url1 = '<?=base_url('purchase/Product-modal/');?>' + searchValue;
                                return '<div class="m-demo-icon"> <button class="float-right btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#new-product" data-whatever="Add Product"  ><i class="mdi mdi-plus"></i>Add Product</button></div>';	
                            }else{
                                return "";
                            }
                            
                        
                        }
                        },
                    templateResult: function (e) {
                        if (e.loading) return e.text;
                        return e.text
                    },
                    templateSelection: function (data, container) {
                        $(data.element).attr('data-contact-type', data.contact_type);
                        return data.text;
                    }
                }).on('select2:select', function (e) {
                    var product_id = e.params.data.id;
                    var _this = $(this); 
                    $.ajax({
                        url: '<?php echo base_url();?>purchase/get_product_details',
                        type: 'POST',
                        dataType: 'json',
                        data: { id: product_id },
                        success: function (response) {
                            console.log(response);
                            if(response.success=='success')
                            {
                                var supplier = $('#supplier').val();
                                var totalAmount = '';
                                var mrp = '';
                                var Quantity = '1';
                                var selling_per = '0';
                                var PerAmount = '0';
                                var per='0';
                                var margn=0;
                                var margnper=0;
                                var product = response.rowData;
                                setTimeout(function() {
                                calculate_amount();
                            }, 100);
                            var _row = _this.closest('tr');
                            var rowId = _row.data('row-id'); 
                            console.log(_row);
                            Quantity = product.qty;
                      var selling_rate = product.selling_rate;
                      if(product.discount_type=='1')
                    {
                        selling_per = (Quantity*selling_rate * product.offer_upto)/100;
                        selling_rate = (Quantity*selling_rate) - selling_per;
                        per = product.offer_upto;
                        _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " %</span>"); 
                        _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                        
                    }else
                    if(product.discount_type=='0'){
                        selling_per = product.offer_upto;
                        per = product.offer_upto;
                        selling_rate = (Quantity*selling_rate) - product.offer_upto;
                        _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " OFF</span>"); 
                        _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                    }else
                    {
                        selling_per =0;
                        selling_rate = Quantity*selling_rate;
                    }
                        margn=product.mrp-selling_rate;
                        margnper = (margn/product.selling_rate)*100;
                        var GetResult = GetSaleRecord(selling_rate, product.tax_value, product.qty)
                        var getV = GetResult.split(",");
                        var AmountwithoutTax = getV[0];
                        var TaxValue = getV[1];
                        _row.find('#landingCost').val(selling_rate.toFixed(2));
                        _row.find('#netAmount').val(selling_rate.toFixed(2));
                        _row.find('#catMargin').val(product.selling_rate);
                        _row.find('#mrpp').val(product.mrp);
                        _row.find('#SellRate').val(selling_rate);
                        _row.find('[name="quantity[]"]').val(Quantity);
                        
                        var margnperFloat = parseFloat(margnper);
                        if (!isNaN(margnperFloat)) {
                            _row.find('#margin').val(margnperFloat.toFixed(2));
                        }_row.find('[name="itemCode[]"]').val(product.product_code);
                        
                        _row.find('#offerCost').val(selling_per.toFixed(2));
                        
                       _row.find('.productVarientId-container').empty(); // Clear existing content

                        var dropdownId = 'productVarientId_' + _row.index(); 
                        var dropdown = $('<select class="form-control select2 productVarientId" name="productVarientId[]"></select>').attr('id', dropdownId);
                        dropdown.append('<option value="' + product.product_id + '">' + product.name + '</option>'); 
                        

                            var button = $('<button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" onclick="setitem()" data-product-id="' + product.product_id + '" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2 btn-sm btn-primary" title="Duplicate Product" id="duplicate_item" data-product-id="' + product.product_id + '"><i class="mdi mdi-content-duplicate"></i></button>');
                        

                        _row.find('.productVarientId-container').append(dropdown).append(button); 


                        _row.find('#uom').val(product.unit_type);
                        var amountWithoutTaxFloat = parseFloat(AmountwithoutTax);
                        if (!isNaN(amountWithoutTaxFloat)) {
                            _row.find('#unitCost').val(amountWithoutTaxFloat.toFixed(2));
                        }
                        _row.find('#data-item-cess-amount').html("<span style='font-size:10px;color:blue'>GST " + product.tax_value + " %</span>");
                        var amountTaxValue = parseFloat(TaxValue);
                        if (!isNaN(amountTaxValue)) {
                            _row.find('#data-item-tax-amount').text(amountTaxValue.toFixed(2));
                            _row.find('#taxAmount').val(amountTaxValue.toFixed(2));
                        }
                        var amountTaxValueRate = parseFloat(product.tax_value);
                        if (!isNaN(amountTaxValueRate)) {
                            _row.find('#taxRate').val(amountTaxValueRate.toFixed(2));
                        }
                        } else
                        if (response.success == 'YES_INVENTORY') {
                                rowReferences.push(_row);
                                console.log(_row);
                                $('.code').val(itemCode);
                                $('#product-data').load('<?php echo base_url('purchase/yes-inventory-product');?>', {id: response.rowData.id}, function(response, status, xhr) {
                                    if (status == "error") {
                                        console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                                    } else {
                                        $('#new-product-modal').modal('show');
                                    }
                                });
                            }

                            else 
                            if(response.success=='NO_INVENTORY'){
                                rowReferences.push(_row);
                                console.log(_row);
                                $('.code').val(itemCode);
                                $('#product-data').load('<?php echo base_url('purchase/no-inventory-product');?>', {id: response.rowData.id}, function(response, status, xhr) {
                                    if (status == "error") {
                                        console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                                    } else {
                                        $('#new-product-modal').modal('show');
                                    }
                                });
                            } else {
                             rowReferences.push(_row);
                             console.log(_row);
                            $('.code').val(itemCode);
                            $('#create-new-product').load('<?php echo base_url('purchase/create-new-product');?>', {code: itemCode}, function(response, status, xhr) {
                        if (status == "error") {
                            console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                        } else {
                            $('#new-product').modal('show');
                        }
                       });
                        }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching supplier details:", error);
                        }
                    });
                });
    });

    $('body').on('click', '.remove-row', function(e) {
    var $row = $(this).closest('tr');
    var itemCode = $row.find('.pitemcode input').val();
    var purchaseId = $row.find('#purchase_item_id').val() || 0;
    var purchase_id = $row.find('#purchase_id').val() || 0;
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to delete this item? if delete than not recover this item',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            if(purchaseId=='0')
                {
                    $row.remove();
                    resetIndexNumbers();
                    calculate_amount();
                    Swal.fire(
                    'Deleted!',
                    'Your row has been deleted.',
                    'success'
                    );
                    return true;
                }
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url('purchase/delete_item');?>', 
            dataType: 'json',
            data: { itemCode: itemCode ,id:purchaseId,purchase_id:purchase_id},
            success: function(response) {
                if(response.res)
                {
                    $row.remove();
                    resetIndexNumbers();
                    calculate_amount();
                    Swal.fire(
                        'Deleted!',
                        'Your item has been deleted.',
                        'success'
                    );
                }else{
                    Swal.fire(
                        'Not Deleted!',
                        'Your item has been not deleted.',
                        'error'
                    ); 
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
              }
            });
        }
    });
});


    function resetIndexNumbers() {
        var index = 1;
        $('.rooms_tb>tbody>tr').each(function() {
            $(this).find('[data-item-index]').text(index);
            $(this).find('[id="indexNo"]').val(index);
            index++;
        });
    }
});



function check(e, c) {
        var allowedKeyCodesArr = [9, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 8, 37, 39, 109, 189, 46, 110, 190];
        if ($.inArray(e.keyCode, allowedKeyCodesArr) === -1) { 
            e.preventDefault();
        } else if ($.trim($(c).val()).indexOf('.') > -1 && $.inArray(e.keyCode, [110, 190]) !== -1) {
            e.preventDefault();
        } else {
            return true;
        }
    }

   

$(document).ready(function() {
    var rowReferences = [];
    INSERTPORDUCT = "1";
    $('select[name="productVarientId[]"]').select2({
                    placeholder: "Search Product",
                    allowClear: 2,
                    ajax: {
                        url: '<?php echo base_url();?>purchase/search_product',
                        dataType: "json",
                        type: "POST",
                        delay: 250,
                        data: function (e) {
                            var name1=e.term;
                            $('.ProductName').val(name1);
                            return {
                                contactname: e.term,
                                page: e.page
                            }
                        },
                        processResults: function (e, t) {
                            return t.page = t.page || 1, {
                                results: e.items,
                                pagination: {
                                    more: 30 * t.page < e.total_count
                                }
                            }
                        },
                        cache: !0
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    minimumInputLength: 2,
                    language: {
                        noResults: function () {
                            if(INSERTPORDUCT == 1){
                                var searchValue = $("#ProductName").val();
                                $('.name').val(searchValue);
                                var url1 = '<?=base_url('purchase/Product-modal/');?>' + searchValue;
                                return '<div class="m-demo-icon"> <button class="float-right btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#new-product" data-whatever="Add Product"  ><i class="mdi mdi-plus"></i>Add Product</button></div>';	
                            }else{
                                return "";
                            }
                            
                        
                        }
                        },
                    templateResult: function (e) {
                        if (e.loading) return e.text;
                        return e.text
                    },
                    templateSelection: function (data, container) {
                        $(data.element).attr('data-contact-type', data.contact_type);
                        return data.text;
                    }
                }).on('select2:select', function (e) {
                    var product_id = e.params.data.id;
                    var _this = $(this); 
                    $.ajax({
                        url: '<?php echo base_url();?>purchase/get_product_details',
                        type: 'POST',
                        dataType: 'json',
                        data: { id: product_id },
                        success: function (response) {
                            console.log(response);
                            var _row = _this.closest('tr');
                            var rowId = _row.data('row-id'); 
                            if(response.success=='success')
                            {
                                var supplier = $('#supplier').val();
                                var totalAmount = '';
                                var mrp = '';
                                var Quantity = '1';
                                var selling_per = '0';
                                var PerAmount = '0';
                                var per='0';
                                var margn=0;
                                var margnper=0;
                                var product = response.rowData;
                                setTimeout(function() {
                                calculate_amount();
                            }, 100);
                           
                            Quantity = product.qty;
                            var selling_rate = product.selling_rate;
                    if(product.discount_type=='1')
                    {
                        selling_per = (Quantity*selling_rate * product.offer_upto)/100;
                        selling_rate = (Quantity*selling_rate) - selling_per;
                        per = product.offer_upto;
                        _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " % </span>"); 
                        _row.find('#data-item-offer-amount').text("<?=$shop_details->currency;?>. "+selling_per);
                        
                    }else
                    if(product.discount_type=='0'){
                        selling_per = product.offer_upto;
                        per = product.offer_upto;
                        selling_rate = (Quantity*selling_rate) - product.offer_upto;
                        _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " OFF </span>"); 
                        _row.find('#data-item-offer-amount').text("<?=$shop_details->currency;?>. "+selling_per);
                    }else
                    {
                        selling_per =0;
                        selling_rate = Quantity*selling_rate;
                    }
                        margn=product.mrp-selling_rate;
                        margnper = (margn/product.selling_rate)*100;
                        var GetResult = GetSaleRecord(selling_rate, product.tax_value, product.qty)
                        var getV = GetResult.split(",");
                        var AmountwithoutTax = getV[0];
                        var TaxValue = getV[1];
                        _row.find('#landingCost').val(selling_rate.toFixed(2));
                        _row.find('#netAmount').val(selling_rate.toFixed(2));
                        _row.find('#catMargin').val(product.selling_rate);
                        _row.find('#mrpp').val(product.mrp);
                        _row.find('#SellRate').val(selling_rate);
                        _row.find('[name="quantity[]"]').val(Quantity);
                        
                        var margnperFloat = parseFloat(margnper);
                        if (!isNaN(margnperFloat)) {
                            _row.find('#margin').val(margnperFloat.toFixed(2));
                        }
                        _row.find('#offerCost').val(selling_per.toFixed(2));
                        // toastr.error(product.product_code);
                        _row.find('[name="itemCode[]"]').val(product.product_code);
                        
                       _row.find('.productVarientId-container').empty(); // Clear existing content

                        var dropdownId = 'productVarientId_' + _row.index(); 
                        var dropdown = $('<select class="form-control select2 productVarientId" name="productVarientId[]"></select>').attr('id', dropdownId);
                        dropdown.append('<option value="' + product.id + '">' + product.name + '</option>'); 
                        

                            var button = $('<button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" onclick="setitem()" data-product-id="' + product.id + '" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2 btn-sm btn-primary" title="Duplicate Product" id="duplicate_item" data-product-id="' + product.id + '"><i class="mdi mdi-content-duplicate"></i></button>');
                        

                        _row.find('.productVarientId-container').append(dropdown).append(button); 


                        _row.find('#uom').val(product.unit_type);
                        var amountWithoutTaxFloat = parseFloat(AmountwithoutTax);
                        if (!isNaN(amountWithoutTaxFloat)) {
                            
                            _row.find('#unitCost').val(amountWithoutTaxFloat.toFixed(2));
                        }
                        _row.find('#data-item-cess-amount').html("<span style='font-size:10px;color:blue'>GST " + product.tax_value + " % </span>");
                        var amountTaxValue = parseFloat(TaxValue);
                        if (!isNaN(amountTaxValue)) {
                             _row.find('#data-item-tax-amount').text("<?=$shop_details->currency;?>. "+amountTaxValue.toFixed(2));
                            _row.find('#taxAmount').val(amountTaxValue.toFixed(2));
                        }
                        var amountTaxValueRate = parseFloat(product.tax_value);
                        if (!isNaN(amountTaxValueRate)) {
                            _row.find('#taxRate').val(amountTaxValueRate.toFixed(2));
                        }
                        }else 
                            if (response.success == 'YES_INVENTORY') {
                                rowReferences.push(_row);
                                console.log(_row);
                                $('.code').val(itemCode);
                                $('#product-data').load('<?php echo base_url('purchase/yes-inventory-product');?>', {id: response.rowData.id}, function(response, status, xhr) {
                                    if (status == "error") {
                                        console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                                    } else {
                                        $('#new-product-modal').modal('show');
                                    }
                                });
                            }

                            else 
                            if(response.success=='NO_INVENTORY'){
                                rowReferences.push(_row);
                                console.log(_row);
                                $('.code').val(itemCode);
                                $('#product-data').load('<?php echo base_url('purchase/no-inventory-product');?>', {id: response.rowData.id}, function(response, status, xhr) {
                                    if (status == "error") {
                                        console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                                    } else {
                                        $('#new-product-modal').modal('show');
                                    }
                                });
                            } else {
                             rowReferences.push(_row);
                             console.log(_row);
                            $('.code').val(itemCode);
                            $('#create-new-product').load('<?php echo base_url('purchase/create-new-product');?>', {code: itemCode}, function(response, status, xhr) {
                        if (status == "error") {
                            console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                        } else {
                            $('#new-product').modal('show');
                        }
                       });
                        }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching supplier details:", error);
                        }
                    });
                });
        $('body').on('blur', '[name="itemCode[]"]', function(e) {
        let _this = $(this);
        let _row = _this.closest('tr'); 
        let itemCode = _this.val(); 
        var itemCodeExists = false;
       // Start iteration from the second row
       $('.rooms_tb tbody tr').not(_row).each(function() {
        var existingItemCode = $(this).find('.pitemcode input').val();
        if (existingItemCode === itemCode) {
            itemCodeExists = true;
            return false; // Exit the loop if duplicate found
            }
        });

        if (itemCodeExists) {
            toastr.error('Item code already exists in the table.'); // Display error message
            _this.val(''); 
            return true;
        }
            let supplier = $('#supplier').val();
            var totalAmount = '';
            var mrp = '';
            var Quantity = '1';
            var selling_per = '0';
            var PerAmount = '0';
            var per='0';
            var margn=0;
            var margnper=0;
            if (!supplier || supplier === '--Select Supplier--') {
                toastr.error('Please select a supplier.');
                return;
            }

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('purchase/getItemCodeData');?>',
            data: { itemCode: itemCode },
            success: function(response) {
                response = JSON.parse(response);
                if(response.success=='success') {
                    setTimeout(function() {
                        calculate_amount();
                    }, 100);
                    var product = response.rowData;
                    var selling_rate = product.selling_rate;
                    if(product.discount_type=='1')
                    {
                        selling_per = (Quantity*selling_rate * product.offer_upto)/100;
                        selling_rate = (Quantity*selling_rate) - selling_per;
                        per = product.offer_upto;
                        _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " %</span>"); 
                        _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                        
                    }else
                    if(product.discount_type=='0'){
                        selling_per = product.offer_upto;
                        per = product.offer_upto;
                        selling_rate = (Quantity*selling_rate) - product.offer_upto;
                        _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " OFF</span>"); 
                        _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                    }else
                    {
                        selling_per =0;
                        selling_rate = Quantity*selling_rate;
                    }
                    margn=product.selling_rate-selling_rate;
                    margnper = (margn/product.selling_rate)*100;
                    var GetResult = GetSaleRecord(selling_rate, product.tax_value, product.qty)
                    var getV = GetResult.split(",");
                    var AmountwithoutTax = getV[0];
                    var TaxValue = getV[1];
                    _row.find('#SellRate').val(selling_rate.toFixed(2));
                    _row.find('#landingCost').val(selling_rate.toFixed(2));
                    _row.find('#netAmount').val(selling_rate.toFixed(2));
                    _row.find('#catMargin').val(product.selling_rate);
                    _row.find('#mrpp').val(product.mrp);
                    _row.find('[name="quantity[]"]').val(1);
                    
                    var margnperFloat = parseFloat(margnper);
                    if (!isNaN(margnperFloat)) {
                        _row.find('#margin').val(margnperFloat.toFixed(2));
                    }
                    _row.find('#offerCost').val(selling_per.toFixed(2));
                     _row.find('.productVarientId-container').empty();
                        var dropdownId = 'productVarientId_' + _row.index(); 
                        var dropdown = $('<select class="form-control select2 productVarientId" name="productVarientId[]"></select>').attr('id', dropdownId);
                        dropdown.append('<option value="' + product.product_id + '">' + product.name + '</option>'); 

                             var button = $('<button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" onclick="setitem()" data-product-id="' + product.product_id + '" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2  btn-sm btn-primary" title="Duplicate Product" id="duplicate_item" data-product-id="' + product.product_id + '"><i class="mdi mdi-content-duplicate"></i></button>');

                        _row.find('.productVarientId-container').append(dropdown).append(button); 
                    _row.find('#uom').val(product.unit_type);
                    var amountWithoutTaxFloat = parseFloat(AmountwithoutTax);
                    if (!isNaN(amountWithoutTaxFloat)) {
                        _row.find('#unitCost').val(amountWithoutTaxFloat.toFixed(2));
                    }
                   _row.find('#data-item-cess-amount').html("<span style='font-size:10px;color:blue'>GST " + product.tax_value + " %</span>");
                    var amountTaxValue = parseFloat(TaxValue);
                    if (!isNaN(amountTaxValue)) {
                        _row.find('#data-item-tax-amount').text(amountTaxValue.toFixed(2));
                        _row.find('#taxAmount').val(amountTaxValue.toFixed(2));
                    }
                    var amountTaxValueRate = parseFloat(product.tax_value);
                    if (!isNaN(amountTaxValueRate)) {
                        _row.find('#taxRate').val(amountTaxValueRate.toFixed(2));
                    }
                   
                  

                }else 
                if (response.success == 'YES_INVENTORY') {
                    rowReferences.push(_row);
                    $('.code').val(itemCode);
                    $('#product-data').load('<?php echo base_url('purchase/yes-inventory-product');?>', {id: response.rowData.product_id}, function(response, status, xhr) {
                        if (status == "error") {
                            console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                        } else {
                            $('#new-product-modal').modal('show');
                        }
                    });
                }

                else 
                if(response.success=='NO_INVENTORY'){
                    rowReferences.push(_row);
                    $('.code').val(itemCode);
                    $('#product-data').load('<?php echo base_url('purchase/no-inventory-product');?>', {id: response.rowData.product_id}, function(response, status, xhr) {
                        if (status == "error") {
                            console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                        } else {
                            $('#new-product-modal').modal('show');
                        }
                    });
                }  else {
                    rowReferences.push(_row);
                    $('.code').val(itemCode);
                    $('#create-new-product').load('<?php echo base_url('purchase/create-new-product');?>', {code: itemCode}, function(response, status, xhr) {
                        if (status == "error") {
                            console.log("Error loading modal content: " + xhr.status + " " + xhr.statusText);
                        } else {
                            $('#new-product').modal('show');
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on("submit", '.add_product', function(event) {
        var  $this = $(this);
        event.preventDefault(); 
        var NewQty =$('[name="NewQty"]').val();
        if(NewQty==0)
        {
            toastr.error("Please enter a quantity of at least 1 for the product.");
            return true;
        }
        debugger;
        if ($this.hasClass("needs-validation")) {
            if (!$this.valid()) {
                return false;
            }
        }
        $.ajax({
            url: $this.attr("action"),
            type: $this.attr("method"),
            data:  new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function(response1) {
                response = JSON.parse(response1);
                if(response.existpro)
                    {
                        toastr.error("Product name already exist.");
                        return ;
                    }
                    if(response.existcode)
                    {
                        toastr.error("Product code already exist.");
                        return ;
                    }
                if(response.res) {
                    console.log("Response received:", response);
                    // Loop through the stored row references and update each one
                   
                    for (var i = 0; i < rowReferences.length; i++) {
                        var _row = rowReferences[i];
                        // Update the row accordingly
                        var supplier = $('#supplier').val();
                        var totalAmount = '';
                        var mrp = '';
                        var Quantity = '1';
                        var selling_per = '0';
                        var PerAmount = '0';
                        var per='0';
                        var margn=0;
                        var margnper=0;
                        setTimeout(function() {
                            calculate_amount();
                        }, 100);
                        var product = response.rowData;
                        var selling_rate = product.selling_rate;
                        Quantity = product.qty;
                        if(product.discount_type=='1')
                        {
                            selling_per = Quantity*(product.NewOfferValue);
                            selling_rate = Quantity*selling_rate
                            per = product.offer_upto;
                            if(selling_per=='0'){
                                _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> 0 %</span>"); 
                            _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }else{
                            _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " %</span>"); 
                            _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }
                        }else
                        if(product.discount_type=='0'){
                            selling_per = Quantity*(product.NewOfferValue);
                            selling_rate = Quantity*selling_rate
                            per = product.offer_upto;
                            if(selling_per=='0'){
                                _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'>0 OFF</span>"); 
                            _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }else{
                            _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " OFF</span>"); 
                            _row.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }
                        }else
                        {
                            selling_per=0;
                            selling_rate = Quantity*selling_rate;
                            _row.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'></span>"); 
                            _row.find('#data-item-offer-amount').text('Rs. 0');
                        }
                        margn=product.landing_cost-selling_rate;
                        margnper = (margn/selling_rate)*100;
                        var AmountwithoutTax = product.purchase_rate;
                        var TaxValue = product.tax_amount;
                        _row.find('#landingCost').val(selling_rate.toFixed(2));
                        _row.find('#netAmount').val(selling_rate.toFixed(2));
                        _row.find('#catMargin').val(product.selling_rate);
                        _row.find('#mrpp').val(product.mrp);
                        _row.find('#SellRate').val(selling_rate);
                        _row.find('[name="quantity[]"]').val(Quantity);
                        
                        var margnperFloat = parseFloat(margnper);
                        if (!isNaN(margnperFloat)) {
                            _row.find('#margin').val(margnperFloat.toFixed(2));
                        }
                        _row.find('#offerCost').val(selling_per.toFixed(2));
                        _row.find('[name="itemCode[]"]').val(product.product_code);
                         _row.find('.productVarientId-container').empty();
                        var dropdownId = 'productVarientId_' + _row.index(); 
                        var dropdown = $('<select class="form-control select2 productVarientId" name="productVarientId[]"></select>').attr('id', dropdownId);
                        dropdown.append('<option value="' + product.product_id + '">' + product.name + '</option>'); 

                             var button = $('<button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" onclick="setitem()" data-product-id="' + product.product_id + '" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2  btn-sm btn-primary" title="Duplicate Product" id="duplicate_item" data-product-id="' + product.product_id + '"><i class="mdi mdi-content-duplicate"></i></button>');

                        _row.find('.productVarientId-container').append(dropdown).append(button); 

                        _row.find('#uom').val(product.unit_type);
                        var amountWithoutTaxFloat = parseFloat(AmountwithoutTax);
                        if (!isNaN(amountWithoutTaxFloat)) {
                            _row.find('#unitCost').val(amountWithoutTaxFloat.toFixed(2));
                        }
                        _row.find('#data-item-cess-amount').html("<span style='font-size:10px;color:blue'>GST " + product.tax_value + " %</span>");
                        var amountTaxValue = parseFloat(TaxValue);
                        if (!isNaN(amountTaxValue)) {
                            _row.find('#data-item-tax-amount').text(amountTaxValue.toFixed(2));
                            _row.find('#taxAmount').val(amountTaxValue.toFixed(2));
                        }
                        var amountTaxValueRate = parseFloat(product.tax_value);
                        if (!isNaN(amountTaxValueRate)) {
                            _row.find('#taxRate').val(amountTaxValueRate.toFixed(2));
                        }
                    }
                    // Clear the array after updating all rows
                    rowReferences = [];
                    $('#new-product').modal('hide');
                }
            }
        })
        return false;
    })


});

function GetSaleRecord(SellingRate, TaxValue, Stock) {
        var TaxValue = (Number(SellingRate) - (Number(SellingRate) * (100 / (100 + Number(TaxValue)))));
        var AmountwithoutTax = (Number(SellingRate) - Number(TaxValue));
        var getResult = AmountwithoutTax + ',' + TaxValue;

        return getResult;

    }
calculate_amount();
function calculate_amount() {
    let Row = $('[name="indexNo[]"]');
    var flatDiscount = parseFloat(document.getElementById("flatDiscount").value) || 0;
      // Check if flatDiscount is greater than or equal to 100%
     if (flatDiscount >= 100) {
        toastr.error('The flat discount cannot be 100% or greater.');
        flatDiscount = 0;
        document.getElementById("flatDiscount").value = flatDiscount;
        calculate_amount();
        return;
    }
    let TotalRow = Row.length;
    let currency = "<?=$shop_details->currency;?>";
    let totalQty = 0;
    let TotalUnitCost = 0;
    let TaxTotal = 0;
    let NetFinalTotal = 0;
    let FinalTotalUnitCost = 0;
    let FinalTaxAmount = 0;
    let discountFinal=0;
    let GrossTotal=0;
    $.each(Row, function() {
        var _this = $(this);
        let _row = _this.closest('tr');
        if (_this.val()) {
            var unitCost = parseFloat(_row.find('.unitCost').val()) || 0;
            var qty = parseFloat(_row.find('[name="quantity[]"]').val()) || 0;
            var taxRate = parseFloat(_row.find('[name="taxRate[]"]').val()) || 0;
            var taxAmount = parseFloat(_row.find('[name="taxAmount[]"]').val()) || 0;
            var catMargin = parseFloat(_row.find('.catMargin').val()) || 0;

            // Calculate discount amount
            var discountAmount = unitCost * (flatDiscount / 100);
            // Apply discount to unit cost
            var DiscountUnitCost = unitCost - discountAmount;
            // Calculate tax amount after discount
            var DiscountTax = DiscountUnitCost * (taxRate / 100);
            // Calculate final unit cost after discount and tax
            var UnitLand = DiscountUnitCost + DiscountTax;
            // Calculate total for the row
            var PerFinalTotal = UnitLand * qty;
            var DiscountTaxNew = DiscountTax * qty;
            // Update UI elements for the row
            _row.find('#data-item-tax-amount').text("<?=$shop_details->currency;?>. "+DiscountTaxNew.toFixed(2));
            _row.find('[name="taxAmount[]"]').val(DiscountTaxNew.toFixed(2));
            _row.find('#landingCost').val(UnitLand.toFixed(2));
            _row.find('[name="netAmount[]"]').val(PerFinalTotal.toFixed(2));
            _row.find('[name="discount_value[]"]').val(discountAmount.toFixed(2));

            // Margin
            margn=catMargin-UnitLand;
            margnper = (margn/catMargin)*100;
            var margnperFloat = parseFloat(margnper);
             if (!isNaN(margnperFloat)) {
             _row.find('#margin').val(margnperFloat.toFixed(2));
            }

            // Accumulate totals
            totalQty += qty;
            TotalUnitCost += UnitLand*qty;
            TaxTotal += DiscountTax*qty;
            discountFinal +=discountAmount;
            GrossTotal +=unitCost*qty;
        }
    });

    // Calculate total amounts
    NetFinalTotal = TotalUnitCost ;
    FinalTaxAmount = TaxTotal;

    // Update UI elements with totals
    $('#GrossTotal').val(GrossTotal.toFixed(2));
    $('#taxable_amount').text("<?=$shop_details->currency;?>. "+(TotalUnitCost-FinalTaxAmount).toFixed(2));
    $('#net_amount').text("<?=$shop_details->currency;?>. "+NetFinalTotal.toFixed(2));
    $('#product_sub_total').text("<?=$shop_details->currency;?>. "+NetFinalTotal.toFixed(2));
    $('#tax_total').text(FinalTaxAmount.toFixed(2));
    $('#tax_amount').text("<?=$shop_details->currency;?>. "+FinalTaxAmount.toFixed(2));
    $('#gross_amount').text("<?=$shop_details->currency;?>. "+GrossTotal.toFixed(2));
    $('#product_qty_total').text(totalQty);
    $('#FinalTaxWithAmount').val((TotalUnitCost-FinalTaxAmount).toFixed(2));
    $('#FinalQty').val(totalQty);
    $('#FinalTax').val(FinalTaxAmount.toFixed(2));
    $('#FinalTotal').val(NetFinalTotal.toFixed(2));
    $('#FinalTotalRound').val(NetFinalTotal.toFixed(2));
    $('#total_disc').text("<?=$shop_details->currency;?>. "+discountFinal.toFixed(2));
    $('#FinalDiscount').val(discountFinal.toFixed(2));
    
 }


$('body').on('change | keyup', ` [name="quantity[]"],[name="unitCost[]"],[name="netAmount[]"]`, function(e) {
        let _this = $(this);
        let _row = _this.parents('tr');
        let _form = _this.parents('form');
        let qty = _row.find(`[name="quantity[]"]`).val();
        calculate_amount();
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
$(document).ready(function() {
    $('#purchase_order_no').on('blur', function() {
        var purchaseOrderNo = $(this).val();
        $.ajax({
            type: 'POST',
            url: '<?=base_url();?>purchase/check_purchase_order', 
            data: { purchase_order_no: purchaseOrderNo },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.exists) {
                    $('#add-purchase').prop('disabled', true);
                    $('#purchase_order_no').val('');
                    toastr.error('Purchase order number already exists.');
                } else {
                    $('#add-purchase').prop('disabled', false);
                    toastr.success('Purchase order number available.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#purchase_order_no').val('');
                $('#add-purchase').prop('disabled', false); 
                toastr.error('An error occurred. Please try again.');
            }
        });
    });
});
$(document).ready(function() {
var _rowData;
$(document).on('click', '.item_edit', function() {
    var productId = $(this).data('product-id');
     _rowData = $(this).closest('tr');
    setitem(productId, _rowData);
    console.log("Edit clicked for product ID:", productId);
    console.log(_rowData);
});
function getProductCode(productId) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: '<?php echo base_url('purchase');?>/get_product_code', 
            method: 'POST',
            dataType: 'json',
            data: { productId: productId },
            success: function(response) {
                if (response.success) {
                    resolve(response.productCode);
                } else {
                    console.error('Product code not found.');
                    reject('Product code not found');
                }
            },
            error: function() {
                console.error('Failed to fetch product code.');
                reject('Failed to fetch product code');
            }
        });
    });
}

function setitem(productId, _row) {
    getProductCode(productId).then(function(productCode) {
        $('#productModalLabel').text('Product Details (' + productCode + ')');
        var UnitCost = $('#unitCost').val();
        $.ajax({
            url: '<?php echo base_url('purchase');?>/get_product_edit_details', 
            method: 'POST',
            dataType: 'json',
            data: { productId: productId ,UnitCost:UnitCost},
            success: function(response) {
                if (response.success) {
                    console.log(response.html);
                    $('#productModalBody').html(response.html);
                    $('#exampleModal1').modal('show');
                } else {
                    alert('Failed to fetch product details.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Failed to fetch product details.');
            }
        });
    }).catch(function(error) {
        console.error(error);
    });
}

$(document).on("submit", '.edit_product', function(event) {
    event.preventDefault(); 
    var _row = _rowData; 
    console.log(_rowData);
    $this = $(this);
    debugger;
    if ($this.hasClass("needs-validation")) {
        if (!$this.valid()) {
            return false;
        }
    }
    $.ajax({
        url: $this.attr("action"),
        type: $this.attr("method"),
        data:  new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        success: function(data){
            console.log(data);
            debugger;
            data = JSON.parse(data);
            if (data.res == 'success') {
                console.log(data);
                _row.find('#data-item-cess-amount').html("<span style='font-size:10px;color:blue'>GST " + data.taxRate + " %</span>");
                var amountTaxValue = data.taxAmount;
                    _row.find('#data-item-tax-amount').text(amountTaxValue.toFixed(2));
                    _row.find('#taxAmount').val(amountTaxValue.toFixed(2));
                    var amountTaxValueRate = parseFloat(data.taxRate);
                    if (!isNaN(amountTaxValueRate)) {
                        _row.find('#taxRate').val(amountTaxValueRate.toFixed(2));
                    }

                    _row.find('.productVarientId-container').empty();
                    var dropdownId = 'productVarientId_' + _row.index(); 
                    var dropdown = $('<select class="form-control select2 productVarientId" name="productVarientId[]"></select>').attr('id', dropdownId);
                    dropdown.append('<option value="' + data.id + '">' + data.name + '</option>'); 
                    var button = $('<button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-info m-btn--icon m-btn--icon-only m-btn--pill item_edit" title="Edit Product" data-product-id="' + data.id + '"><i class="fa fa-edit"></i></button> <button  type="button" class="btn mt-1 btn-sm btn-primary" title="Duplicate Product" id="duplicate_item"  data-product-id="' + data.id + '" >Duplicate</button>');

                    _row.find('.productVarientId-container').append(dropdown).append(button); 
                setTimeout(function() {
                    calculate_amount();
                }, 100);
                $('#exampleModal1').modal('hide');
            }
            alert(data.msg);
        }
    });
    return false;
});
});


$(document).ready(function() {
var _rowData1;
$(document).on('click', '#duplicate_item', function() {
    var productId = $(this).data('product-id');
     _rowData1 = $(this).closest('tr');
    duplicateProduct(productId, _rowData1);
    console.log(_rowData1);
});
function getProductCode(productId) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: '<?php echo base_url('purchase');?>/get_product_code', 
            method: 'POST',
            dataType: 'json',
            data: { productId: productId },
            success: function(response) {
                if (response.success) {
                    resolve(response.productCode);
                } else {
                    console.error('Product code not found.');
                    reject('Product code not found');
                }
            },
            error: function() {
                console.error('Failed to fetch product code.');
                reject('Failed to fetch product code');
            }
        });
    });
}
function duplicateProduct(productId, _row) {
    getProductCode(productId).then(function(productCode) {
        $('#productModalLabel1').text('Duplicate Product Details (' + productCode + ')');
        var UnitCost = $('#unitCost').val();
        var landingCost = $('#landingCost').val();
        var mrpp = $('#mrpp').val();
        var SellRate = $('#SellRate').val();
        var quantity = $('#quantity').val();
        $.ajax({
            url: '<?php echo base_url('purchase');?>/get_duplicate_details', 
            method: 'POST',
            dataType: 'json',
            data: { productId: productId ,UnitCost:UnitCost,landingCost:landingCost,mrp:mrpp,sellingRate:SellRate,qty:quantity},
            success: function(response) {
                if (response.success) {
                    console.log(response.html);
                    $('#productDuplicate').html(response.html);
                    $('#exampleModal2').modal('show');
                } else {
                    alert('Failed to fetch product details.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Failed to fetch product details.');
            }
        });
    }).catch(function(error) {
        console.error(error);
    });
}



$(document).on("submit", '.duplicate_product', function(event) {
    event.preventDefault(); 
    var NewQty =$('[name="NewQty"]').val();
        if(NewQty==0)
        {
            toastr.error("Please enter a quantity of at least 1 for the product.");
            return true;
        }
    var _row = _rowData1; 
    console.log(_rowData1);
    $this = $(this);
    debugger;
    if ($this.hasClass("needs-validation")) {
        if (!$this.valid()) {
            return false;
        }
    }
    $.ajax({
        url: $this.attr("action"),
        type: $this.attr("method"),
        data:  new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        success: function(response1) {
                response = JSON.parse(response1);
                if(response.existpro)
                    {
                        toastr.error("Product name already exist.");
                        return ;
                    }
                    if(response.existcode)
                    {
                        toastr.error("Product code already exist.");
                        return ;
                    }
                if(response.res) {
                    console.log("Response received:", response);

                        var supplier = $('#supplier').val();
                        var totalAmount = '';
                        var mrp = '';
                        var Quantity = '1';
                        var selling_per = '0';
                        var PerAmount = '0';
                        var per='0';
                        var margn=0;
                        var margnper=0;
                        setTimeout(function() {
                            calculate_amount();
                        }, 100);
                        var product = response.rowData;
                        var selling_rate = product.selling_rate;
                        var currentIndex = $('.rooms_tb tbody tr').length;
                        var newRow = $('<tr   data-row-id="' + (currentIndex+1) + '" class="text-center rooms_row" data-purchase-item="template" style="background-color:#B2BEB5;"></tr>');
                        newRow.append('<td class="paction mt-2" style="display: flex;"><a  id="remove_item" data-item-remove=""  class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill remove-row"><i class="fa fa-times"></i></a></td>');
                newRow.append('<td class="psrno"><span id="itemIndex" data-item-index>' + (currentIndex + 1) + '</span><input type="hidden" id="indexNo" class="indexNo" value="1" name="indexNo[]" ></td>');
                newRow.append('<td class="pitemcode"><div class="p-0 text-right m--font-bolder form-group mb-0"><input type="text" class="form-control form-control-sm" name="itemCode[]" id="itemCode" placeholder="ItemCode" required  data-placement="top"/> </div></td>');
                newRow.append('<td align="center" class="pname productVarientId-container" style="width:200px"> <select class="form-control select2 productVarientId" id="productVarientId" name="productVarientId[]" placeholder="Select Product" required> </select><button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" data-product-id="' + product.product_id + '"  ><i class="fa fa-edit"></i></button><button  type="button" class="btn  ml-2 btn-sm btn-primary" title="Duplicate Product" id="duplicate_item"  data-product-id="' + product.product_id + '"  ><i class="mdi mdi-content-duplicate"></i></button></td>');
                newRow.append('<td class="pqty"><input type="text" class="form-control form-control-sm text-center quantity1 qty" name="quantity[]" id="quantity" onkeydown="check(event,this)" placeholder="Qty" value="0" data-placement="top" data-debitnoteqty="0" required/></td>');
                newRow.append('<td class="puom"><input type="text" readonly="readonly" class="form-control form-control-sm" name="uom[]" id="uom" placeholder="UOM" data-placement="top" required/></td>');
                newRow.append('<td class="pprice"><input type="text" class="form-control form-control-sm unitCost" name="unitCost[]" id="unitCost"  onkeydown="check(event,this)" value="0" required/></td>');
                newRow.append('<td class="ptax" style="width: 100px;text-align:center"><div class="p-0"><span class="m--font-info" style="font-size:10px;" data-item-tax-name=""  id="data-item-tax-name"></span><span data-item-cess-amount="" id="data-item-cess-amount"></span><br><span data-item-tax-amount="" id="data-item-tax-amount" style="font-size:10px;"><?=$shop_details->currency;?>. 0</span> <input type="hidden" name="taxAmount[]" id="taxAmount" /> <input type="hidden" name="taxRate[]" id="taxRate" /> <input type="hidden" name="mrpp[]" id="mrpp" /> <input type="hidden" name="SellRate[]" id="SellRate" />  <input type="hidden" name="discount_value[]" id="discount_value" /> </div> </td>');
                newRow.append('<td class="plcost"><div class="p-0"><span data-item-cess-amount="" id="data-item-offer-name"></span><br><span data-item-tax-amount=""  id="data-item-offer-amount" style="font-size:14px;"><?=$shop_details->currency;?>. 0</span>  <input type="hidden" name="offerCost[]" id="offerCost" /> </div></td>');
                newRow.append('<td class="plcost"><input  type="text" class="form-control form-control-sm text-right" name="landingCost[]" id="landingCost" readonly="readonly" value="0" required/></td>');
                newRow.append('<td class="plcost"><input  type="text" class="form-control form-control-sm text-right" name="margin[]" id="margin" readonly="readonly" value="0" required /><input type="hidden" id="catMargin" class="catMargin"></td>');
                newRow.append('<td class="ptotal"><input  type="text" class="form-control form-control-sm text-right" name="netAmount[]" required id="netAmount" onkeydown="check(event,this)" placeholder="Amout" data-item-amount=""  value="0" /></td>');
                
                // Append the new row to the table body
                $('.rooms_tb tbody').append(newRow);
                var newRowCells = newRow.find('td');
                newRow.find('input').val(product.product_code);
                Quantity = product.qty;
                        if(product.discount_type=='1')
                        {
                            selling_per = Quantity*(product.NewOfferValue);
                            selling_rate = Quantity*selling_rate
                            per = product.offer_upto;
                            if(selling_per=='0'){
                                newRow.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> 0 %</span>"); 
                                newRow.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }else{
                                newRow.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " %</span>"); 
                                newRow.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }
                        }else
                        if(product.discount_type=='0'){
                            selling_per = Quantity*(product.NewOfferValue);
                            selling_rate = Quantity*selling_rate
                            per = product.offer_upto;
                            if(selling_per=='0'){
                                newRow.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'>0 OFF</span>"); 
                                newRow.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }else{
                                newRow.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'> " + product.offer_upto + " OFF</span>"); 
                                newRow.find('#data-item-offer-amount').text("Rs. "+selling_per);
                            }
                        }else
                        {
                            selling_per=0;
                            selling_rate = Quantity*selling_rate;
                            newRow.find('#data-item-offer-name').html("<span style='font-size:10px;color:blue'></span>"); 
                            newRow.find('#data-item-offer-amount').text('Rs. 0');
                        }
                        margn=product.landing_cost-selling_rate;
                        margnper = (margn/selling_rate)*100;
                        var AmountwithoutTax = product.purchase_rate;
                        var TaxValue = product.tax_amount;
                        newRow.find('#landingCost').val(selling_rate.toFixed(2));
                        newRow.find('#netAmount').val(selling_rate.toFixed(2));
                        newRow.find('#catMargin').val(product.selling_rate);
                        newRow.find('#mrpp').val(product.mrp);
                        newRow.find('#SellRate').val(selling_rate);
                        newRow.find('[name="quantity[]"]').val(Quantity);
                        var margnperFloat = parseFloat(margnper);
                        if (!isNaN(margnperFloat)) {
                            newRow.find('#margin').val(margnperFloat.toFixed(2));
                        }
                        newRow.find('#offerCost').val(selling_per.toFixed(2));

                        newRow.find('.productVarientId-container').empty();
                        var dropdownId = 'productVarientId_' + newRow.index(); 
                        var dropdown = $('<select class="form-control select2 productVarientId" name="productVarientId[]"></select>').attr('id', dropdownId);
                        dropdown.append('<option value="' + product.product_id + '">' + product.name + '</option>'); 

                             var button = $('<button type="button"  class="btn-sm mt-2 btn-danger text-white item_edit" title="Edit Product" id="edit_item" onclick="setitem()" data-product-id="' + product.product_id + '" ><i class="fa fa-edit"></i></button><button  type="button" class="btn ml-2  btn-sm btn-primary" title="Duplicate Product" id="duplicate_item" data-product-id="' + product.product_id + '"><i class="mdi mdi-content-duplicate"></i></button>');

                          newRow.find('.productVarientId-container').append(dropdown).append(button); 


                          newRow.find('#uom').val(product.unit_type);
                        var amountWithoutTaxFloat = parseFloat(AmountwithoutTax);
                        if (!isNaN(amountWithoutTaxFloat)) {
                            newRow.find('#unitCost').val(amountWithoutTaxFloat.toFixed(2));
                        }
                        newRow.find('#data-item-cess-amount').html("<span style='font-size:10px;color:blue'>GST " + product.tax_value + " %</span>");
                        var amountTaxValue = parseFloat(TaxValue);
                        if (!isNaN(amountTaxValue)) {
                            newRow.find('#data-item-tax-amount').text(amountTaxValue.toFixed(2));
                            newRow.find('#taxAmount').val(amountTaxValue.toFixed(2));
                        }
                        var amountTaxValueRate = parseFloat(product.tax_value);
                        if (!isNaN(amountTaxValueRate)) {
                            newRow.find('#taxRate').val(amountTaxValueRate.toFixed(2));
                        }
                $('#exampleModal2').modal('hide');
            }
            alert(response.msg);
        }
    });
    return false;
});

});

   
function validateInput() {
 var gstin = document.getElementById("gstinInput").value;
 var isValid = vGST(gstin.trim());
 }
        
let vGST = (gnumber)=>{
    let gstVal = gnumber;
    let eMMessage = "No Errors";
    let submitButton = document.getElementById("btnsubmit");
    let errorMessage = document.getElementById("errorMessage");
    let successMessage = document.getElementById("successMessage");
    let statecode = gstVal.substring(0, 2);
    let patternstatecode=/^[0-9]{2}$/
    let threetoseven = gstVal.substring(2, 7);
    let patternthreetoseven=/^[A-Z]{5}$/
    let seventoten = gstVal.substring(7, 11);
    let patternseventoten=/^[0-9]{4}$/
    let Twelveth = gstVal.substring(11, 12);
    let patternTwelveth=/^[A-Z]{1}$/
    let Thirteen = gstVal.substring(12, 13);
    let patternThirteen=/^[1-9A-Z]{1}$/
    let fourteen = gstVal.substring(13, 14);
    let patternfourteen=/^Z$/
    let fifteen = gstVal.substring(14, 15);
    let patternfifteen=/^[0-9A-Z]{1}$/
    if (gstVal.length != 15) {
        eMMessage = 'Length should be restricted to 15 digits and should not allow anything more or less';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
            
    }else if (!patternstatecode.test(statecode)) {
        eMMessage = 'First two characters of GSTIN should be numbers';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternthreetoseven.test(threetoseven)) {
        eMMessage = 'Third to seventh characters of GSTIN should be alphabets';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternseventoten.test(seventoten)) {
        eMMessage = 'Eighth to Eleventh characters of GSTIN should be numbers';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternTwelveth.test(Twelveth)) {
        eMMessage = 'Twelveth character of GSTIN should be alphabet';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternThirteen.test(Thirteen)) {
        eMMessage = 'Thirteen characters of GSTIN can be either alphabet or numeric';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternfourteen.test(fourteen)) {
        eMMessage = 'fourteen characters of GSTIN should be Z';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternfifteen.test(fifteen)) {
        eMMessage = 'fifteen characters of GSTIN can be either alphabet or numeric';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else{
        submitButton.disabled = false;
        successMessage.innerText = "Valid GSTIN!.";
        errorMessage.innerText = "";
    }
    console.log(eMMessage)
}
</script>



