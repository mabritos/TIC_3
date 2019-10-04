<!DOCTYPE html>
<html>
<head>

    <title><?php echo $this->titulo . ' | ' . TITULO;?></title>

    <?php $this->renderInclude("head");?>

</head>
<body>

    <div class="login-wrap">

        <img src="<?php echo webApp::app()->getUrlAssets();?>images/logo-200x42.png" alt="" class="logo"/>

        <?php echo $loginForm->render();?>

    </div>

</body>
</html>
