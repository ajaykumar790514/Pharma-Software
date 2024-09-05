
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
     <?php echo $breadcrumb;?>
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
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <h3 class="card-title" id="test"><?=$title?></h3>
                                <h6 class="card-subtitle"></h6>
                            </div>

                           

                            
                        </div>
                    </div>
                    <div class="col-12 ">
                        <form action="<?=$tb_url?>" class="tb-filter" method="post">
                                <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                <label class="control-label">Date:</label>
                                <input type="date" name="date" class="form-control">
                                    
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                <label class="control-label">Vendors:</label>
                                <select class="form-control" style="width:100%;" name="vendor_id" id="vendor_id">
                                    <option value="">Select</option>
                                    <?php foreach ($vendors as $vendor) { ?>
                                        <option value="<?=$vendor->id?>" >
                                            <?=$vendor->name?>
                                        </option>
                                <?php } ?>
                                </select>
                                </div>
                            </div>

                            

                            <div class="col-md-2">
                                <div class="form-group">
                                <label class="control-label">Brand : </label>
                                <select class="form-control" style="width:100%;" name="brand_id" id="brand_id" >
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
                                <select class="form-control" style="width:100%;" name="product_id" id="product_id" >
                                <option value="">Select Brand First</option>
                                
                                </select>
                                </div>
                            </div>

                            

                           
                            <div class="col-md-2 col-xs-6">
                                <div class="form-group">
                                <label class="control-label"><br></label> 
                                <input type="submit" value="Filter" class="form-control btn btn-success text-white">
                                </div>
                            </div>

                            <div class="col-md-2 col-xs-6">
                                <div class="form-group">
                                <label class="control-label"><br></label> 
                                <input type="reset" value="Reset" class="form-control btn btn-danger text-white">
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
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->

<!-- //###### ANKIT MAIN CONTENT  ######// -->
<input type="hidden" name="tb" value="<?=$tb_url?>">
<div class="modal fade text-left" id="showModal-xl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
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


<div class="modal fade text-left" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
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
      </div>
  </div>
</div>
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


</script>
<!-- //###### ANKIT MAIN CONTENT  ######// -->

<script type="text/javascript">
function getid(proid) {
    $("#pro_content"+proid).load("<?php echo base_url('master-data/view_product_images/') ?>"+proid)
}
</script>

<?php $this->load->view('shop/reports/filters_js'); ?>