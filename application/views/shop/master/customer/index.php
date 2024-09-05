<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->

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
                                <h3 class="card-title" id="test">Customers Data</h3>
                                <h6 class="card-subtitle"></h6>
                            </div>
                            <div class="float-left col-md-6 col-lg-6 col-sm-6">
                                <div class="row">
                                    <div class="col-8">
                                <input type="text" class="form-control form-control-sm" name="tb-search" id="tb-search" value="<?=$search?>" placeholder="Search Name / Mobile / Customer Code / GSTIN...">
                                    </div>
                                    <div class="col-4">
                                    <button class="float-right btn-sm btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Add Customer" data-url="<?=$new_url?>" >Add Customer</button>
                                    </div>
                                </div>
                              
                            
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
                                <button class="float-right btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#showModal" data-whatever="Add Customer" data-url="<?=$new_url?>" >Add Customer</button>
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
debugger;
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
debugger;
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
</script>
<script type="text/javascript">
   function fetch_city(state)
   {
    $.ajax({
        url: "<?php echo base_url('shop-master-data/fetch_city'); ?>",
        method: "POST",
        data: {
            state:state
        },
        success: function(data){
            $(".city").html(data);
        },
    });
   }
 
 function validateInput() {
 var gstin = document.getElementById("gstinInput").value;
 var isValid = vGST(gstin.trim());
 }
        
let vGST = (gnumber)=>{
    let gstVal = gnumber;
    let eMMessage = "No Errors";
    let submitButton = document.getElementById("btnsubmit");
    let errorMessage = document.getElementById("errorMessage");
    let successMessage = document.getElementById("successMessage");
    let statecode = gstVal.substring(0, 2);
    let patternstatecode=/^[0-9]{2}$/
    let threetoseven = gstVal.substring(2, 7);
    let patternthreetoseven=/^[A-Z]{5}$/
    let seventoten = gstVal.substring(7, 11);
    let patternseventoten=/^[0-9]{4}$/
    let Twelveth = gstVal.substring(11, 12);
    let patternTwelveth=/^[A-Z]{1}$/
    let Thirteen = gstVal.substring(12, 13);
    let patternThirteen=/^[1-9A-Z]{1}$/
    let fourteen = gstVal.substring(13, 14);
    let patternfourteen=/^Z$/
    let fifteen = gstVal.substring(14, 15);
    let patternfifteen=/^[0-9A-Z]{1}$/
    if (gstVal.length != 15) {
        eMMessage = 'Length should be restricted to 15 digits and should not allow anything more or less';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
            
    }else if (!patternstatecode.test(statecode)) {
        eMMessage = 'First two characters of GSTIN should be numbers';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternthreetoseven.test(threetoseven)) {
        eMMessage = 'Third to seventh characters of GSTIN should be alphabets';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternseventoten.test(seventoten)) {
        eMMessage = 'Eighth to Eleventh characters of GSTIN should be numbers';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternTwelveth.test(Twelveth)) {
        eMMessage = 'Twelveth character of GSTIN should be alphabet';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternThirteen.test(Thirteen)) {
        eMMessage = 'Thirteen characters of GSTIN can be either alphabet or numeric';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternfourteen.test(fourteen)) {
        eMMessage = 'fourteen characters of GSTIN should be Z';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else if (!patternfifteen.test(fifteen)) {
        eMMessage = 'fifteen characters of GSTIN can be either alphabet or numeric';
        submitButton.disabled = true;
        successMessage.innerText = "";
        errorMessage.innerText = eMMessage;
        
    }else{
        submitButton.disabled = false;
        successMessage.innerText = "Valid GSTIN!.";
        errorMessage.innerText = "";
    }
    console.log(eMMessage)
}


</script>

