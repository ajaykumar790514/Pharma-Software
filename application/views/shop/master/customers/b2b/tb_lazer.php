<div class="card-body card-dashboard">
   <div class="row mt-5 mb-5">
        <div class="col-sm-12 col-md-12">
            <h3 class="text-center"><strong>Customer Details</strong></h3>
            <hr>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 card bg-white">
                    <table class="table table-bordered base-style">
                        <tr>
                            <td>Customer Name :- </td>
                            <th><?php echo  $customer->fname ?? '' ;?> <?php echo  $customer->mname ?? '' ;?>  <?php echo  $customer->lname ?? '' ;?> </th>
                            <td>Mobile Number :- </td>
                            <th><?php echo  $customer->mobile ?? '' ;?>  </th>
                        </tr>
                        <tr>
                            <td>Email :- </td>
                            <th><?php echo  $customer->email ?? '' ;?> </th>
                        </tr>
                    </table>
                </div>
                <div class="col-md-2 mt-5">
                      <a href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Transaction  (<?php echo  $customer->fname ?? '' ;?> <?php echo  $customer->mname ?? '' ;?>  <?php echo  $customer->lname ?? '' ;?> &nbsp;&nbsp; , <?php echo $customer->mobile ??'' ;?> ) " data-url="<?=$new_transaction?><?=$customer->id ?? '' ;?>" class="btn btn-primary btn-sm" class="btn btn-primary btn-sm add-btn"> 
                                        <i class="ft-plus"></i> Add Transaction
                       </a>
                </div>
            </div>
            <hr>
        </div>
    </div>
   <div class="table-responsive pt-1">
       <table class="table table-bordered base-style" id="mytable">
        <thead>
       <tr>
        <th>Date</th>
        <th>Type</th>
        <th style="width:120px">Udhar/Borrow/Debit</th>
        <th>Received</th>
        <th>Balance</th>
        <th>Remark</th>
        <th>Action</th>
        </tr> 
        </thead>
        <tbody>
         
        <?php $totaldr=$totalcr=$totalbal =0; $i=1;foreach($opening as $row){

$newDate = date("d-m-Y", strtotime($row->tr_date));
if(!empty($_POST['start_date'])){
        $totalbal = $totalbal+ $row->credit-$row->debit;
         $totaldr = $totaldr + $row->debit;
         $totalcr = $totalcr + $row->credit;
        }else{
           $totalbal;
        } 

         $i++;  }?>
        <tr>
                <td><?php echo $newDate ?? '' ?></td>
                <td>Opening Balance </td>
                <td></td>
                <td></td>
                <td>
            <?php echo  number_format((float)(abs($totalbal ?? '')), 2, '.', '');?> <?php if($totaldr>$totalcr){echo "Dr";}elseif($totaldr<$totalcr){echo "Cr";};?>
                </td>
                <td></td>
                <td></td>
            </tr>
     
            <?php $lasttotal=0;$totalcredit=0;$totaldebit=0; $i=1;foreach($rows as $row){
          
                ?>
            
            <tr>
                <td><?php echo $newDate = date("d-m-Y", strtotime($row->tr_date));?></td>
                <td><?php if($row->credit =='0.00'){echo "Sale" ;}else if($row->debit =='0.00'){ echo "Amount Received";}else{echo "N/A";} ;?></td>
                <td style="color:red"><?=$row->debit;?></td>
                <td style="color:green"><?=$row->credit;?></td>
                <td>
                     <?php   $totalbal = $totalbal+ $row->credit-$row->debit;
                     echo number_format((float)(abs($totalbal)), 2, '.', '');
                     $totaldebit=$totaldebit+$row->debit;
                     $totalcredit=$totalcredit+$row->credit;
                     $lasttotal=$lasttotal+$totalbal;
                     if($totaldebit > $totalcredit){echo " Dr";}else if($totaldebit < $totalcredit){echo " Cr";} ;
                       ?></td>
                <td><?=$row->remark;?></td>

                    <td> 
                       <a href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Update Transaction  (<?php echo $customer->fname ?? '' ?>  <?php echo $customer->lname ?? '' ?>)" data-url="<?=$update_url?><?=$row->id?>" title="Update">
                           <i class="fa fa-edit"></i>
                       </a>

                       <a href="javascript:void(0)" onclick="_delete(this)" url="<?=$delete_url?><?=$row->id?>" title="Delete" >
                           <i class="fa fa-trash"></i>
                       </a>
                    </td>
                   <?php  
                    // $totaldebit=$totaldebit+$row->debit;
                    // $totalcredit=$totalcredit+$row->credit;
                    //  $lasttotal=$lasttotal+$totalbal;
                     ?>
            </tr>
            <?php $i++;}?>
            <tr>
                <td></td>
                <td class="text-right"><b>Total :</b></td>
                <td style="border-top: 2px solid black;"><b><?php echo number_format((float)$totaldebit, 2, '.', '');?></b></td>
                <td style="border-top: 2px solid black;"><b><?php echo number_format((float)$totalcredit, 2, '.', ''); ?></b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right"><b><?php if($totaldebit>$totalcredit){echo "Debit balance : ";}else{echo "Credit balance : ";}?></b></td>
                <td style="border-top: 2px solid black;"></td>
                <td style="border-top: 2px solid black;"><b><?php if($totaldebit>$totalcredit){echo   number_format((float)(abs($totaldebit-$totalcredit)), 2, '.', '');}else{echo number_format((float)(abs($totalcredit-$totaldebit)), 2, '.', '');}?></b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right"><b>Grand Total :</b></td>
                <td style="border-top: 2px solid black;border-bottom: 2px solid black;color:red"><b><?php echo number_format((float)$totaldebit, 2, '.', '');?></b></td>
                <td style="border-top: 2px solid black;;border-bottom: 2px solid black;color:green"><b><?php echo number_format((float)(abs($totalcredit)), 2, '.', '');?></b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
       </table>

   </div>
