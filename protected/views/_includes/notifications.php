<?php

foreach (webApp::app()->getNotifications() as $n) { ?>

    <div class="alert alert-<?php echo notificacionTipoAlerta($n);?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $n->text;?>
    </div>

<?php }

function notificacionTipoAlerta($n)
{
    switch ($n->type) {
        case notificacion::SUCCESS:
            return 'success';
        case notificacion::ERROR:
            return 'danger';
        case notificacion::WARNING:
            return 'warning';
        case notificacion::INFO:
            return 'info';
    }

    return '';
}
