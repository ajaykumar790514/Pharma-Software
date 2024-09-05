<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-wrapper">
<div class="container-fluid" style="max-width: 100% !important;">
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('admin-dashboard'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('master-data/'.$menu_id); ?>">Master Data</a></li>
            <li class="breadcrumb-item active">Ingredients</li>
        </ol>
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
                                <h3 class="card-title" id="test">Ingredients Data</h3>
                                <h6 class="card-subtitle"></h6>
                            </div>

                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <button class="float-right btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Add Ingredients" data-url="<?=$new_url?>" >Add Ingredient</button>
                            </div>

                            
                        </div>
                    </div>

                    <div class="col-12" id="tb">
                        
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                            </div>

                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <button class="float-right btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Add Ingredients" data-url="<?=$new_url?>" >Add Ingredient</button>
                            </div>

                            
                        </div>
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
          <!-- <div class="modal-footer">
              <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
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
    var element = document.getElementById("loader");
    element.className = 'fa fa-spinner fa-spin';
    $("#btnsubmit").prop('disabled', true);
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
            var element = document.getElementById("loader");
                element.classList.remove("fa-spinner");
                $("#btnsubmit").prop('disabled', false);
            if(!$this.hasClass("update-form")) {
                $('[type="reset"]').click();
            }
            if($this.hasClass("add-form")) {
                $('#showModal').modal('hide');
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
        var element = document.getElementById("loader");
                element.classList.remove("fa-spinner");
                $("#btnsubmit").prop('disabled', false);
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
// $(document).ready(function(){
// $("#view-product-images").load("<?php echo base_url('master-data/view_product_images'); ?>")
// })

var timer;
        var timeout = 500;
        $(document).on('keyup', '#tb-search', function(event){
            if(event.keyCode == 13)
            {
                $("#datatable").html('<div class="text-center"><img src="loader.gif"></div>');
            clearTimeout(timer);
            timer = setTimeout(function(){
                var search  = $('#tb-search').val();
                var parent_id = $('#parent_id').val();
                var parent_cat_id  = $('#parent_cat_id').val();
                var child_cat_id = $('#cat_id').val();
                var tbUrl = $('[name="tb"]').val();
                $.post(tbUrl,{search:search,
                    parent_id:parent_id,
                    cat_id:parent_cat_id,
                    child_cat_id:child_cat_id
                })
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
   function fetch_shop(business_id)
   {
    $.ajax({
        url: "<?php echo base_url('master-data/fetch_shop'); ?>",
        method: "POST",
        data: {
            business_id:business_id
        },
        success: function(data){
            $(".shop_id").html(data);
        },
    });
   }
</script>

<script type="text/javascript">
   function fetch_sub_categories(parent_id)
   {
    $.ajax({
        url: "<?php echo base_url('master-data/fetch_sub_categories'); ?>",
        method: "POST",
        data: {
            parent_id:parent_id //cat1 id
        },
        success: function(data){
            $(".parent_cat_id").html(data);

            var cat_id = $('#parent_cat_id').val(); //cat2 id
            if(parent_id)
            {
                $.ajax({
                    url: "<?php echo base_url('master-data/products/tb'); ?>",
                    method: "POST",
                    data: {
                        cat_id:cat_id,
                        parent_id:parent_id,
                    },
                    success: function(data){
                    $("#tb").html(data);
                    },
                });
            }
        },
    });
   };
   // function fetch_category(parent_cat_id)
   // {
   //  $.ajax({
   //      url: "<?php echo base_url('master-data/fetch_cat'); ?>",
   //      method: "POST",
   //      data: {
   //          parent_cat_id:parent_cat_id
   //      },
   //      success: function(data){
   //          // console.log(data);
   //          $("#level-third-cat").html(data);
   //      },
   //  });
   // }
   function fetch_update_category(parent_cat_id)
   {
    $.ajax({
        url: "<?php echo base_url('master-data/fetch_cat'); ?>",
        method: "POST",
        data: {
            parent_cat_id:parent_cat_id
        },
        success: function(data){
            // $(".update_cat_id").html(data);
             $("#level-third-cat").html(data);
        },
    });
   }

   //by zahid
   function select_parent_cat(btn,cat_id1,cat_id2){
    // console.log(btn);
    $('#defaultCheck'+cat_id1).prop('checked', true);
    $('#defaultCheck'+cat_id2).prop('checked', true);
   }
</script>



