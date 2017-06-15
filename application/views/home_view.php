<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Designaciones Usuarios</title>
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/angular-growl.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/default.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/textAngular.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/ng-tags-input.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/ng-tags-input.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/ngDialog.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>css/ngDialog-theme-default.min.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
                    var base_url_login = "<?php echo $login_url; ?>";</script>
    </head>
    <body ng-app="app">
        <?php
        if (check_id()) {
            ?>
            <div ng-init="$root.usuario_acceso = {
                                                                    username: '<?php echo $_SESSION['u_designaciones']['username']; ?>',
                                                                    id:<?php echo $_SESSION['u_designaciones']['id']; ?>,
                                                                    permisos:[
            <?php foreach ($_SESSION['u_designaciones']['permisos'] as $permiso) {
                ?>{sitio:<?php echo $permiso['sitio']; ?>},<?php }
            ?>]
                                                                    }">
            </div>
            <?php
        } else {
            ?>
            <div ng-init="$root.usuario_acceso = false"></div>
            <?php
        }
        ?>
        <div ui-view></div>
        <div growl></div>
        <script src="<?php echo base_url(); ?>js/vendors/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/vendors/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/vendors/angular.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/angular-growl.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/angular-ui-router.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/angular-uhttp.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/angular-sanitize.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/ng-file-upload.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/ng-file-upload-shim.min.js" type="text/javascript"></script><!-- para subir archivos en instituciones -->
        <script src="<?php echo base_url(); ?>js/modules/ngDialog.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/angular-strap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/angular-strap.tpl.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/ocLazyLoad.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/textAngular-rangy.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/textAngular-sanitize.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/textAngular.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/sortable.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/modules/jcs-auto-validate.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/app.js" type="text/javascript"></script>
    </body>
</html>
