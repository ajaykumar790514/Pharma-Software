<!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <style>       
 .dropdown-menu {
  color: black;
  padding: 5px;
  background-color: #fff;
  font-size: 16px;
  border: none;

}

.dropdown {
  position: relative;
  display: inline-block;
  /* padding: -8rem !important; */
  left: -70px;
}
.sidebar-nav ul li ul {
    padding: 0px !important;
}
.dropdown-menu {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 200px;
  width: 220px;
  margin-left: -8rem !important;
  box-shadow: 0px 4px 8px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-menu a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
 
}

.dropdown-menu a:hover {background-color: #ddd;}
.nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .sidebar-nav ul {
    margin: 0px;
    padding: 6px;
    right: 92px;
    left: 100px;
}
.dropdown .nav-links
{
    font-size: 1rem;
    margin-left: 0rem;
    padding: 8px;
    color:#fff;
}

.scroll-sidebar
{
    max-width:27% !important;
   margin-left: 100px;
}
.sidebar-nav ul li a {
    color: #607d8b;
    padding: 8px 13px;
    display: block;
    font-size: 14px;
    white-space: nowrap;
}
.nav-link {
    display: block;
    padding: 0.1rem 3rem;
}
.navbar-expand-lg li:hover > ul.dropdown-menu {
    display: block;
}
.dropdown-submenu {
    position:relative;
}
.dropdown-submenu>.dropdown-menu {
    top:0;
    left:100%;
    margin-top:-6px;
}

/* rotate caret on hover */
.dropdown-menu > li > a:hover:after {
    text-decoration: underline;
    transform: rotate(-90deg);
}
.dropdown-submenu .dropdown-menu {
    display: none !important;
}

.dropdown-submenu:hover > ul.dropdown-menu {
    display: block !important;
}


        </style>
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <?php $roleid = $user->role_id;?>
                    <?php foreach($shop_menus as $menu):?>
                     <?php $rs =  $this->admin_model->get_submenu_data($menu->id,$roleid);
                      $menu_flag ='0';
                     foreach($rs as $all) 
                     {
                     
                      if($menu->id == $all->parent)
                      {
                          $menu_flag ='1';
                          break;
                      }
                    }
                      if($menu_flag == '1')
                      {
                          $url = $menu->url.'/'.$menu->id;
                      }
                      else if($menu_flag == '0')
                      {
                          $url = $menu->url.'/'.$menu->id;
                      }
                      
                       ?>
                       <!-- -->
                            <?php if($menu_flag>0){?>
                            <li class="nav-item dropdown">
                                <a href="<?=base_url($url);?>" class="nav-link nav-links dropdown-toggle"  id="navbarDropdown<?= str_replace(' ', '', $menu->title) ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?= $menu->icon_class; ?>"></i> <?= $menu->title; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown<?= str_replace(' ', '', $menu->title) ?>">
                                <?php foreach($rs as $r): 
                                        $submenu = $this->admin_model->getSubMenuData($r->id,$roleid);
                                         $submenucount =  count($submenu);
                                         if($submenucount ==0){
                                        ?>
                                        <li><a style="position: relative;left: -1px;" class="dropdown dropdown-submenu" href="<?php echo base_url($r->url.'/'.$r->id); ?>"><?= $r->title ?></a></li>
                                        <?php }else{?>
                                        <li class="dropdown dropdown-submenu">
                                        <a style="position: relative;left: 68px;padding-right: 180px;"  href="<?php echo base_url($r->url.'/'.$r->id); ?>"  class="dropdown-toggle"  style="padding-right: 140px;"><?= $r->title ?>
                                         </a>
                                        <ul class="dropdown-menu" style="position: absolute; left: 413px;top: 6px;">
                                        <?php foreach($submenu as $men):?>
                                        <li class="more"><a href="<?php echo base_url($men->url.'/'.$men->id); ?>"><?= $men->title ?></a>
                                        <li>      
                                            <?php endforeach;?>                          
                                        </ul>
                                      </li> 
                                    <?php } endforeach; ?>
                                </ul>
                             
                            </li>
                            <?php }else{?>
                                <li class="nav-item dropdown">
                                <a class="nav-link nav-links dropdown-toggles" href="<?=base_url($url);?>" id="navbarDropdown<?= str_replace(' ', '', $menu->title) ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?= $menu->icon_class; ?>"></i> <?= $menu->title; ?>
                                </a>
                             
                            </li>
                            <?php }?>
                        <?php endforeach; ?>
                        
                    </div>
                    </nav>
                </nav>
            </div>
            <!-- End Sidebar scroll-->
        </aside>

        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->