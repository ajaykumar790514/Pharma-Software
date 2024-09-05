<!-- <div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?= $page + 1 ?> to <?= $page + count($cashvendor) ?> of <?= $total_rows ?> entries</span>
    </div>
    <div class="col-md-6 text-right">
    <?= $links ?>
    </div>
</div> -->


<div class="col-12">
    <div id="grid_table">
        <table class="jsgrid-table" id="Bankable">
            <tr class="jsgrid-header-row">
                <th class="jsgrid-header-cell jsgrid-align-center">S.No.</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Transaction Date</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Particulars</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Expense Account</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Reference No</th>
                <th class="jsgrid-header-cell jsgrid-align-center" int>Debit (₹)</th>
                <th class="jsgrid-header-cell jsgrid-align-center">Action</th>
            </tr>
            <?php $i = $page+1;
            foreach ($cashvendor as $value) { 
            ?>
                <tr class="jsgrid-filter-row">
                    <td class="jsgrid-cell jsgrid-align-center">
                        <?php echo $i++; ?></td>
                    <td class="jsgrid-cell jsgrid-align-center">
                        <?= date_format_func($value->PaymentDate);?></td>
                    <td class="jsgrid-cell jsgrid-align-center">
                        <?php echo $value->name;?></td>
                        <td class="jsgrid-cell jsgrid-align-center">
                        <?php echo $value->title;?></td>
                    <td class="jsgrid-cell jsgrid-align-center" id="status<?php echo $value->reference_no; ?>">
                        <?php echo $value->reference_no; ?></td>    
                    
                    <td class="jsgrid-cell jsgrid-align-center" id="status<?php echo $value->dr; ?>" int>
                        <?php echo $value->dr; ?> 
                    </td>
                   
                    <td class="jsgrid-cell jsgrid-align-center">
                        <a data-toggle="modal" href="#" onclick="showmodel(<?php echo  $value->id ?>)" data-target="#showModal"><i class="fa fa-edit"></i></a>
                        <a href="<?php echo base_url('bank-register/delete/' . $value->id); ?>" onclick="return confirm('Do you want to delete this?')"><i class="fa fa-trash" style="color:red"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6 text-left">
        <span>Showing <?= $page + 1 ?> to <?= $page + count($cashvendor) ?> of <?= $total_rows ?> entries</span>
    </div>
    <div class="col-md-6 text-right">
    <?= $links ?>
    </div>
</div>