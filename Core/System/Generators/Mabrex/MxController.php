<?php

namespace Abc\System\Generators\Mabrex;

use Abc\Utility\Stringify;

class MxController
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

    public function getControllerContent(): string
    {
        $file = $this->getControllerConstruct() . PHP_EOL;
        $file .= $this->getControllerIndexMethod() . PHP_EOL;
        $file .= $this->getControllerFilters();
        $file .= $this->getControllerProfileMethod() . PHP_EOL;
        $file .= $this->getControllerAssociatedRecordsMethod() . PHP_EOL;
        $file .= $this->getControllerCreateMethod() . PHP_EOL;
        $file .= $this->getPostCEMethod() . PHP_EOL;

        if ($this->model_specs['actions']) {
            $file .= $this->getControllerEditMethod() . PHP_EOL;
            $file .= $this->getPostCEMethod(true) . PHP_EOL;
            $file .= $this->getControllerStateMethod() . PHP_EOL;
            $file .= $this->getControllerPostStateMethod() . PHP_EOL;
            $file .= $this->getControllerStateMethod(false) . PHP_EOL;
            $file .= $this->getControllerPostStateMethod(false) . PHP_EOL;
        }

        return $file;
    }

    private function getControllerConstruct(): string
    {
        $file = "\t" . 'private ' . $this->class . '_Model $model;' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\tpublic function __construct()" . PHP_EOL;
        $file .= "\t{" . PHP_EOL;
        $file .= "\t\t" . '$this->model = new ' . $this->class . '_Model();' . PHP_EOL;
        $file .= "\t\tparent::__construct();" . PHP_EOL;
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getControllerIndexMethod(): string
    {
        $file = "\tpublic function index() {" . PHP_EOL;
        $file .= "\t\t" . '$permission = Perm_Auth::getPermissions($_SESSION[\'id\']);' . PHP_EOL;
        $file .= "\t\t" . 'if ($permission->verifyPermission(\'view_' . $this->lowercase_class_plural . "')) {" . PHP_EOL;
        $file .= "\t\t\t" . '$data = $this->model->getAllRecords($this->model->getTable());' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . '$this->view->title = "All " . $this->model->getTitle();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->buttons = $this->model->getControls();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->class = getClassName(get_class($this->model));' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->table = $this->model->getTable();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->allRecords = $data[0];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->headings = $this->model->getClassFields($this->model->getTable())[\'properties\'];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->hidden = $this->model->getHiddenFields();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->actions = $this->model->getActions();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->table = $this->model->getTable();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->resultData = $data[1];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->postData = $data[2];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->colors = $this->model->getTableLabels();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->render(\'index\');' . PHP_EOL;
        $file .= $this->closeFunctionBrace();
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getControllerFilters(): string
    {
        if (isset($this->controller_specs['filters']) && $this->controller_specs['filters'] != []) {
            $file = '';
            foreach ($this->controller_specs['filters'] as $filter) {
                $file .= "\tpublic function " . $filter['function'] . "() {" . PHP_EOL;
                $file .= "\t\t" . '$permission = Perm_Auth::getPermissions($_SESSION[\'id\']);' . PHP_EOL;
                $file .= "\t\t" . 'if ($permission->verifyPermission(\'view_' . $this->lowercase_class_plural . "')) {" . PHP_EOL;
                $file .= "\t\t\t" . '$data = $this->model->getFilteredRecords($this->model->getTable(), [\'opt_mx_' . $filter['filter_key'] . '_id\'], [' . strtoupper($filter['filter_value']) . ']);' . PHP_EOL;
                $file .= PHP_EOL;
                $file .= "\t\t\t" . '$this->view->title = "All ' . ucfirst(strtolower($filter['function'])) . ' " . $this->model->getTitle();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->buttons = $this->model->getControls();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->class = getClassName(get_class($this->model));' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->table = $this->model->getTable();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->allRecords = $data[0];' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->headings = $this->model->getClassFields($this->model->getTable())[\'properties\'];' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->hidden = $this->model->getHiddenFields();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->actions = $this->model->getActions();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->table = $this->model->getTable();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->resultData = $data[1];' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->postData = $data[2];' . PHP_EOL;
                $file .= "\t\t\t" . '$this->view->colors = $this->model->getTableLabels();' . PHP_EOL;
                $file .= "\t\t\t" . '$this->render(\'index\');' . PHP_EOL;
                $file .= $this->closeFunctionBrace();
                $file .= "\t}" . PHP_EOL . PHP_EOL;
            }

            return $file;
        }

        return '';

    }

    private function getControllerProfileMethod(): string
    {
        $file = "\t" . 'public function profile($id) {' . PHP_EOL;
        $file .= "\t\t" . '$record_id = filter_var($id, FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$user_id = filter_var($_SESSION[\'id\'], FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$permission = Perm_Auth::getPermissions($user_id);' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'if ($permission->verifyPermission(\'view_' . $this->lowercase_class_plural . "')) {" . PHP_EOL;
        $file .= "\t\t\t" . '$returned_id = $this->model->getRecordIdByRowValue($this->model->getTable(), $record_id);' . PHP_EOL;
        $file .= "\t\t\t" . 'if ($returned_id > -1) {' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t\t" . '$'. $this->lowercase_class . ' = $this->model->getRecord($returned_id, $this->model->getTable());' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t\t" . '$profile_data = $this->model->getProfileData($returned_id, $this->model->getTable());' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->primary_color = filter_input(INPUT_COOKIE, \'primary\', FILTER_SANITIZE_STRING, ["options" => ["default" => "#000000"]]);' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->secondary_color = filter_input(INPUT_COOKIE, \'secondary\', FILTER_SANITIZE_STRING, ["options" => ["default" => "#FF0000"]]);' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->title = $this->model->getTitle() . \' Profile\';' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->data = array_merge($profile_data);' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->tabs = $this->model->getTabs($returned_id);' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->hidden_columns = $this->model->getProfileHiddenColumns();' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->account_details = [];' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->extras = [];' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->buttons = $this->model->getProfileButtons($returned_id);' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->render(\'profile/profile\');' . PHP_EOL;
        $file .= $this->closeIfElseBrace();
        $file .= $this->closeFunctionBrace();
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getControllerAssociatedRecordsMethod(): string
    {
        $file = '';

        if (isset($this->controller_specs['associated_records']) && is_array($this->controller_specs['associated_records'])) {
            $file .= "\t" . 'public function associated_records($id, $caller) {' . PHP_EOL;
            $file .= "\t\t" . '$record_id = filter_var($id, FILTER_SANITIZE_STRING);' . PHP_EOL;
            $file .= "\t\t" . '$valid_caller = filter_var($caller, FILTER_SANITIZE_STRING);' . PHP_EOL;
            $file .= "\t\t" . '$table = \'mx_\' . rtrim(strtolower($valid_caller), \'s\');' . PHP_EOL;
            $file .= PHP_EOL;
            $file .= "\t\t" . '$call_mappers = [' . PHP_EOL;
            $file .= $this->getCallMappers();
            $file .= "\t\t" . '];' . PHP_EOL;
            $file .= PHP_EOL;
            $file .= "\t\t" . 'if (array_key_exists($valid_caller, $call_mappers)) {' . PHP_EOL;
            $file .= "\t\t\t" . '$table = $call_mappers[$valid_caller];' . PHP_EOL;
            $file .= "\t\t" . '}' . PHP_EOL;
            $file .= PHP_EOL;
            $file .= "\t\t" . '$user_id = filter_var($_SESSION[\'id\'], FILTER_SANITIZE_STRING);' . PHP_EOL;
            $file .= "\t\t" . '$permission = Perm_Auth::getPermissions($user_id);' . PHP_EOL;
            $file .= PHP_EOL;
            $file .= "\t\t" . 'if ($permission->verifyPermission(\'view_' . $this->lowercase_class_plural . '\')) {' . PHP_EOL;
            $file .= "\t\t\t" . '$returned_id = $this->model->getRecordIdByRowValue($this->model->getTable(), $record_id);' . PHP_EOL;
            $file .= "\t\t\t" . 'if ($returned_id > -1) {' . PHP_EOL;
            $file .= "\t\t\t\t" . '$associated_record_details = $this->model->getAssociatedRecordDetails($valid_caller);' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->hiddens = $associated_record_details[\'hiddens\'] ?? [];' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->labels = $associated_record_details[\'labels\'] ?? [];' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->formatters = $associated_record_details[\'formatters\'] ?? [];' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->data = $this->model->getAssociatedRecords($returned_id, $table, $this->model->getParentKey());' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->table_headers = $this->model->getTableColumns($table . \'_view\');' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->caller = str_replace("_", " ", filter_var($valid_caller, FILTER_SANITIZE_STRING));' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->actions = $this->model->getAssociatedRecordActions($valid_caller);' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->show_cards = false;' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->render(\'associated_records/main\');' . PHP_EOL;
            $file .= "\t\t\t" . '} else {' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->view->subtitle = "' . $this->class_title . ' information not found";' . PHP_EOL;
            $file .= "\t\t\t\t" . '$this->renderFull(\'views/templates/not_found\');' . PHP_EOL;
            $file .= "\t\t\t" . '}' . PHP_EOL;
            $file .= "\t\t" . '} else {' . PHP_EOL;
            $file .= "\t\t\t" . '$this->_permissionDenied(__METHOD__);' . PHP_EOL;
            $file .= "\t\t" . '}' . PHP_EOL;
            $file .= "\t" . '}' . PHP_EOL;
        }

        return $file;
    }

    private function getControllerCreateMethod(): string
    {
        $file = "\t" . 'public function create() {' . PHP_EOL;
        $file .= "\t\t" . '$user_id = filter_var($_SESSION[\'id\'], FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$perm = Perm_Auth::getPermissions($user_id);' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'if ($perm->verifyPermission(\'add_' . $this->lowercase_class . "')) {" . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->class = getClassName(get_class($this->model));' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->title = \'New \' . $this->model->getTitle();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->data = [\'has_extra\' => 0];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->dropdowns = $this->model->getFormDropdowns();' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->form_title = "Fill the form to add a new ' . $this->view_name_title_lc . '";' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->disabled = [];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->render(\'create\');' . PHP_EOL;
        $file .= $this->closeFunctionBrace();
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getPostCEMethod($edit = false): string
    {
        if ($edit) {
            $function_name = 'post_edit';
            $action_class = 'Update';
        } else {

            $function_name = 'save';
            $action_class = 'Add';
        }

        $file = "\t" . 'public function ' . $function_name . '() {' . PHP_EOL;
        $file .= "\t\t" . '$posted_data = json_decode(file_get_contents("php://input"), true);' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . '$this->model->db->beginTransaction();' . PHP_EOL;
        $file .= "\t\t" . 'try {' . PHP_EOL;
        $file .= "\t\t\t" . '$result = (new ' . $action_class . '' . $this->class . '($posted_data, $this->model))->init();' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . 'if ($result[\'status\']) {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->model->db->commit();' . PHP_EOL;
        $file .= "\t\t\t" . '} else {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->model->db->rollBack();' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t\t" . 'response($result);' . PHP_EOL;
        $file .= "\t\t" . '} catch (Exception $exception) {' . PHP_EOL;
        $file .= "\t\t\t" . 'Log::sysErr($exception->getMessage());' . PHP_EOL;
        $file .= "\t\t\t" . 'response ([\'status\' => false, \'code\' => 100, \'message\' => \'Something went wrong with the operation\']);' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getControllerEditMethod(): string
    {
        $file = "\t" . 'public function edit($id) {' . PHP_EOL;
        $file .= "\t\t" . '$user_id = filter_var($_SESSION[\'id\'], FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$posted_id = filter_var($id, FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$permission = Perm_Auth::getPermissions($user_id);' . PHP_EOL;
        $file .= "\t\t" . 'if ($permission->verifyPermission(\'edit_' . $this->lowercase_class . '\')) {' . PHP_EOL;
        $file .= "\t\t\t" . '$returned_id = $this->model->getRecordIdByRowValue($this->model->getTable(), $posted_id);' . PHP_EOL;
        $file .= "\t\t\t" . 'if ($returned_id > -1) {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$data = $this->model->getRecord($returned_id, $this->model->getTable());' . PHP_EOL;
        $file .= "\t\t\t\t" . '$view_data = [' . PHP_EOL;
        $file .= $this->getEditFormColumns();
        $file .= "\t\t\t\t" . '];' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->title = \'Update \' . $this->model->getTitle();' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->data = $view_data;' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->dropdowns = $this->model->getFormDropdowns();' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->render(\'edit\');' . PHP_EOL;
        $file .= "\t\t\t" . '} else {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->subtitle = \'' . $this->class_title . ' Editing Error\';' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->renderFull(\'views/templates/not_found\');' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= $this->closeFunctionBrace();
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getEditFormColumns(): string
    {
        $file = "\t\t\t\t\t" . '\'id\' => $posted_id,' . PHP_EOL;
        if (isset($this->model_specs['form_fields']) && $this->model_specs['form_fields'] != []) {
            foreach ($this->model_specs['form_fields'] as $form_field) {
                $file .= "\t\t\t\t\t" . '\'' . $form_field . '\' => $data[\'' . $form_field . '\'],' . PHP_EOL;
            }
        } else {
            $file .= "\t\t\t\t\t" . '\'txt_name\' => $data[\'txt_name\'],' . PHP_EOL;
            $file .= "\t\t\t\t\t" . '\'txt_abbreviation\' => $data[\'txt_abbreviation\'],' . PHP_EOL;
        }

        return $file;
    }

    private function getControllerStateMethod($suspend = true): string
    {
        if ($suspend) {
            $state = 'suspend';
            $title = 'Suspend';
            $action = 'Suspension';
        } else {
            $state = 'activate';
            $title = 'Activate';
            $action = 'Activation';
        }

        $file = "\t" . 'public function ' . $state . '($id) {' . PHP_EOL;
        $file .= "\t\t" . '$user_id = filter_var($_SESSION[\'id\'], FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$posted_id = filter_var($id, FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t" . '$permission = Perm_Auth::getPermissions($user_id);' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'if ($permission->verifyPermission(\'' . $state . '_' . $this->lowercase_class . '\')) {' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->title = \'' . $title . ' ' . $this->class_title . '\';' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->subtitle = \'' . $this->class_title . ' ' . $action . '\';' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->controller = \'' . $this->class . '\';' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->action = \'post_' . $state . '\';' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->name = \'\';' . PHP_EOL;
        $file .= "\t\t\t" . '$this->view->data = [\'id\' => $posted_id];' . PHP_EOL;
        $file .= "\t\t\t" . '$this->renderFull(\'views/templates/' . $state . '\');' . PHP_EOL;
        $file .= $this->closeFunctionBrace();
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getControllerPostStateMethod($suspend = true): string
    {
        if ($suspend) {
            $method = 'suspend';
            $action = 'suspended';
            $state = $this->controller_specs['state_data']['activate_constant'];
        } else {
            $method = 'activate';
            $action = 'activated';
            $state = $this->controller_specs['state_data']['suspend_constant'];;
        }

        $file = "\t" . 'public function post_' . $method . '($id) {' . PHP_EOL;
        $file .= "\t\t" . '$posted_data = json_decode(file_get_contents("php://input"), true);' . PHP_EOL;
        $file .= "\t\t" . 'try {' . PHP_EOL;
        $file .= "\t\t\t" . '$posted_id = filter_var($posted_data[\'id\'], FILTER_SANITIZE_STRING);' . PHP_EOL;
        $file .= "\t\t\t" . '$operator_id = $this->model->getRecordIdByRowValue($this->model->getTable(), $posted_id);' . PHP_EOL;
        $file .= "\t\t\t" . '$this->model->update([\'opt_mx_' . $this->controller_specs['state_data']['field'] . '_id\' => filter_var(' . $state . ', FILTER_VALIDATE_INT)], $this->model->getTable(), $operator_id);' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . 'response([\'status\' => false, \'code\' => 201, \'message\' => \'' . $this->class . ' ' . $action . ' successfully\']);' . PHP_EOL;
        $file .= "\t\t" . '} catch (Exception $ex) {' . PHP_EOL;
        $file .= "\t\t\t" . 'response([\'status\' => false, \'code\' => 100, \'message\' => \'An Error Occurred, Failed to ' . $method . ' ' . $this->class . '\']);' . PHP_EOL;
        $file .= "\t\t\t" . 'echo $ex->getMessage();' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t}" . PHP_EOL;

        return $file;
    }

    private function getCallMappers(): string
    {
        $file = '';

        if (isset($this->model_specs['tabs']) && is_array($this->model_specs['tabs']) && !empty($this->model_specs['tabs'])) {
            foreach ($this->model_specs['tabs'] as $tab) {
                $file .= "\t\t\t" . '\'' . $tab['name'] . '\' => \'mx_' . $tab['table'] . '\',' . PHP_EOL;
            }
        }

        return $file;
    }

    private function closeFunctionBrace(bool $with_error = true): string
    {
        if ($with_error == false) {
            return "\t\t}" . '}';
        }

        $file = "\t\t} else {" . PHP_EOL;
        $file .= "\t\t\t" . '$this->_permissionDenied(__METHOD__);' . PHP_EOL;
        $file .= "\t\t}" . PHP_EOL;

        return $file;
    }

    private function closeIfElseBrace(): string
    {
        $file = "\t\t\t} else {" . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->view->subtitle = "' . $this->class . ' not found";' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->renderFull(\'views/templates/not_found\');' . PHP_EOL;
        $file .= "\t\t\t}" . PHP_EOL;

        return $file;
    }
}