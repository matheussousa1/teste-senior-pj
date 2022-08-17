<!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar theme-horizontal menu-light brand-blue">
        <div class="navbar-wrapper container">
            <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
                <ul class="nav pcoded-inner-navbar sidenav-inner">
                    <li class="nav-item pcoded-menu-caption">
                      <label>Menu</label>
                    </li>

                    <li class="nav-item"><a href="<?php echo SITE.'inicio/inicio' ?>" class="nav-link "><span class="pcoded-micon"><i class="fas fa-th"></i></span><span class="pcoded-mtext">Painel</span></a></li>
                    <li class="nav-item"><a href="<?php echo SITE.'usuarios/gerenciar' ?>" class="nav-link "><span class="pcoded-micon"><i class="fas fa-users-cog"></i></span><span class="pcoded-mtext">Usuários</span></a></li>
                    <li class="nav-item"><a href="<?php echo SITE.'empresas/gerenciar' ?>" class="nav-link "><span class="pcoded-micon"><i class="fas fa-cogs"></i></span><span class="pcoded-mtext">Empresas</span></a></li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->
    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
        <div class="container">
            <div class="m-header">
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
                <a href="#!" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    <img src="<?php echo SITE; ?>assets/images/logo.png" alt="" class="logo-menu">
                    
                </a>
                <a href="#!" class="mob-toggler">
                    <i class="feather icon-more-vertical"></i>
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
                        <div class="search-bar">
                            <input type="text" class="form-control border-0 shadow-none" placeholder="Search hear">
                            <button type="button" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li>
                        <div class="dropdown drp-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="feather icon-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-notification">
                                <div class="pro-head">
                                    <img src="<?php echo SITE; ?>assets/images/user/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                                    <span><?php echo $_SESSION['nomeSession']; ?></span>
                                    <a href="<?php echo SITE; ?>login/sair" class="dud-logout" title="Logout">
                                        <i class="feather icon-log-out"></i>
                                    </a>
                                </div>
                                <ul class="pro-body">
                                    <li><a href="<?php echo SITE; ?>login/sair" class="dropdown-item"><i class="feather icon-lock"></i> Sair</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->