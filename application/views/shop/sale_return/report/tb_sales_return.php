
<div class='table-responsive'>
	<table class='table table-bordered table-sm'>
		<thead>
			<tr>
				<th>Sr. No.</th>
				<th>Name</th>
				<th>Image</th>
				<th>Product</th>
				<th int	>Quantity</th>
				<th int>Return Rate</th>
				<th int>Total</th>
				<th int>Discount %</th>
				<th center>Date</th>
				<th>Invoice No.</th>
				<th>Remark</th>
				<th>Category</th>
				<!-- <th>Action</th>  -->
			</tr>
		</thead>
		<tbody>
		<?php 
		if (@$rows) :
			$total = 0;
			$i=1;
			foreach ($rows as $key => $value) :
				$i = $key+1;
				$total += $value->total;
				$free = (@$value->free) ? "( $value->free free )" : '';
				echo "<tr>
					<td> $i </td>
					<td> $value->name ( $value->code ) </td>
					<th><img style='cursor: pointer;'  height='35px' src='".IMGS_URL.$value->thumbnail."' alt='' data-toggle='modal' data-target='#exampleModal".$i."'></th>
					<td> $value->product_name ( $value->product_code ) </td>
					<td int> $value->qty $free </td>
					<td int> $value->rate </td>
					<td int> $value->total </td>
					<td int> $value->discount </td>
					<td center> ".date_format_func($value->date)." </td>
					<td> $value->invoice_no </td>
					<td> $value->remark </td>
					<td style='width:200px'>";?>
					<?php 
					$category_list = [];
                    foreach ($cat_pro_map as $cat) {
                        if ($cat->pro_id == $value->prod_id) {
                            $category_list[] = '(' . $cat->name . ')';
                        } 
                    }
                    
                    $truncated_category_list = implode(' ', array_slice($category_list, 0, 2));
                    if (count($category_list) > 2) { 
                        echo '<div class="category-content">';
                        echo '<span class="prev'.$i.'">' . $truncated_category_list . '... </span>';
                        echo '<span class="hidden'.$i.'" style="display: none;">' . implode(' ', $category_list) . '</span>';
                        echo '<button class="btn btn-primary btn-xs" id="read-more'.$i.'" onclick="read_more(' . $i . ')">Read More</button>';
                        echo '<button class="btn btn-primary btn-xs" id="read-less'.$i.'" onclick="read_less(' . $i . ')" style="display: none;">Read Less</button>';
                        echo '</div>';
                    } else {
                        echo '<div class="category-content">' . implode(' ', $category_list) . '</div>';
                    }?>
					<?php echo "</td>
				</tr>";?>

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
				<?php
				$i++;
			endforeach;
			echo "<tr>
					<td colspan='5' int><strong> Total </strong> </td>
					<td int> <strong> "._nf($total)." </strong> </td>
					<td colspan='5'></td>
				</tr>";
		else:
			echo "<tr><td colspan='10' class='text-center tex-danger'>Data Not Found!</td></tr>";
		endif; 
		?>
		</tbody>
	</table>
</div>


