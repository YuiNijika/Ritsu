<?php

/**
 * 一个基于Vue3+Antdv构建的Typecho主题
 * @package Ritsu
 * @author 鼠子(Tomoriゞ)
 * @version 1.0.0
 * @link https://github.com/ShuShuicu/Typecho-Theme-Development-Framework
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <?php TTDF_Hook::do_action('load_head'); ?>
    <link rel="icon" href="<?php get_options('FaviconUrl') ?>">
    <script type="module" crossorigin src="<?php get_theme_file_url('Assets/index-bCl628V9.js?ver=') . get_theme_version(); ?>"></script>
    <link rel="modulepreload" crossorigin href="<?php get_theme_file_url('Assets/vue-vendor-CJLHb3Yq.js?ver=') . get_theme_version(); ?>">
    <link rel="stylesheet" crossorigin href="<?php get_theme_file_url('Assets/index-yIrybjE3.css?ver=') . get_theme_version(); ?>">
</head>

<body>
    <div id="app"></div>
    <?php TTDF_Hook::do_action('load_foot'); ?>
</body>

</html>