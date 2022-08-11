<?php

namespace Abc\System\Generators\Mabrex;

use Abc\Utility\Stringify;

class MxAction
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

    protected string $model_class_suffix = '_Model';


    protected Stringify $stringify;

    public function __construct(string $class, array $controller_specs, array $model_specs, array $view_specs)
    {
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
    }

    public function getActionContent(): string
    {
        $file = $this->getActionConstruct() . PHP_EOL;
        $file .= $this->getActionInitMethod() . PHP_EOL;

        return $file;
    }

    private function getActionConstruct()
    {
    }

    private function getActionInitMethod()
    {
    }

    private function saveFunctionality(): string
    {
        $file = "\t" . 'public function save' . $this->class . '($posted_data): int' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . '$this->db->beginTransaction();' . PHP_EOL;
        $file .= "\t\t" . '$log = new Log();' . PHP_EOL;
        $file .= "\t\t" . 'try {' . PHP_EOL;
        $file .= $this->buildSaveColumns();
        $file .= PHP_EOL;
        $file .= "\t\t\t" . '$result = $this->create($data, $this->getTable());' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . 'if ($result) {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->db->commit();' . PHP_EOL;
        $file .= "\t\t\t\t" . 'return 200;' . PHP_EOL;
        $file .= "\t\t\t" . '} else {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->db->rollBack();' . PHP_EOL;
        $file .= "\t\t\t\t" . 'return 100;' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . '} catch (Exception $exception) {' . PHP_EOL;
        $file .= "\t\t\t" . '$log->sysErr($exception->getMessage());' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . 'return 100;' . PHP_EOL;
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function buildSaveColumns($edit = false): string
    {
        $file = '';
        $foreign_key_fetch = '';

        if (isset($this->model_specs['form_fields']) && $this->model_specs['form_fields'] != []) {
            foreach ($this->model_specs['form_fields'] as $form_field) {
                if (substr($form_field,0, 4) == 'opt_') {
                    $form_field_table = str_replace(['opt_', '_id'], '', $form_field);
                    $form_field_variable = str_replace(['opt_', '_id', 'mx_'], '', $form_field);

                    $foreign_key_fetch .= "\t\t\t" . '$' . $form_field_variable . '_id = $this->getRecordIdByRowValue(\'' . $form_field_table . '\', filter_var($posted_data[\'' . $form_field . '\'], FILTER_SANITIZE_STRING));' . PHP_EOL;
                    $file .= "\t\t\t\t" . '\'' . $form_field . '\' => filter_var($' . $form_field_variable . '_id, FILTER_SANITIZE_STRING),' . PHP_EOL;
                } else {
                    $file .= "\t\t\t\t" . '\'' . $form_field . '\' => filter_var($posted_data[\'' . $form_field . '\'], FILTER_SANITIZE_STRING),' . PHP_EOL;
                }
            }

            foreach ($this->model_specs['extra_save_fields'] as $extra_save_field) {
                if (substr($extra_save_field['name'],0, 4) == 'opt_') {
                    $file .= "\t\t\t\t" . '\'' . $extra_save_field['name'] . '\' => filter_var(' . strtoupper($extra_save_field['default_data']) . ', FILTER_SANITIZE_STRING),' . PHP_EOL;
                } else {
                    $file .= "\t\t\t\t" . '\'' . $extra_save_field['name'] . '\' => $extra_save_field[\'default_data\']' . PHP_EOL;
                }
            }

            $data = $foreign_key_fetch . PHP_EOL;
            $data .= "\t\t\t" . '$data = [' . PHP_EOL;
            $data .= $file;
            $data .= "\t\t\t\t" . '\'txt_row_value\' => $this->getGUID()' . PHP_EOL;
            $data .= "\t\t\t" . '];' . PHP_EOL;
        }

        return $data;
    }

    private function getPostEditColumns(): string
    {
        $file = '';
        $foreign_key_fetch = '';
        if (isset($this->model_specs['form_fields']) && $this->model_specs['form_fields'] != []) {
            foreach ($this->model_specs['form_fields'] as $form_field) {
                if (substr($form_field,0, 4) == 'opt_') {
                    $form_field_table = str_replace(['opt_', '_id'], '', $form_field);
                    $form_field_variable = str_replace(['opt_', '_id', 'mx_'], '', $form_field);

                    $foreign_key_fetch .= "\t\t\t\t" . '$' . $form_field_variable . '_id = $this->model->getRecordIdByRowValue(\'' . $form_field_table . '\', filter_var($posted_data[\'' . $form_field . '\'], FILTER_SANITIZE_STRING));' . PHP_EOL;
                    $file .= "\t\t\t\t\t" . '\'' . $form_field . '\' => filter_var($' . $form_field_variable . '_id, FILTER_SANITIZE_STRING),' . PHP_EOL;
                } else {
                    $file .= "\t\t\t\t\t" . '\'' . $form_field . '\' => filter_var($posted_data[\'' . $form_field . '\'], FILTER_SANITIZE_STRING),' . PHP_EOL;
                }
            }
        } else {
            $file .= "\t\t\t\t\t" . '\'txt_name\' => filter_var($posted_data[\'txt_name\'], FILTER_SANITIZE_STRING),' . PHP_EOL;
            $file .= "\t\t\t\t\t" . '\'txt_abbreviation\' => filter_var($posted_data[\'txt_abbreviation\'], FILTER_SANITIZE_STRING),' . PHP_EOL;
        }

        $data = $foreign_key_fetch . PHP_EOL;
        $data .= "\t\t\t\t" . '$data = [' . PHP_EOL;
        $data .= $file;
        $data .= "\t\t\t\t" . '];' . PHP_EOL;

        return $data;
    }
}