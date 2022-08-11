<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Base;

use Abc\Auth\Admin\Models\CoreMenuModel;
use Abc\Tests\FileOps\FileOps;
use Abc\Utility\Files;
use Abc\Utility\Stringify;
use Exception;

class BaseGenerator
{
    protected string $class;
    protected string $lowercase_class;
    protected string $lowercase_class_plural;
    protected string $class_title;

    protected string $view_name;
    protected string $view_name_title;
    protected string $view_name_title_lc;
    protected string $view_name_title_plural;
    protected string $view_name_title_lc_plural;
    protected string $view_name_plural;

    protected const STRUCTURES = ['model', 'view', 'controller', 'entity', 'schema', 'module'];

    protected string $structure;
    protected $modelClass;
    protected $modelObject;
    protected array $columns;
    protected array $foreign_keys = [];

    protected string $model_suffix = 'Model';
    protected string $controller_suffix = 'Controller';
    protected string $action_suffix = 'Action';
    protected string $entity_suffix = 'Entity';

    protected string $entity_extends = 'BaseEntity';
    protected string $model_extends = 'AbstractBaseModel';
    protected string $base_extends = 'BaseController';

    protected CoreMenuModel $menuModel;
    protected array $menuComponents;
    protected string $title_tag;
    protected string $link;

    protected const MODEL_PATH = ROOT_PATH . '/App/Models';
    protected const VIEW_PATH = ROOT_PATH . '/App/Views';
    protected const CONTROLLER_PATH = ROOT_PATH . '/App/Controllers';
    protected const ENTITY_PATH = ROOT_PATH . '/App/Entity';

    protected const MODEL_NAMESPACE = 'App\\Models';
    protected const CONTROLLER_NAMESPACE = 'App\\Controllers';
    protected const ENTITY_NAMESPACE = 'App\\Entity';

    protected const HIDDEN_COLUMNS = ['id', 'created_at', 'modified_at', 'created_by'];


    public function __construct()
    {
        $this->menuModel = new CoreMenuModel();
    }

    protected function getForeignKeys(): array
    {
        $ctrl_dependencies = [];

        foreach ($this->foreign_keys as $foreign_key) {
            $ctrl_dependencies[] = [
                'name' => strtolower($foreign_key),
                'name_plural' => Stringify::pluralize(strtolower($foreign_key)),
                'code_name' => strtolower($foreign_key) . $this->model_suffix,
                'model' => Stringify::convertToStudlyCaps($foreign_key) . $this->model_suffix,
                'variable' => Stringify::pluralize(strtolower($foreign_key)),
                'title' => Stringify::titlelize(strtolower($foreign_key)),
                'title_plural' => Stringify::pluralize(Stringify::titlelize(strtolower($foreign_key))),
            ];
        }
        return $ctrl_dependencies;
    }

    protected function getModel()
    {
//        return $this->modelObject;
        return $this->modelObject;
    }

    protected function tableColumns()
    {
        $this->modelObject = new $this->modelClass;
        $columns = $this->getModel()->getColumns();

        for ($i = 0; $i < count($columns); $i++) {
            if (in_array($columns[$i], self::HIDDEN_COLUMNS)) {
                unset($columns[$i]);
            } else {
                $this->columns[$i]['name'] = $columns[$i];

                if (strpos($columns[$i], '_id')) {
                    $this->columns[$i]['index_name'] = substr($this->columns[$i]['name'], 0, -3);
                    $this->columns[$i]['index_title'] = Stringify::titlelize($this->columns[$i]['index_name']);
                } else {
                    $this->columns[$i]['title'] = Stringify::titlelize($columns[$i]);
                }
            }
        }
    }

    protected function getDataTypes(string $column_name): string
    {
        switch ($column_name) {
            case 'description':
                return 'textarea';
            case 'position':
            case 'number':
                return 'number';
            default:
                return 'text';
        }
    }

    protected function createFile(array $conditions)
    {
        (new FileOps($conditions, []))->writeToFile();
    }

    public function deleteModule($path = '')
    {
//        print_r($this->class);echo '<br>';

        $modelPath = self::MODEL_PATH . '/' . $this->class . $this->model_suffix . '.php';
        $viewPath = self::VIEW_PATH . '/' . $this->lowercase_class;
        $controllerPath = self::CONTROLLER_PATH . '/' . $this->class . $this->controller_suffix . '.php';
        $entityPath = self::ENTITY_PATH . '/' . $this->class . $this->entity_suffix . '.php';

//        echo $modelPath;echo '<br>';
//        echo $viewPath;echo '<br>';
//        echo $controllerPath;echo '<br>';
//        echo $entityPath;echo '<br>';

        if(file_exists($modelPath)) {
            unlink($modelPath);
        }

        if(file_exists($viewPath)) {
            Files::removeDirectoryRecursively($viewPath);
        }

        if(file_exists($controllerPath)) {
            unlink($controllerPath);
        }

        if(file_exists($entityPath)) {
            unlink($entityPath);
        }
    }

    protected function createView(array $viewContent)
    {
        (new FileOps([], $viewContent))->writeToViews();
    }

    protected function setMenuComponents($class)
    {
//        echo '<pre>';
//        print_r($this->menuComponents);
//        exit;
        foreach ($this->menuComponents as $menuComponent) {
            if (strpos($menuComponent['link'], $class) !== false) {
//                print_r($menuComponent);echo '<br>';
//                print_r($menuComponent['title_tag']);echo '<br>';
//                print_r($menuComponent['link']);echo '<br>';
                $this->title_tag = $menuComponent['title_tag'];
                $this->link = $menuComponent['link'];
            }
        }
    }
}
