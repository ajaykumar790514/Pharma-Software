<style>
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 20%;
    }
</style>
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor">Dashboard</h3>
      <?php echo $breadcrumb?>
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
                            <div>
                                <h3 class="card-title">Cash Register</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <?php foreach ($sub_menus as $menus) {  ?>
                                <div class="col-lg-3 col-md-6">
                                    <a href="<?php echo base_url($menus->url . '/' . $menu_id); ?>">
                                        <div class="card text-center" style="padding:10px;">
                                            <img class="center" src="<?php echo base_url($menus->icon_class); ?>" style="height: 50px;width:60px;" alt="Category">
                                            <div class="card-body">
                                                <h4 class="card-title"><?= $menus->title; ?></h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
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