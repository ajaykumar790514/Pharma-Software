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
                                <h3 class="card-title" id="test">Tax Data</h3>
                                <h6 class="card-subtitle"></h6>
                            </div>

                           

                            
                        </div>
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
<div class="modal  text-left" id="showModal-xl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
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


<div class="modal  text-left" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel21" aria-hidden="true">
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

function loadtb(url=null){
    if (url!=null) {
        var tbUrl = url;
    }
    else{
        var tbUrl = $('[name="tb"]').val();
    }

    if (tbUrl!='') {
        $('#tb').load(tbUrl);
    }
}

loadtb();

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

$(document).on("submit", '.ajaxsubmit', function(event) {
    event.preventDefault(); 
    $this = $(this);

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

        data = JSON.parse(data);
        
        if (data.res=='success') {
            if(!$this.hasClass("update-form")) {
                $('[type="reset"]').click();
            }
            
            if ($this.hasClass("reload-tb")) {
                loadtb();
            }

            if ($this.hasClass("reload-page")) {
                setTimeout(function() {
                    window.location.reload();
                }, 1000); 
            }

            if ($this.hasClass("btn-click")) {
                setTimeout(function() {
                    var btn_target = $this.attr("btn-target");
                    $(btn_target).click();
                }, 1000); 
            }
        }
        alert(data.msg);
        // alert_toastr(data.res,data.msg);
      }
    })
    return false;
})
</script>
<!-- //###### ANKIT MAIN CONTENT  ######// -->

<script type="text/javascript">
function getid(proid) {
    $("#pro_content"+proid).load("<?php echo base_url('master-data/view_product_images/') ?>"+proid)
}

var timer;
        var timeout = 1500;
        $(document).on('keyup', '#tb-search', function(event){
            clearTimeout(timer);
            timer = setTimeout(function(){
                var search  = $('#tb-search').val();
                // console.log(search);
                var tbUrl = $('[name="tb"]').val();
                $.post(tbUrl,{search:search})
                .done(function(data){
                    $('#tb').html(data);
                })
            }, timeout);

            return false;
        })
</script>

