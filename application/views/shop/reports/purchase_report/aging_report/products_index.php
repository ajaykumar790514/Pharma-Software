<style type="text/css">
    .form-group {
    margin-bottom: 10px;
}
</style>
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
       <?php echo $breadcrumb;?>
    </div>
</div>

<div class="row">       
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div class="float-left col-md-10 col-lg-10 col-sm-12">
                                <h3 class="card-title" id="test"><?=$title?></h3>
                                <h6 class="card-subtitle"></h6>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!--<div class="col-12 text-right">-->
    
                    <!--        <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-3" id="reset-data" title="Reset"><i class="fas fa-retweet"></i></a>-->
   
   
                    <!--</div>-->
                    

                      <div class="col-12 ">
                        <form action="<?=$tb_url?>" class="tb-filter" method="post">
                         <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                <label class="control-label">Date:</label>
                                <?php 
                                $options = array(
                                    '2024'   => '2024 - 2025',
                                    '2025'   => '2025 - 2026',
                                    '2026'   => '2026 - 2027',
                                    '2027'   => '2027 - 2028',
                                );
                                echo form_dropdown('year', $options, '',"class='form-control form-control-sm'");
                                 ?>                                    
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                <label class="control-label">Suppliers:</label>
                                <select class="form-control form-control-sm" style="width:100%;" name="vendor_id" id="vendor_id">
                                    <option value="">Select</option>
                                    <?php foreach ($vendors as $vendor) { ?>
                                        <option value="<?=$vendor->id?>" >
                                            <?=$vendor->fname.' '.$vendor->lname?>
                                        </option>
                                <?php } ?>
                                </select>
                                </div>
                            </div>

                            

                            <div class="col-md-2">
                                <div class="form-group">
                                <label class="control-label">Brand : </label>
                                <select class="form-control form-control-sm" style="width:100%;" name="brand_id" id="brand_id" >
                                <option value="">Select</option>
                                <?php foreach ($brands as $brand) { ?>
                                <option value="<?=$brand->id?>" >
                                    <?=$brand->name?>
                                </option>
                                <?php } ?>
                                </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                <label class="control-label">Product : </label>
                                <select class="form-control form-control-sm" style="width:100%;" name="product_id" id="product_id" >
                                <option value="">Select Brand First</option>
                                
                                </select>
                                </div>
                            </div>

                            

                           
                            <div class="col-md-1 col-xs-6 d-flex align-items-end">
                                <div class="form-group">
                                <input type="submit" value="Filter" class="btn btn-success btn-xs text-white">
                                </div>
                                <div class="form-group ml-1">
                                <input type="reset" value="Reset" class="btn btn-danger btn-xs text-white">
                                </div>
                            </div>

                         
                            </div>
                        </form>
                    </div>

                  
                    <div class="col-12" id="tb">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="tb" value="<?=@$tb_url?>">

<script type="text/javascript">

function loadtb(){
    $('.tb-filter').submit();
}
// loadtb();

</script>

<?php $this->load->view('shop/reports/filters_js'); ?>