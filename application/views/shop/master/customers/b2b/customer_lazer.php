<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-wrapper">
<div class="container-fluid" style="max-width: 100% !important;">
<div class="row page-titles">
    <div class="col-md-10 align-self-center">
        <h3 class="text-themecolor">Dashboard</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url('admin-dashboard'); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url('shop-customer-b2b/'.$menu_id); ?>">Customer Data -  Lazer</a></li>
            <li class="breadcrumb-item active">Customer B2B - Lazer</li>
            <li class="breadcrumb-item text-danger active"><?=@$customer->fname.' '.@$customer->mname.' '.@$customer->lname;?></li>
        </ol>
    </div>
    <!-- <div class="col-md-2"></div> -->
    <div class="col-md-2 mt-3">
        <a href="<?=base_url('shop-customer-b2b/'.$menu_id);?>" class="btn btn-danger">Back</a>
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
      function _delete(e){
            
            Swal.fire({
              toast:true,
              type: 'warning',
              title: 'You want to delete ?',
              timer:3000,
              showConfirmButton: true,
              showCancelButton: true,
              confirmButtonText: `Yes`,
              cancelButtonText: `No`,
            }).then((result) => {
                var $this = $(e);
                url = $this.attr('url');
                if(result.value==true){
                $.post(url,function(data){
                    data = JSON.parse(data);
                    if (data.res=='success') {
                        $this.parent().parent().remove();
                        toastr.success(data.res,data.msg);
                    }else{
                        toastr.error(data.res,data.msg);
                    }
                    // alert_toastr(data.res,data.msg);
                })
                }
            }).catch(swal.noop);
        return false;
      }
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

$(document).on("submit", '.submithisabkitab', function(event) {
    //alert("Hello");
    event.preventDefault(); 
    $this = $(this);
    if ($this.hasClass("append")) {
        var append_data = $($this.attr('append-data')).val();
        $(this).append('<input type="hidden" name="append" value="'+append_data+'" /> ');

    }
    var form_data = new FormData(this);
    form_valid = true;

    if ($this.hasClass("validate-form")) {
        if ($this.valid()) {
            form_valid = true;
        }
        else{
            form_valid = true;
        }
    }

    setTimeout(function() {
        if (form_valid == true) {
            $.ajax({
                url: $this.attr("action"),
                type: $this.attr("method"),
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    data = JSON.parse(data);
                    if (data.res=='success') {
                        if ($this.hasClass("reload-tb")) {
                            loadtb();
                        }
                        if ($this.hasClass("reload-page")) {
                setTimeout(function() {
                    window.location.reload();
                }, 1000); 
            }
            toastr.success(data.res,data.msg);
                    }else{
                        toastr.error(data.res,data.msg);
                    }
                    if (data.errors) {
                        $.each(data.errors,function(key,value){
                            $this.find(`[name="${key}"]`).parents(`.form-group`).find(`.error`).text(value);
                        })
                    }
                    // toastr.success(data.res,data.msg);
                    ///loadtb();
                }
                
            })
        }
    }, 100);

    return false;
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


