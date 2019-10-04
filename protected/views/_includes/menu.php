
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo SITIO;?>">
            <img src="<?php echo webApp::app()->getUrlAssets();?>images/logo-100x21.png" alt="">
        </a>
    </div>

    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>

    <!-- Top Navigation: Right Menu -->
    <ul class="nav navbar-right navbar-top-links">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <?php echo webApp::app()->getUsuario();?> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li class="divider"></li>
                <li><a href="<?php echo SITIO;?>usuario/miperfil"><i class="fa fa-user fa-fw"></i> Mi perfil</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo SITIO;?>site/logout"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a></li>
            </ul>
        </li>
    </ul>

    <!-- Sidebar -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">

            <ul class="nav" id="side-menu">

                <li>
                    <a href="<?php echo SITIO;?>ventas"><i class="fa fa-shopping-cart fa-fw"></i> <span>Ventas</span></a>
                </li>

                <li>
                    <a href="<?php echo SITIO;?>leadads"><i class="fa fa-facebook fa-fw"></i> <span>Lead Ads</span></a>
                </li>

                <li>
                    <a href="<?php echo SITIO;?>usuario"><i class="fa fa-users fa-fw"></i> <span>Usuarios</span></a>
                </li>

                <li>
                    <a href="#" id="contract"></a>
                </li>

            </ul>

        </div>
    </div>

</nav>
