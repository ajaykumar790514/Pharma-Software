
<div class='table-responsive'>
	<table class='table table-bordered table-sm'>
		<thead>
			<tr>
				<th>Sr. No.</th>
				<th>Product</th>
				<th int>Qty</th>
				<th int>Return Rate</th>
				<th int>Total</th>
				<th>Date</th>
				<th>Invoice No.</th>
				<th>Remark</th>
				<th>Action</th> 
			</tr>
		</thead>
		<tbody id='purchase_return_table'>
		<?php 
		if (@$rows) :
			foreach ($rows as $key => $value) :
				$i = $key+1;
				echo "<tr>
					<td> $i </td>
					<td> $value->product_name </td>
					<td int> $value->qty </td>
					<td int> $value->rate </td>
					<td int> $value->total </td>
					<td> ".date_format_func($value->date)." </td>
					<td> $value->invoice_no </td>
					<td> $value->remark </td>
					<td>
					<a href='javascript:void(0)' data-toggle='modal' data-target='#showModal' data-whatever='Update Purchase Return ( $value->product_name )' data-url='" . $update_url . $value->id . "'>
						<i class='fa fa-edit'></i>
					</a>
					<a href='javascript:void(0)' onclick='delete_purchase_return($value->id)'>
						<i class='fa fa-trash'></i>
					</a>
				</td>
				</tr>";
			endforeach;
		else:
			echo "<tr><td colspan=7 class='text-center tex-danger'>Data Not Found!</td></tr>";
		endif; 
		?>
		</tbody>
	</table>
</div>


<script>
    function delete_purchase_return(id){
        if(confirm('Do you want to delete?') == true)
        {
            $.ajax({
                url: "<?php echo base_url('purchase_return/delete_purchase_return/'); ?>",
                method: "POST",
                data: {
                    id:id
                },
                success:function(data){
                    $("#purchase_return_table").html(data);
                }
            });
        }
    }
	</script>
