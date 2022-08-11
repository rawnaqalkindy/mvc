<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\System\Generators;

use Abc\System\Generators\Mabrex\MxAction;
use Abc\System\Generators\Mabrex\MxController;
use Abc\System\Generators\Mabrex\MxModel;
use Abc\System\Generators\Mabrex\MxView;
use Abc\Tests\FileOps\MabrexFileOps;
use Abc\Utility\Log;
use Abc\Utility\Stringify;

class MabrexModuleGenerator
{
    private array $model_specs;
    private array $controller_specs;
    private array $view_specs;

    protected string $class;
    protected string $model_class;
    protected string $lowercase_class;
    protected string $lowercase_class_plural;
    protected string $class_title;
    protected string $class_title_plural;
    protected string $lowercase_class_title;
    protected string $lowercase_class_title_plural;

    protected string $view_name;
    protected string $view_name_title;
    protected string $view_name_title_lc;
    protected string $view_name_title_plural;
    protected string $view_name_title_lc_plural;
    protected string $view_name_plural;

    protected string $model_extends = 'Model';
    protected string $controller_extends = 'Controller';
    protected string $model_class_suffix = '_Model';
    protected const MX_PATH = STORAGE_PATH . '/mx/';

    protected Stringify $stringify;


    public function __construct(string $class, array $controller_specs, array $model_specs, array $view_specs, $delete = false)
    {
        if (!$delete) {
//            print_r($controller_specs);
//            exit;
            $this->stringify = new Stringify;

            $this->controller_specs = $controller_specs;
            $this->model_specs = $model_specs;
            $this->view_specs = $view_specs;

            $this->class = $this->stringify->convertToStudlyCaps($class);
            $this->model_class = $this->stringify->convertToStudlyCaps($class) . $this->model_class_suffix;
            $this->lowercase_class = strtolower($class);
            $this->lowercase_class_plural = $this->stringify->pluralize($this->lowercase_class);
            $this->class_title = $this->stringify->titlelize($class);
            $this->class_title_plural = $this->stringify->pluralize($this->class_title);
            $this->lowercase_class_title = $this->stringify->underscoreSeparate($this->lowercase_class);
            $this->lowercase_class_title_plural = $this->stringify->pluralize($this->lowercase_class_title);

            $this->view_name = $class;
            $this->view_name_plural = $this->stringify->pluralize($class);
            $this->view_name_title = $this->stringify->titlelize($this->view_name);
            $this->view_name_title_lc = strtolower($this->view_name_title);
            $this->view_name_title_plural = $this->stringify->pluralize($this->view_name_title);
            $this->view_name_title_lc_plural = strtolower($this->view_name_title_plural);

            $this->generateModel();
            $this->generateViews();
            $this->generateController();
//            $this->generateActions();
        }
    }







    public function getVariables(): self
    {
        return $this;
    }

    private function generateModel()
    {
        Log::evo_log('Creating ' . $this->class . 'Model class');
        $conditions = [
            'content' => (new MxModel($this->class, $this->controller_specs, $this->model_specs, $this->view_specs))->getModelContent(),
            'class' => $this->model_class,
            'uses' => [
                'Libs\\Model',
                'Libs\\Perm_Auth',
            ],
//            'use_foreign_models' => '',
            'extras' => [
                'extends' => $this->model_extends,
//                'implements' => '',
            ],
            'filepath' => self::MX_PATH . $this->class,
        ];

//        print_r($conditions['filepath']);
//        exit;

        if (!is_dir($conditions['filepath'])) {
            mkdir($conditions['filepath']);
        }

        $this->createMabrexFile($conditions);
        Log::evo_log('Finished creating ' . $this->class . 'Model class');
    }

    private function generateViews()
    {
        Log::evo_log('Creating ' . $this->class . 'view files');
        $viewContent = (new MxView($this->class, $this->controller_specs, $this->model_specs, $this->view_specs))->getViewContent();

        if (!file_exists(self::MX_PATH . DS . $this->class . DS . 'Views')) {
            mkdir(self::MX_PATH . DS . $this->class . DS . 'Views', 0777, true);
        }

        $this->createMabrexView($viewContent);
        Log::evo_log('Finished creating ' . $this->class . ' view files');
    }

    private function generateController()
    {
        Log::evo_log('Creating ' . $this->class . 'Controller class');
        $conditions = [
            'content' => (new MxController($this->class, $this->controller_specs, $this->model_specs, $this->view_specs))->getControllerContent(),
            'class' => $this->class,
            'uses' => [
                'Exception',
                'Libs\\Controller',
                'Libs\\Perm_Auth',
                'Modules\\' . $this->class . '\\Actions\\Add' . $this->class,
                'Modules\\' . $this->class . '\\Actions\\Update' . $this->class,
            ],
//            'use_foreign_models' => '',
            'extras' => [
                'extends' => $this->controller_extends,
//                'implements' => '',
            ],
            'filepath' => self::MX_PATH . $this->class,
        ];

//        print_r($conditions['filepath']);
//        exit;

        if (!is_dir($conditions['filepath'])) {
            mkdir($conditions['filepath']);
        }

        $this->createMabrexFile($conditions);
        Log::evo_log('Finished creating ' . $this->class . 'Controller class');
    }

    private function generateActions()
    {
        $actionContent = [
            'content' => (new MxAction($this->class, $this->controller_specs, $this->model_specs, $this->view_specs))->getActionContent(),
            'class' => $this->class,
            'uses' => [
                'Exception',
                'Libs\\Controller',
                'Libs\\Log',
                'Modules\\' . $this->class . '\\' . $this->class . '_Model',
            ],
//            'use_foreign_models' => '',
            'extras' => [
                'extends' => $this->controller_extends,
//                'implements' => '',
            ],
            'filepath' => self::MX_PATH . $this->class . DS . 'Actions',
        ];

        if (!file_exists($actionContent['filepath'])) {
            mkdir(self::MX_PATH . DS . $this->class . DS . 'Actions', 0777, true);
        }

        $this->createMabrexActionFile($actionContent);
    }





    protected function createMabrexFile(array $conditions)
    {
        (new MabrexFileOps($conditions, [], []))->writeToMabrexFile();
    }

    protected function createMabrexView(array $viewContent)
    {
        (new MabrexFileOps([], $viewContent, []))->writeToViews();
    }

    protected function createMabrexActionFile(array $actionContent)
    {
        (new MabrexFileOps([], [], $actionContent))->writeToMabrexActionFile();
    }

    public function deleteMabrexModule($path = '')
    {
//        print_r($this->class);echo '<br>';

        $module_path = self::MX_PATH . DS . $this->class;

        if(file_exists($module_path)) {
            unlink($module_path);
        }
    }









}