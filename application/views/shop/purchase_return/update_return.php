
<div class="row return-box pb-3 pr-3 pl-3 pt-3">
	<form action="<?=base_url()?>purchase_return/store_update"	 method="POST" class="return-form ajaxsubmit reload-tb add-form">
    <input type="hidden" name="id" value="<?=$id;?>">
		<div class="row">
	     <div class="col-md-6">
			<strong>Supplier : </strong><span class="vendor_name"><?=$row->customer_name;?></span>
			<input type="hidden" value="<?=$row->vendor_id;?>" class="vendors_id" name="vendor_id">
		</div>

		<div class="col-md-6">
			<strong>Product : </strong><span class="product_name"><?=$row->product_name;?></span>
			<input type="hidden" value="<?=$row->product_id;?>" class="products_id" name="product_id" >

		</div>
		<div class="col-12"><br></div>

		<div class="col-md-3">
            <div class="form-group">
	            <label class="control-label">Qty<sup>*</sup> :</label>
	            <input type="number" class="form-control" name="return_qty" value="<?=$row->qty;?>"  min="1" >
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
	            <label class="control-label">Return Rate<sup>*</sup> : </label>
	            <input type="number" class="form-control" value="<?=$row->rate;?>"  name="return_rate" min="1" step="0.01">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
	            <label class="control-label">Total : </label>
	            <input type="number" value="<?=$row->total;?>"  class="form-control" name="return_total" readonly>
            </div>
        </div>

       <!--  <div class="col-md-3">
        	<div class="form-group">
	            <label class="control-label">Invoice No : </label>
	            <input type="text" class="form-control" name="invoice_no">
            </div>
        </div> -->

        <div class="col-md-3">
        	<div class="form-group">
	            <label class="control-label">Return Date : </label>
	            <input type="date" class="form-control" value="<?=$row->date;?>"  name="return_date">
            </div>
        </div>

        <div class="col-md-12">
        	<div class="form-group">
	            <label class="control-label">Remark : </label>
	            <input type="text" class="form-control" name="remark" value="<?=$row->remark;?>" >
            </div>
        </div>

		<div class="col-12">
	        <div class="form-group" >
	            <label class="control-label">Select Stock For Return<sup>*</sup></label>
	            <table class="table table-bordered w-100 table-sm">
                    <tbody>
                        <tr>
                            <th></th>
                            <th>Vendor</th>
                            <th int>Qty</th>
                            <th int>Purchase Rate</th>
                            <th int>MRP</th>
                            <th>Expiry Date</th>
                        </tr>
                        <?php $qty= $row->qty;
                        $stocks = $this->return_model->get_stocks_purchase_update($user->id,$row->inventory_id,$row->vendor_id);
                        foreach ($stocks as $key => $row) : 
                            $check_box_id = uniqid().$key; ?>
                        <tr>
                            <td center>
                                <fieldset class="vendors">
                                    <input type='radio' name='stock_id' value="<?=$row->id?>" id="<?=$check_box_id?>" checked>
                                    <label for="<?=$check_box_id?>" class="control-label"></label>
                                </fieldset>
                            </td>
                            <td><?=$row->vendor_name?> (<?=$row->vendor_code?>)</td>
                            <td int><?=$qty?></td>
                            <td int><?=$row->purchase_rate?></td>
                            <td int><?=$row->mrp?></td>
                            <td><?=$row->expiry_date?></td>
                        </tr>
                            
                        <?php endforeach; ?>
                    </tbody>
                </table>
	        </div>         
		</div>

		<div class="col-12">
            <div class="form-group">
	            <label class="control-label"><br></label>
	            <input type="submit" class="btn btn-primary btn-sm text-white" value="Update" name="return_store" >
            </div>
        </div>
		</div>
	</form>
</div>

