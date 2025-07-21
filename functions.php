<?php

/**
 * 主题核心文件
 * Theme core file
 * @link https://github.com/YuiNijika/TTDF
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
// 引入框架配置文件
require_once __DIR__ . '/Core/TTDF.Config.php';

/**
 * 主题自定义代码
 * theme custom code
 */
/**
 * 自定义function代码
 * @example 下方写入主题的自定义代码
 */
TTDF_Hook::add_action('load_head', function () {
    Get::Options('Custom_Code_Head', true);
});
TTDF_Hook::add_action('load_foot', function () {
    Get::Options('Custom_Code_Foot', true);
});