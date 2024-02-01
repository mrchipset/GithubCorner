<?php

namespace TypechoPlugin\GithubCorner;

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Checkbox;
use Typecho\Widget\Helper\Form\Element\Select;
use Typecho\Widget\Helper\Form\Element\Text;
use Widget\Archive;
use Widget\Options;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * Github Corner
 *
 * @package GithubCorner
 * @author Mr.Chip
 * @version 0.0.1
 * @link https://www.xtigerkin.com
 */

class Plugin implements PluginInterface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     */
    public static function activate()
    {
        \Typecho\Plugin::factory('Widget_Archive')->header = __CLASS__ . '::render';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     */
    public static function deactivate()
    {
    }

    /**
     * 获取插件配置面板
     *
     * @param Form $form 配置面板
     */
    public static function config(Form $form)
    {
        $profile = new Text('profile', null, 'https://', _t('Github Profile'));
        $form->addInput($profile);
        $check = new Checkbox('showProfileOptions', [
            'Show' => _t('在主页上显示Github Corner Profile'),
        ], [], _t('是否在主页上显示Github Corner'));
        $form->addInput($check->multiMode());
    }

    /**
     * 个人用户的配置面板
     *
     * @param Form $form
     */
    public static function personalConfig(Form $form)
    {
    }

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render(...$args)
    {

        $widget = null;

        foreach ($args as $key => $value) {
            // var_dump(gettype($value));
            if(gettype($value) == 'object') {   // check the archive instance
                $valid = $value instanceof Archive;
                if ($valid) {
                    $widget = $value;
                }
            }
        }
        if (!is_null($widget)) { 
            $githubUrl = "";
            if ($widget->getArchiveType() == 'index') {
                // show profile on index
                $profile = Options::alloc()->plugin('GithubCorner')->profile;
                $showOption  = Options::alloc()->plugin('GithubCorner')->showProfileOptions;
                if ($showOption != null && count($showOption, COUNT_NORMAL) > 0) {
                    // show profile options is checked
                    // echo $profile;
                    $githubUrl = $profile;
                    require_once 'Corner.php';
                }
            } else {
                // check the data show if `repository` field is existed.
                // var_dump($widget->getArchiveType());
                // echo $widget->getArchiveType();
                $field = $widget->fields->repository;
                if (!is_null($field)) {
                    $githubUrl = $field;
                    require_once 'Corner.php';
                }
            }

        }


    }
}

