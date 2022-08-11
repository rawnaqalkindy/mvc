<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

namespace Abc\Base;

use Abc\Auth\Admin\Models\CoreMenuModel;
use Abc\Auth\Admin\Models\CoreSectionModel;
use Abc\Auth\Authorized;
use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Flash;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseView
{
    private static array $sections;
    private static array $menus;
    private static string $my_theme;

    public function __construct($sections = [], $menus = [])
    {
        self::$my_theme = (MY_THEME == null || MY_THEME == '') ? DEFAULT_THEME : MY_THEME;

        if (Authorized::getCurrentSessionID()) {
            self::$sections = !empty($sections) ? $sections : $this->getSections();
            self::$menus = !empty($menus) ? $menus : $this->getMenus();
        }
    }

    private function getMenus(): array
    {
        return (new CoreMenuModel)->getRepository()->findBy(['id', 'name', 'section_id', 'position', 'link', 'icon'],['state_id' => ACTIVE_STATE_ID],[],['orderby' => 'position ASC']);
    }

    private function getSections(): array
    {
        return (new CoreSectionModel)->getRepository()->findBy(['id', 'name', 'position'],['state_id' => ACTIVE_STATE_ID],[],['orderby' => 'position ASC']);
    }

    public static function render(string $module, string $template, array $optional_view_data = [])
    {
//        if (strtolower($module) != 'login') {
//            $template = $module . '/' . $template;
//        }
//        echo static::getTemplate($module, $template, $optional_view_data); // LENGO
        echo static::getTemplate($template, $optional_view_data);
    }

    public static function template(string $template, array $optional_view_data = [])
    {
        $file = ROOT_PATH . '/_Modules/_templates/' . $template;

        if (is_readable($file)) {
            require $file;
        } else {
            ErrorHandler::exceptionHandler(new Exception("$file not found"));
            exit;
        }
    }

//    public static function getTemplate(string $module, string $template, array $optional_view_data = []): string // LENGO


    public static function getTemplate(string $template, array $optional_view_data = []): string
    {
        static $twig = null;

//        $namespace = isset($optional_view_data['namespace']) && $optional_view_data['namespace'] != '' ? $optional_view_data['namespace'] . '/' : ''; // LENGO

        if ($twig === null) {
//            $loader = new FilesystemLoader(ROOT_PATH . '/_Modules/' . ucfirst(strtolower($namespace)) . ucfirst(strtolower($module)) . '/views'); // LENGO
            $loader = new FilesystemLoader(ROOT_PATH . '/_Modules/views/_' . self::$my_theme);
            $twig = new Environment($loader);
            $twig->addGlobal('current_user', Authorized::getUser());
            $twig->addGlobal('flash_messages', Flash::getAllFlashNotifications());
        }

        if (Authorized::getCurrentSessionID()) {
            $optional_view_data['sections'] = self::$sections;
            $optional_view_data['menus'] = self::$menus;
        }

        return $twig->render($template, $optional_view_data);
    }
}
