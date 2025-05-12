<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE html>
<html lang="<?php Get::Options('lang') ?>">

<head>
    <?php TTDF_Hook::do_action('load_head'); ?>
    <link href="<?php echo Get::Options('FaviconUrl', false) ? Get::Options('FaviconUrl', false) : Get::SiteUrl(false) . 'favicon.ico'; ?>" rel="icon" />
    <script type="module" crossorigin src="<?php GetTheme::AssetsUrl(); ?>/index-B8HhW5lS.js"></script>
    <link rel="modulepreload" crossorigin href="<?php GetTheme::AssetsUrl(); ?>/vue-vendor-C3OiIXBd.js">
    <link rel="stylesheet" crossorigin href="<?php GetTheme::AssetsUrl(); ?>/index-yIrybjE3.css">
</head>

<body>
    <div id="app"></div>
    <?php TTDF_Hook::do_action('load_foot'); ?>
</body>

</html>