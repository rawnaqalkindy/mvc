<?php

namespace Abc\System\Generators\Mabrex;

use Abc\System\Generators\MabrexModuleGenerator;
use Abc\Utility\Stringify;

class MxModel
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
    }

    public function getModelContent(): string
    {
        $file = $this->getPrivateVariables() . PHP_EOL;
        $file .= $this->getHiddenFields() . PHP_EOL;
        $file .= $this->getControls() . PHP_EOL;
        $file .= $this->getTableLabels() . PHP_EOL;
        $file .= $this->getActions() . PHP_EOL;
        $file .= $this->getTabs() . PHP_EOL;
        $file .= $this->getProfileButtons() . PHP_EOL;
        $file .= $this->getProfileHiddenColumns() . PHP_EOL;
        $file .= $this->getFormDropdowns() . PHP_EOL;
        $file .= $this->getFormHiddenColumns() . PHP_EOL;
        $file .= $this->getAssociatedRecordDetails() . PHP_EOL;
        $file .= $this->getAssociatedRecordActions() . PHP_EOL;
        $file .= $this->getTable() . PHP_EOL;
        $file .= $this->getTitle() . PHP_EOL;
        $file .= $this->getViewDir() . PHP_EOL;
        $file .= $this->getParentKey() . PHP_EOL;

        return $file;
    }

    private function getPrivateVariables(): string
    {
        $file = "\t" . 'private string $table = "mx_' . $this->lowercase_class . '";' . PHP_EOL;
        $file .= "\t" . 'private string $view_dir = "' . $this->lowercase_class . '/";' . PHP_EOL;
        $file .= "\t" . 'private string $title = "' . $this->class_title_plural . '";' . PHP_EOL;
        $file .= "\t" . 'private string $parent_key = "' . $this->lowercase_class . '_id";' . PHP_EOL;
        $file .= "\t" . 'private string $view_table = "mx_' . $this->lowercase_class . '_view";' . PHP_EOL;

        return $file;
    }

    private function getHiddenFields(): string
    {
        $file = "\t" . 'public function getHiddenFields(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return [' . PHP_EOL;
        $file .= $this->buildHiddenFields();
        $file .= "\t\t" . '];' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getControls(): string
    {
        $file = "\t" . 'public function getControls(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . '$permitted_sections = [];' . PHP_EOL;
        $file .= "\t\t" . '$permissions = Perm_Auth::getPermissions($_SESSION[\'id\']);' . PHP_EOL;
        $file .= $this->buildControls();
        $file .= PHP_EOL;
        $file .= "\t\t" . 'foreach ($data as $key => $permission) {' . PHP_EOL;
        $file .= "\t\t\t" . 'if ($permissions->verifyPermission($permission[\'permission\'])) {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$permitted_sections[] = $permission;' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . 'return $permitted_sections;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getTableLabels(): string
    {
        $file = "\t" . 'public function getTableLabels(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= $this->buildTableLabels() . PHP_EOL;
        $file .= "\t\t" . 'return parent::generateTableLabels($labels);' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getProfileButtons(): string
    {
        $file = "\t" . 'public function getProfileButtons($id): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . '$permitted_section = [];' . PHP_EOL;
        $file .= "\t\t" . '$permission = Perm_Auth::getPermissions(filter_var($_SESSION[\'id\'], FILTER_SANITIZE_STRING));' . PHP_EOL;
        $file .= $this->buildProfileButton() . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'if ($data) {' . PHP_EOL;
        $file .= "\t\t\t" . 'foreach ($data as $key => $value) {' . PHP_EOL;
        $file .= "\t\t\t\t" . 'if ($permission->verifyPermission($value[\'permission\'])) {' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '$permitted_section[] = $value;' . PHP_EOL;
        $file .= "\t\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . 'return $permitted_section;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getActions(): string
    {
        $file = "\t" . 'public function getActions(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return [' . PHP_EOL;
        if ($this->model_specs['actions']) {
            $file .= $this->buildActions() . PHP_EOL;
        }
        $file .= "\t\t" . '];' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getTabs(): string
    {
        $file = "\t" . 'public function getTabs(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= $this->buildTabs() . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getProfileHiddenColumns(): string
    {
        $file = "\t" . 'public function getProfileHiddenColumns(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return [' . PHP_EOL;
        $file .= $this->buildProfileHiddenFields();
        $file .= "\t\t" . '];' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getFormDropdowns(): string
    {
        $file = "\t" . 'public function getFormDropdowns(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . '$data = [];' . PHP_EOL;
        $file .= $this->buildFormDropdowns() . PHP_EOL;
        $file .= "\t\t" . 'return $data;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getFormHiddenColumns(): string
    {
        $file = "\t" . 'public function getFormHiddenColumns(): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return [' . PHP_EOL;
        $file .= $this->buildFormHiddenFields();
        $file .= "\t\t" . '];' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getAssociatedRecordDetails(): string
    {
        $file = "\t" . 'public function getAssociatedRecordDetails($caller = null): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . '// echo $caller;' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'switch ($caller) {' . PHP_EOL;
        $file .= $this->buildAssociatedSwitch();
        $file .= "\t\t\t" . 'default: return [];' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getAssociatedRecordActions(): string
    {
        $file = "\t" . 'public function getAssociatedRecordActions($caller): array' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . '$data = [];' . PHP_EOL;
        $file .= "\t\t" . 'switch (strtolower($caller)) {' . PHP_EOL;
        $file .= $this->buildAssociatedActions();
        $file .= "\t\t\t" . 'default:' . PHP_EOL;
        $file .= "\t\t\t\t" . 'break;' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . 'return $data;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getTable(): string
    {
        $file = "\t" . 'public function getTable($view_table = false): string' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'if ($view_table) {' . PHP_EOL;
        $file .= "\t\t\t" . 'return $this->view_table;' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . 'return $this->table;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getTitle(): string
    {
        $file = "\t" . 'public function getTitle(): string' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return $this->title;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getViewDir(): string
    {
        $file = "\t" . 'public function getViewDir(): string' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return $this->view_dir;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function getParentKey(): string
    {
        $file = "\t" . 'public function getParentKey(): string' . PHP_EOL;
        $file .= "\t" . '{' . PHP_EOL;
        $file .= "\t\t" . 'return $this->parent_key;' . PHP_EOL;
        $file .= "\t" . '}' . PHP_EOL;

        return $file;
    }

    private function buildHiddenFields(): string
    {
        if (isset($this->model_specs['hidden_fields']) && is_array($this->model_specs['controls']) && !empty($this->model_specs['hidden_fields'])) {
            return "\t\t\t'" . implode("', '",$this->model_specs['hidden_fields']) . '\'' . PHP_EOL;
        }
        return "\t\t\t" . '\'id\', \'txt_row_value\'' . PHP_EOL;
    }

    private function buildControls(): string
    {
        if (isset($this->model_specs['controls']) && is_array($this->model_specs['controls'])) {
            $data = "\t\t" . '$data = [' . PHP_EOL;
            foreach ($this->model_specs['controls'] as $label) {
                $data .= "\t\t\t" . '[' . PHP_EOL;
                foreach ($label as $key => $value) {
                    $data .= "\t\t\t\t" . '"' . strtolower($key) . '" => "' . $value . '",' . PHP_EOL;
                }
                $data .= "\t\t\t" . '],' . PHP_EOL;
            }
            $data .= "\t\t" . '];';
            return $data;
        }
        return "\t\t" . '$data = [];' . PHP_EOL;
    }

    private function buildTableLabels(): string
    {
        if (isset($this->model_specs['table_labels']) && is_array($this->model_specs['table_labels']) && !empty($this->model_specs['table_labels'])) {
            $data = "\t\t" . '$labels = [' . PHP_EOL;
            foreach ($this->model_specs['table_labels'] as $label) {
                $data .= "\t\t\t" . '\'opt_mx_' . $label['label'] . '_id\' => [' . PHP_EOL;

                $color_column = $label['color'] == true ? ', txt_color' : '';
                $color_key = $label['color'] == true ? ', \'color\' => \'txt_color\'' : '';

                $data .= "\t\t\t\t" . '\'query\' => "SELECT id, ' . $label['value'] . $color_column . ' FROM mx_' . $label['label'] . '",' . PHP_EOL;
                $data .= "\t\t\t\t" . '\'key\' => "' . $label['key'] . '", \'value\' => "' . $label['value'] . '"' . $color_key . PHP_EOL;
                $data .= "\t\t\t" . '],' . PHP_EOL;
            }
            $data .= "\t\t" . '];';

            return $data;
        }
        return "\t\t" . '$labels = [];';
    }

    private function buildProfileButton(): string
    {
        if ($this->model_specs['profile_buttons'] != null && is_array($this->model_specs['profile_buttons'])) {
            $data = "\t\t" . '$data = [' . PHP_EOL;
            foreach ($this->model_specs['profile_buttons'] as $label) {
                $data .= "\t\t\t" . '[' . PHP_EOL;
                foreach ($label as $key => $value) {
                    $data .= "\t\t\t\t" . '"' . strtolower($key) . '" => "' . $value . '",' . PHP_EOL;
                }
                $data .= "\t\t\t" . '],' . PHP_EOL;
            }
            $data .= "\t\t" . '];';
            return $data;
        }
        return "\t\t" . '$data = [];' . PHP_EOL;
    }

    private function buildActions(): string
    {
        if ($this->model_specs['actions']) {
            $data = "\t\t\t" . '[' . PHP_EOL;
            $data .= "\t\t\t\t" . '"action" => "Edit_' . str_replace(' ', '_', $this->class_title) . '", ';
            $data .= '"name" => "Edit", "icon" => "fa-edit", "color" => "caf", "url" => "' . $this->class . '", ' . PHP_EOL;
            $data .= "\t\t\t\t" . '\'disabled\' => [' . PHP_EOL;
            $data .= $this->indexActionDisable('edit');
            $data .= "\t\t\t\t" . '],' . PHP_EOL;
            $data .= "\t\t\t" . '],' . PHP_EOL;

            $data .= "\t\t\t" . '[' . PHP_EOL;
            $data .= "\t\t\t\t" . '"action" => "Suspend_' . str_replace(' ', '_', $this->class_title) . '", ';
            $data .= '"name" => "Suspend", "icon" => "fa-lock", "color" => "orange", "url" => "' . $this->class . '", ' . PHP_EOL;
            $data .= "\t\t\t\t" . '\'disabled\' => [' . PHP_EOL;
            $data .= $this->indexActionDisable('suspend');
            $data .= "\t\t\t\t" . '],' . PHP_EOL;
            $data .= "\t\t\t" . '],' . PHP_EOL;

            $data .= "\t\t\t" . '[' . PHP_EOL;
            $data .= "\t\t\t\t" . '"action" => "Activate_' . str_replace(' ', '_', $this->class_title) . '", ';
            $data .= '"name" => "Activate", "icon" => "fa-unlock", "color" => "ccm", "url" => "' . $this->class . '", ' . PHP_EOL;
            $data .= "\t\t\t\t" . '\'disabled\' => [' . PHP_EOL;
            $data .= $this->indexActionDisable('activate');
            $data .= "\t\t\t\t" . '],' . PHP_EOL;
            $data .= "\t\t\t" . '],' . PHP_EOL;

            return $data;
        }
        return "";
    }

    private function buildTabs(): string
    {
        if ($this->model_specs['tabs'] != null && is_array($this->model_specs['tabs'])) {
            $data = "\t\t" . 'return [' . PHP_EOL;
            foreach ($this->model_specs['tabs'] as $tab) {
                $data .= "\t\t\t" . '\'' . $tab['name'] . '\', ';
            }
            $data .= PHP_EOL . "\t\t" . '];';
            return $data;
        }
        return "\t\t" . 'return [];';
    }

    private function buildProfileHiddenFields(): string
    {
        if (isset($this->model_specs['profile_hidden_fields']) && !empty($this->model_specs['profile_hidden_fields'])) {
            return "\t\t\t'" . implode("', '",$this->model_specs['profile_hidden_fields']) . '\'' . PHP_EOL;
        }
        return "\t\t\t" . '\'id\', \'row_id\', \'color\', \'Color\'' . PHP_EOL;
    }

    private function buildFormDropdowns(): string
    {
        if (isset($this->model_specs['form_dropdowns']) && is_array($this->model_specs['form_dropdowns'])) {
            $query = '';
            $data = '';
            foreach ($this->model_specs['form_dropdowns'] as $dropdown) {
                $query .= "\t\t" . '$' . $dropdown .'_query = $this->db->select("SELECT txt_row_value, txt_name FROM mx_' . $dropdown . ' ORDER BY id asc");' . PHP_EOL;

                $data .= "\t\t" . 'if ($' . $dropdown .'_query) {' . PHP_EOL;
                $data .= "\t\t\t" . 'foreach ($' . $dropdown .'_query as $key => $value) {' . PHP_EOL;
                $data .= "\t\t\t\t" . '$data[\'opt_mx_' . $dropdown .'_ids\'][] = [\'id\' => $value[\'txt_row_value\'], \'name\' => $value[\'txt_name\']];' . PHP_EOL;
                $data .= "\t\t\t" . '}' . PHP_EOL;
                $data .= "\t\t" . '}' . PHP_EOL;
            }
            return $query . PHP_EOL . $data;
        }
        return '';
    }

    private function buildFormHiddenFields(): string
    {
        if (isset($this->model_specs['form_hidden_fields']) && !empty($this->model_specs['form_hidden_fields'])) {
            return "\t\t\t'" . implode("', '",$this->model_specs['form_hidden_fields']) . '\'' . PHP_EOL;
        }
        return "\t\t\t" . '\'id\', \'opt_mx_status\', \'opt_mx_state\', \'opt_mx_txt_row_value\'' . PHP_EOL;
    }

    private function buildAssociatedSwitch(): string
    {
        $file = '';

        foreach ($this->model_specs['tabs'] as $tab) {
//            echo $tab['name'] . '<br>';
//            print_r($this->controller_specs['associated_records'][$tab['name']]);

            $file .= "\t\t\t" . 'case \'' . $tab['name'] . '\':' . PHP_EOL;
            $file .= "\t\t\t\t" . 'return [' . PHP_EOL;
            $file .= "\t\t\t\t\t" . '\'hiddens\' => [' . PHP_EOL;
            $file .= "\t\t\t\t\t\t'" . implode("', '", $this->controller_specs['associated_records'][$tab['name']]['hiddens']) . '\'' . PHP_EOL;
            $file .= "\t\t\t\t\t" . '],' . PHP_EOL;
            $file .= "\t\t\t\t\t" . '\'formatters\' => [' . PHP_EOL;
            $file .= "\t\t\t\t\t\t" . '\'Amount\' => [\'format\' => \'number\'/*, \'color\' => \'red\',*/],' . PHP_EOL;
            $file .= "\t\t\t\t\t\t" . '\'dat_added_date\',\'dat_start_date\',\'dat_end_date\' => [\'format\' => \'date\', \'type\' => \'dd/MM/yyyy@h:mm a\'],' . PHP_EOL;
            $file .= "\t\t\t\t\t\t" . '\'State\' => [\'format\' => \'label\', \'labels\' => getLabels(\'mx_status\',\'txt_name\' , \'txt_color\')],' . PHP_EOL;
            $file .= "\t\t\t\t\t\t" . '\'Accommodation\' => [' . PHP_EOL;
            $file .= "\t\t\t\t\t\t\t" . '\'format\' => \'custom_label\',' . PHP_EOL;
            $file .= "\t\t\t\t\t\t\t" . '\'labels\' => [' . PHP_EOL;
            $file .= "\t\t\t\t\t\t\t\t" . '\'1\' => [\'text\' => \'Is Studying\', \'color\' => \'green\',],' . PHP_EOL;
            $file .= "\t\t\t\t\t\t\t\t" . '\'0\' => [\'text\' => \'Not Studying\', \'color\' => \'red\',],' . PHP_EOL;
            $file .= "\t\t\t\t\t\t\t" . '],' . PHP_EOL;
            $file .= "\t\t\t\t\t\t" . '],' . PHP_EOL;
            $file .= "\t\t\t\t\t" . '],' . PHP_EOL;
            $file .= "\t\t\t\t" . '];' . PHP_EOL;
        }

        return $file;
    }

    private function buildAssociatedActions(): string
    {
        $file = '';

        foreach ($this->model_specs['tabs'] as $tab) {
//            echo $tab['name'] . '<br>';
//            print_r($this->controller_specs['associated_records'][$tab['name']]);

            $file .= "\t\t\t" . 'case \'' . strtolower($tab['name']) . '\':' . PHP_EOL;
            $file .= "\t\t\t\t" . '$data = [' . PHP_EOL;
            foreach ($this->controller_specs['associated_records'][$tab['name']]['actions'] as $actions) {
//                print_r($actions);
                $file .= "\t\t\t\t\t[" . PHP_EOL;
                foreach ($actions as $key => $value) {
                    $file .= "\t\t\t\t\t\t'" . $key . '\' => "' . $value . '",' . PHP_EOL;
                }
                $file .= "\t\t\t\t\t]," . PHP_EOL;
            }
//            $file .= "\t\t\t\t\t'" . $this->controller_specs['associated_records'][$tab['name']]['actions'] . PHP_EOL;
            $file .= "\t\t\t\t" . '];' . PHP_EOL;
            $file .= "\t\t\t\t" . 'break;' . PHP_EOL;
        }

        return $file;
    }

    private function indexActionDisable($action): string
    {
        $file = '';
        if (isset($this->controller_specs['actions']) && is_array($this->controller_specs['actions'])) {
            if ($this->controller_specs['actions'][$action]['type'] && in_array(strtoupper($this->controller_specs['actions'][$action]['type']), ['OR', 'AND'])) {
                $file .= "\t\t\t\t\t" . '\'' . strtoupper($this->controller_specs['actions'][$action]['type']) . '\' => [' . PHP_EOL;
                $file .= "\t\t\t\t\t\t" . '\'' . $this->controller_specs['actions'][$action]['field'] . '\' => [' . $this->controller_specs['actions'][$action]['values'] . ']' . PHP_EOL;
                $file .= "\t\t\t\t\t" . ']' . PHP_EOL;
            } else {
                $file .= "\t\t\t\t\t" . '\'' . $this->controller_specs['actions'][$action]['field'] . '\' => [' . $this->controller_specs['actions'][$action]['values'] . ']' . PHP_EOL;
            }
        }
        return $file;
    }
}