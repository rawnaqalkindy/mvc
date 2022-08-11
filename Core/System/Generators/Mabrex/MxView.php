<?php

namespace Abc\System\Generators\Mabrex;

use Abc\Utility\Stringify;

class MxView
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
    protected const MX_PATH = STORAGE_PATH . '/mx/';

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

    public function getViewContent(): array
    {
        return [
            'view_name' => $this->view_name,
            'index_content' => $this->getIndex(),
            'main_profile_content' => $this->getProfile()['main'],
            'buttons_profile_content' => $this->getProfile()['buttons'],
            'profile_content' => $this->getProfile()['profile'],
            'create_content' => $this->getViewActions(),
            'edit_content' => $this->getViewActions(false),
            'create_form_content' => $this->getForm(),
            'edit_form_content' => $this->getForm(),
            'main_associated_record_content' => $this->getMainAssociatedContent(),
            'default_associated_record_content' => $this->getDefaultAssociatedContent(),
            'filepath' => self::MX_PATH . $this->class . '/Views',
        ];
    }

    private function getIndex(): string
    {
        $file = '<div id="page-content">';
        $file .= "\t" . '<span id="progress" style="visibility:hidden;">' . PHP_EOL;
        $file .= "\t\t" . '<div class="lds-ripple">' . PHP_EOL;
        $file .= "\t\t\t" . '<div></div>' . PHP_EOL;
        $file .= "\t\t\t" . '<div></div>' . PHP_EOL;
        $file .= "\t\t" . '</div>' . PHP_EOL;
        $file .= "\t" . '</span>' . PHP_EOL;

        $file .= PHP_EOL;

        $file .= "\t" . '<?php' . PHP_EOL;
        $file .= "\t\t" . 'use Libs\DataView;' . PHP_EOL;
        $file .= "\t\t" . 'use Libs\Perm_Auth;' . PHP_EOL;
        $file .= "\t\t" . 'use Libs\Session;' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . '$perm = Perm_Auth::getPermissions($_SESSION[\'id\']);' . PHP_EOL;
        $file .= "\t\t" . '$returned = Session::get(\'returned\') != null || Session::get(\'returned\') != \'\' ? Session::get(\'returned\') : 0;' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'echo \'<div ng-controller="formController" class="btn-group btn-group-sm " ng-init="\'' . PHP_EOL;
        $file .= "\t\t\t" . '. \'buttons=\' . sizeof($this->buttons) . \'; return_value=\' . $returned . \'" ng-show="buttons > 0" \'' . PHP_EOL;
        $file .= "\t\t\t" . '. \'ng-model="buttons" style="margin-bottom:10px !important;">\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'foreach ($this->buttons as $button) {' . PHP_EOL;
        $file .= "\t\t\t" . 'if ($perm->verifyPermission(strtolower($button[\'permission\']))) { //check permission' . PHP_EOL;
        $file .= "\t\t\t\t" . '$action = "\'" . $button[\'action\'] . "\'";' . PHP_EOL;
        $file .= "\t\t\t\t" . 'echo \'<button ng-click="showForm(\' . $button[\'url\'] . \', \' . $action . \')" class= "btn btn-\' . $button[\'color\']' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '. \'" data-name="\' . $button[\'name\']' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '. \'" data-action="\' . $button[\'action\']' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '. \'"  data-title= "\' . $button[\'title\']' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '. \'" data-mabrex-dialog="\' . $button[\'url\'] . \'">\';' . PHP_EOL;
        $file .= "\t\t\t\t" . 'echo trans($button[\'title\']) . \'</button>\';' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . 'echo \'</div>\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . '$actions = [];' . PHP_EOL;
        $file .= "\t\t" . 'if (sizeof($this->actions)) {//Checks permissions for action buttons' . PHP_EOL;
        $file .= "\t\t\t" . 'foreach ($this->actions as $action) {' . PHP_EOL;
        $file .= "\t\t\t\t" . 'if ($perm->verifyPermission(strtolower($action[\'action\']))) {' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '$actions[] = $action;' . PHP_EOL;
        $file .= "\t\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;

        $file .= PHP_EOL;

        $file .= "\t\t" . '/** table display */' . PHP_EOL;
        $file .= "\t\t" . 'echo \'<div class="panel panel-default" ng-controller="profileController" ng-init="return_value=\' . $returned . \'">\';' . PHP_EOL;
        $file .= "\t\t" . 'echo \'<div class="panel-heading"><h4 class="panel-title">\';' . PHP_EOL;
        $file .= "\t\t" . 'echo trans($this->title) . \'</h4></div>\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'echo \'<div class="panel-body">\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'if ($this->resultData[\'recordsFiltered\'] > 0) {' . PHP_EOL;
        $file .= "\t\t\t" . '// Add mabrex filter' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<mabrex-filter mx-selected="\' . $this->postData[\'length\'] . \'" mx-location="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'location\'] . \'\\\'" mx-title="\\\'' . $this->class_title . ' List\\\'" mx-current-link="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'current\'] . \'\\\'" mx-page-size="\\\'\' . $this->postData[\'length\'] . \'\\\'" mx-search-term="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'search\'] . \'\\\'" mx-total-records="\' . $this->resultData[\'recordsTotal\'] . \'" mx-table-columns="\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->resultData[\'columns\'] . \'" mx-sort-column="\\\'\' . $this->postData[\'order_column\'] . \'\\\'" mx-sort-order="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'order_dir\'] . \'\\\'" mx-column-label="\\\'\' . $this->resultData[\'column_label\'] . \'\\\'"></mabrex-filter>\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . '$view = new DataView();' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<div class="table-responsive" id="data-view">\';' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<table class="table table-striped table-hover table-condensed">\';' . PHP_EOL;
        $file .= "\t\t\t" . 'echo $view->displayTHead($this->headings, $this->hidden, (sizeof($actions) ? HAS_ACTION : NO_ACTION));' . PHP_EOL;
        $file .= "\t\t\t" . 'echo $view->displayTBody($this->allRecords, $this->class, $this->table, $this->hidden, $actions, LBL_BIG, $this->colors);' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'</table>\';' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'</div>\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . '// Add mabrex pager' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<mabrex-pager mx-filtered="\' . $this->resultData[\'recordsFiltered\'] . \'" mx-total="\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->resultData[\'recordsTotal\'] . \'" mx-current-page="\' . $this->resultData[\'currentPage\'] . \'" mx-pages="\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->resultData[\'totalPages\'] . \'" mx-page-buttons="10" mx-page-location="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'location\'] . \'\\\'" mx-page-title="\\\'' . $this->class_title . ' List\\\'" mx-page-current-link="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'current\'] . \'\\\'" mx-page-size="\\\'\' . $this->postData[\'length\'] . \'\\\'" mx-page-search-term="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'search\'] . \'\\\'" mx-returned="\' . $this->resultData[\'recordsReturned\'] . \'" mx-sort-column="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'order_column\'] . \'\\\'" mx-sort-order="\\\'\' . $this->postData[\'order_dir\'] . \'\\\'"></mabrex-pager>\';' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'</div>\';' . PHP_EOL;
        $file .= "\t\t" . '} else {' . PHP_EOL;
        $file .= "\t\t\t" . '// Add mabrex filter' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<mabrex-filter mx-selected="\' . $this->postData[\'length\'] . \'" mx-location="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'location\'] . \'\\\'" mx-title="\\\'' . $this->class_title . ' List\\\'" mx-current-link="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'current\'] . \'\\\'" mx-page-size="\\\'\' . $this->postData[\'length\'] . \'\\\'" mx-search-term="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'search\'] . \'\\\'" mx-total-records="\' . $this->resultData[\'recordsTotal\'] . \'" mx-table-columns="\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->resultData[\'columns\'] . \'" mx-sort-column="\\\'\' . $this->postData[\'order_column\'] . \'\\\'" mx-sort-order="\\\'\' .' . PHP_EOL;
        $file .= "\t\t\t\t" . '$this->postData[\'order_dir\'] . \'\\\'" mx-column-label="\\\'\' . $this->resultData[\'column_label\'] . \'\\\'"></mabrex-filter>\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<div class="table-responsive" id="data-view">\';' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'<div><h3><i class="pe pe-7s-info pe-fw pe-va pe-3x"></i> Sorry, Record not available !!!</h3></div>\';' . PHP_EOL;
        $file .= "\t\t\t" . 'echo \'</div>\';' . PHP_EOL;
        $file .= "\t\t" . '}' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t\t" . 'echo \'</div>\';' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "\t" . '?>' . PHP_EOL;
        $file .= '</div>' . PHP_EOL;

        return $file;
    }

    private function getProfile(): array
    {
        $profile = [];
        $profile['main'] = $this->getViewProfileMain('new');
        $profile['buttons'] = $this->getViewProfileButtons();
        $profile['profile'] = $this->getViewProfile();

        return $profile;
    }

    private function getViewProfile(): string
    {
        $file = '<div id="page-content">' . PHP_EOL;
        $file .= "\t" . '<div id="data_content"' . PHP_EOL;
        $file .= "\t\t" . 'data-initial="<?php echo htmlspecialchars(json_encode($this->data, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\') ?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-tabs="<?php echo htmlspecialchars(json_encode($this->tabs, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\') ?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-hidden-columns="<?php echo htmlspecialchars(json_encode($this->hidden_columns, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\') ?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-extras="<?php echo htmlspecialchars(json_encode($this->extras, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\') ?>"' . PHP_EOL;
        $file .= "\t" . '>' . PHP_EOL;
        $file .= "\t" . '</div>' . PHP_EOL;
        $file .= "\t" . '<div id="display_content">' . PHP_EOL;
        $file .= "\t\t" . '<div ng-controller="profileController">' . PHP_EOL;
        $file .= "\t\t\t" . '<div class="modal-header" style="background-color:#000000; color: white;">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<button ng-click="cancel()" type="button" class="close" data-dismiss="modal" style="color: white; font-size: 35px;">&times;</button>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<h4 class="modal-title ocean text-capitalize"><i class="pe pe-7s-id pe-fw pe-va pe-2x"></i> <?php echo $this->title ?></h4>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '<div class="modal-body">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<ul class="nav nav-tabs">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<li class="active">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '<a data-toggle="tab" href="#' . $this->class . '" ng-click="getProfileRecords(\'' . $this->class . '\', \'<?php echo $this->data["row_id"] ?>\')">' . $this->class_title . '</a>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '</li>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<li ng-repeat="tab in tabs">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '<a data-toggle="tab" href="#{{tab}}" ng-click="getAssociatedRecords(tab, initial_tab_data.row_id)">{{tab.replace(\'_\', \' \')}}</a>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '</li>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</ul>' . PHP_EOL;
        $file .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<div class="tab-content">' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div class="row" style="margin-bottom: 15px;"> </div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div class="row" style="margin-bottom: 15px;">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<?php include \'buttons.php\' ?>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div class="tab-pane fade active in" id="' . $this->class . '">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<div class="row profile_section">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '<?php include \'main.php\' ?>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div  ng-repeat="tab in tabs" class="tab-pane fade" id="{{tab}}">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<div class="associated_section">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '<!-- ASSOCIATED RECORDS TO BE LOADED HERE -->' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '<div class="modal-footer">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<button class="btn btn-warning" ng-click="cancel()">Cancel</button>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t" . '</div>' . PHP_EOL;
        $file .= "\t" . '</div>' . PHP_EOL;
        $file .= "" . '</div>' . PHP_EOL;

        return $file;
    }

    private function getViewProfileMain($ui = 'default'): string
    {
        switch ($ui) {
            case 'new':
                return $this->newMain();
            case 'complex':
                return $this->complexMain();
            case 'default':
            default:
                return $this->defaultMain();
        }
    }

    private function complexMain(): string
    {
        $ui = '<div class="panel panel-default" style="background-color: whitesmoke">';
        $ui .= "\t" . '';
        $ui .= "\t\t" . '';
        $ui .= "\t\t" . '';
        $ui .= "\t\t\t" . '';
        $ui .= "\t\t\t\t" . '';
        $ui .= "\t\t\t\t\t" . '';
        $ui .= "\t\t\t\t\t\t" . '';
        $ui .= "\t\t\t\t\t\t\t" . '';
        $ui .= "\t\t\t\t\t\t\t\t" . '';
        return $ui;
    }

    private function newMain(bool $splitContent = false): string
    {
        $ui = '<div class="panel panel-default" style="background-color: whitesmoke">' . PHP_EOL;
        $ui .= "\t" . '<div class="panel-body">' . PHP_EOL;
        $ui .= "\t\t" . '<h5 class="text-center" style="font-size: 2.0em; color: white; font-weight: 600; margin-top: 10px; padding: 10px; border-radius: 4px; background-color: {{ initial_tab_data.color }}">' . PHP_EOL;
        $ui .= "\t\t\t" . '{{ initial_tab_data[\'' . ucfirst(strtolower($this->controller_specs['state_data']['field'])) . '\'] }}' . PHP_EOL;
        $ui .= "\t\t" . '</h5>' . PHP_EOL;
        $ui .= "\t\t" . '<div class="row" style="margin-top: 20px;">' . PHP_EOL;
        $ui .= "\t\t\t" . '<div class="col-md-12 col-lg-12">' . PHP_EOL;
        $ui .= "\t\t\t\t" . '<div class="panel panel-default">' . PHP_EOL;
        $ui .= "\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '<h5 class="text-center" style="font-size: 1.5em; color: green; text-transform: uppercase; font-weight: 600; opacity: 0.6;">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . ' ' . $this->class_title . ' Details' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '</h5><hr />' . PHP_EOL;
        $ui .= PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '<div class="panel panel-default">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<h5 class="text-left" style="font-size: 1.2em; text-transform: uppercase; opacity: 0.7"> <i class="fa fa-id-badge" style="font-size: 1.3em; opacity: 0.6"></i> ' . $this->class_title . ' Information</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<hr />' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<div ng-repeat="(key, value) in initial_tab_data" ng-if="hidden_columns.indexOf(key) < 0 && key !== \'row_id\'">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.35em; float: left; opacity: 0.6;">{{ key }}</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.4em; color: green; font-weight: 600; float: right;">{{ value }}</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '<hr style="margin: 0; opacity: 0.6;"/>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        if ($splitContent) {
            $ui .= $this->splitWithPanels();
        }

        $ui .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= PHP_EOL;
        $ui .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t" . '</div>' . PHP_EOL;
        $ui .= '</div>' . PHP_EOL;

        return $ui;
    }

    private function defaultMain(): string
    {
        $ui = '<div class="panel rounded" style="background-color: whitesmoke;">' . PHP_EOL;
        $ui .= "\t" . '<div class="panel-body">' . PHP_EOL;
        $ui .= "\t\t" . '<h5 class="text-center" style="font-size: 2.0em; color: white; font-weight: 600; margin-top: 10px; padding: 10px; border-radius: 4px; background-color: {{ initial_tab_data.color}}">' . PHP_EOL;
        $ui .= "\t\t\t" . '{{ initial_tab_data[\'Status\']}}' . PHP_EOL;
        $ui .= "\t\t" . '</h5>' . PHP_EOL;
        $ui .= "\t\t" . '<div class="row" style="margin-top: 20px">' . PHP_EOL;
        $ui .= "\t\t\t" . '<div class="col-md-12 col-lg-12">' . PHP_EOL;
        $ui .= "\t\t\t\t" . '<div class="panel">' . PHP_EOL;
        $ui .= "\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '<h5 class="" style="text-align: center; font-size: 1.7em; font-weight: 500;">' . strtoupper($this->class_title) . ' DETAILS</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '<hr/>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '<div ng-repeat="(key, value) in initial_tab_data" ng-if="hidden_columns.indexOf(key) < 0 && key !== \'row_id\'">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.35em; float: left;">{{key}}</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.4em; font-weight: 600; float: right;">{{value}}</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . '<hr style="margin: 0; opacity: 0.6;"/>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t" . '</div>' . PHP_EOL;
        $ui .= '</div>' . PHP_EOL;

        return $ui;
    }

    private function splitWithPanels(): string
    {
        $ui = "\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '<div class="panel panel-default">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<h5 class="text-left" style="font-size: 1.2em; text-transform: uppercase; opacity: 0.7"> <i class="fa fa-id-badge" style="font-size: 1.3em; opacity: 0.6"></i> ' . $this->class_title . ' Information</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<hr />' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-12 col-lg-12">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="panel panel-primary">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-10 col-lg-10">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.2em; text-transform: uppercase; color: gray;">Really Long Title</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-2 col-lg-2 text-right">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.5em; text-transform: uppercase;"> TZS ' . number_format(20000000) . '</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-6 col-lg-6">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-12 col-lg-12">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="panel panel-primary">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-10 col-lg-10">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.2em; text-transform: uppercase; color: gray;">Really Long Title</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-2 col-lg-2 text-right">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.5em; text-transform: uppercase;"> TZS ' . number_format(20000000) . '</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-12 col-lg-12">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="panel panel-primary">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="panel-body">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="row">' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-10 col-lg-10">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.2em; text-transform: uppercase; color: gray;">Really Long Title</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<div class="col-md-2 col-lg-2 text-right">' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '<h5 style="font-size: 1.5em; text-transform: uppercase;"> TZS ' . number_format(20000000) . '</h5>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $ui .= "\t\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $ui .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        return $ui;
    }

    private function getViewProfileButtons(): string
    {
        $file = '<?php if (count($this->buttons) > 0) { ?>' . PHP_EOL;
        $file .= "\t" . '<div class="text-center">' . PHP_EOL;
        $file .= "\t\t" . '<div class="profile-buttons-group">' . PHP_EOL;
        $file .= "\t\t\t" . '<?php' . PHP_EOL;
        $file .= "\t\t\t" . 'foreach ($this->buttons as $button) {' . PHP_EOL;
        $file .= "\t\t\t\t" . '$params = \'\';' . PHP_EOL;
        $file .= "\t\t\t\t" . 'echo \'<button ng-disabled="\' . $button[\'disabled\'] . \'" class="\' . $button[\'cssclass\'] . \'" ng-click="\'.$button[\'function\'].\'(\' . $button[\'controller\'] . \',\\\'\' . $button[\'action\'] . \'\\\',[\' . $button[\'params\'] . \'])" ng-show="\' . $button[\'show\'] . \'">\' . $button[\'label\'] . \'</button>\';' . PHP_EOL;
        $file .= "\t\t\t" . '}' . PHP_EOL;
        $file .= "\t\t\t" . '?>' . PHP_EOL;
        $file .= "\t\t" . '</div>' . PHP_EOL;
        $file .= "\t" . '</div>' . PHP_EOL;
        $file .= '<?php } ?>';

        return $file;
    }

    private function getViewActions($create = true, $profile_button = null): string
    {
        if ($create) {
            $save_location = "\t\t" . '<form name="' . $this->view_name . '" ng-submit="saveForm()" novalidate>' . PHP_EOL;
            $form_title = "\t\t\t\t\t\t\t" . '<h5 class="text-center" style="font-size: 1.7em;">Enter the new ' . $this->lowercase_class_title . '</h5>' . PHP_EOL;
            $form_file = "\t\t\t\t\t\t" . 'include \'forms/' . $this->lowercase_class . '.html\';' . PHP_EOL;
        } else {
            if ($profile_button && !empty($profile_button)) {
                $profile_action = $profile_button['action'];
                $profile_module = $profile_button['module'];
/*                $controller = '<?php echo $this->controller?>';*/
/*                $handler = '<?php echo $this->action?>';*/
                $profile_button_file = $profile_action . '_' . $profile_module;
                $profile_button_title = $this->lowercase_class_title;
                $form_title = ucfirst(strtolower($profile_action)) . ' the ' . $profile_button_title;
            } else {
                $profile_button_title = $this->lowercase_class_title;
                $profile_button_file = 'edit_' . $this->lowercase_class;
//                $handler = 'post_edit';
//                $form_title = 'Fill in the form to edit the ' . $profile_button_title;
                $form_title = 'Edit the ' . $profile_button_title;
            }

            $save_location = "\t\t" . '<form name="' . $this->view_name . '" ng-submit="saveProfileOperation(\'<?php echo $this->controller?>\', \'<?php echo $this->action?>\')" novalidate>' . PHP_EOL;
            $form_title = "\t\t\t\t\t\t\t" . '<h5 class="text-center" style="font-size: 1.7em;">' . $form_title . '</h5>' . PHP_EOL;
            $form_file = "\t\t\t\t\t\t" . 'include \'forms/' . $profile_button_file . '.html\';' . PHP_EOL;
        }

        $file = '<div id="page-content">' . PHP_EOL;
        $file .= "\t" . '<div id="data_content" ' . PHP_EOL;
        $file .= "\t\t" . 'data-form="<?php echo htmlspecialchars(json_encode($this->data, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\') ?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-dropdowns="<?php echo htmlspecialchars(json_encode($this->dropdowns, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\') ?>">' . PHP_EOL;
        $file .= "\t" . '</div>' . PHP_EOL;
        $file .= "\t" . '<div id="display_content">' . PHP_EOL;
        $file .= $save_location;
        $file .= "\t\t\t" . '<div class="modal-header" style="background-color: #39A635; color: white;">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<button type="button" ng-click="cancel()" class="close" data-dismiss="modal" style="color: white; font-size: 35px;">&times;</button>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<h4 class="modal-title ocean"><i class="pe pe-7s-culture pe-fw pe-va pe-2x"></i><?php echo $this->title ?></h4>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '<div class="modal-body">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<div class="notification-area"></div>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<div class="form-horizontal">' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div class="row" style="margin-top: 5px; margin-bottom: 10px;">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<div class="col-md-12 col-lg-12 col-xl-12">' . PHP_EOL;
        $file .= $form_title;
        $file .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<?php' . PHP_EOL;
        $file .= $form_file;
        $file .= "\t\t\t\t\t" . '?>' . PHP_EOL;
        $file .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '<div class="modal-footer">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<span ng-if="ProcessingData === true"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i> Processing your request, please wait... &nbsp; &nbsp;</span>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<button type="submit" ng-disabled="' . $this->lowercase_class . '.$invalid || ProcessingData === true" class="btn btn-info" name="submit"> Submit </button>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<button ng-disabled="ProcessingData === true" ng-click="cancel() "type="button" class="btn btn-danger" data-dismiss="modal">Close</button>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t" . '</form>' . PHP_EOL;
        $file .= "\t" . '</div>' . PHP_EOL;
        $file .= '</div>';

        return $file;
    }

    private function getForm(): string
    {
        $file = '';

        foreach ($this->model_specs['form_fields'] as $form_field) {
            $field_type = $this->getFieldType($form_field);
            $file .= $this->renderField($form_field, $field_type) . PHP_EOL;
        }

        return $file;
    }

    private function getFieldType($form_field): string
    {
        $prefix = substr($form_field,0, 4);

        switch ($prefix) {
            case 'tar_':
                return 'textarea';
            case 'opt_':
                return 'select';
            default:
                return 'text';
        }
    }

    private function renderField($field_name, $field_type): string
    {
        if ($field_type == 'select') {
            $field_title = Stringify::titlelize((str_replace(['opt_', 'mx_', '_id'], '', $field_name)));
        } else {
            $field_title = Stringify::titlelize(str_replace(substr($field_name,0, 4), '', $field_name));
        }

        $input = '';

        switch ($field_type) {
            case 'textarea':
                $input .= '<div class="form-group">' . PHP_EOL;
                $input .= "\t" . '<label for="' . $field_name . '" class = "col-lg-3 control-label">' . $field_title . '</label>' . PHP_EOL;
                $input .= "\t" . '<div class="col-lg-9">' . PHP_EOL;
                $input .= "\t\t" . '<textarea placeholder="Write ' . $field_title . '" name="' . $field_name . '" id="' . $field_name . '" class="form-control" cols="50"  ng-model="form.' . $field_name . '"></textarea>' . PHP_EOL;
                $input .= "\t" . '</div>' . PHP_EOL;
                $input .= '</div>' . PHP_EOL;
                break;
            case 'select':
                $input .= '<div class="form-group">' . PHP_EOL;
                $input .= "\t" . '<label for="' . $field_name . '" class = "col-lg-3 control-label">' . $field_title . '</label>' . PHP_EOL;
                $input .= "\t" . '<div class="col-lg-9">' . PHP_EOL;
                $input .= "\t\t" . '<select name="' . $field_name . '" id="' . $field_name . '" class="form-control" ng-model="form.' . $field_name . '"' . PHP_EOL;
                $input .= "\t\t\t\t" . 'ng-options="value.id as value.name for (key, value) in dropdowns.' . $field_name . 's" required>' . PHP_EOL;
                $input .= "\t\t\t" . '<option value="">Select ' . $field_title . '</option>' . PHP_EOL;
                $input .= "\t\t" . '</select>' . PHP_EOL;
                $input .= "\t" . '</div>' . PHP_EOL;
                $input .= '</div>' . PHP_EOL;
                break;
            default:
                $input .= '<div class="form-group">' . PHP_EOL;
                $input .= "\t" . '<label for="' . $field_name . '" class="col-lg-3 control-label">' . $field_title . '</label>' . PHP_EOL;
                $input .= "\t" . '<div class="col-lg-9">' . PHP_EOL;
                $input .= "\t\t" . '<input type="text" id="' . $field_name . '" placeholder="Enter ' . $field_title . '" name="' . $field_name . '"  class="form-control"' . PHP_EOL;
                $input .= "\t\t\t\t" . 'ng-class="' . $this->lowercase_class . '.' . $field_name . '.$invalid && !' . $this->lowercase_class . '.' . $field_name . '.$pristine" ng-model="form.' . $field_name . '" required/>' . PHP_EOL;
                $input .= "\t" . '</div>' . PHP_EOL;
                $input .= '</div>' . PHP_EOL;
                break;
        }

        return $input;
    }

    private function getMainAssociatedContent(): string
    {
        $file = '<div id="page-content">' . PHP_EOL;
        $file .= "\t" . '<div id="data_content"' . PHP_EOL;
        $file .= "\t\t" . 'data-associated="<?php echo htmlspecialchars(json_encode($this->data), ENT_COMPAT, \'UTF-8\')?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-headings="<?php echo htmlspecialchars(json_encode($this->table_headers,JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\')?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-labels="<?php echo htmlspecialchars(json_encode($this->labels ?? [],JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\')?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-hiddens="<?php echo htmlspecialchars(json_encode($this->hiddens ?? [],JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\')?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-formatters="<?php echo htmlspecialchars(json_encode($this->formatters ?? [],JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\')?>"' . PHP_EOL;
        $file .= "\t\t" . 'data-actions="<?php echo htmlspecialchars(json_encode($this->actions, JSON_NUMERIC_CHECK), ENT_COMPAT, \'UTF-8\')?>"' . PHP_EOL;
        $file .= "\t" . '></div>' . PHP_EOL;
        $file .= "\t" . '<div id="display_content">' . PHP_EOL;
        $file .= "\t\t" . '<div class="row">' . PHP_EOL;
        $file .= "\t\t\t" . '<div class="col-md-12">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<div  style="max-height: 50vh;" class="scrolled-div" ng-if="associated_records.length > 0">' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div ng-switch="\'<?php echo $this->caller ?>\'">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<div ng-switch-default>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '<?php include MX17_APP_ROOT . \'/views/templates/default_associated_records.php\' ?>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<div ng-switch-when="' . $this->class_title . ' Tab">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '<?php include \'' . $this->lowercase_class . '_tab.php\' ?>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<div class="row" style="margin-top: 10px;">' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<div class="col-md-12 text-center">' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<h5 class="text-info" style="font-size: 1.5em; opacity: 60%" ng-if="associated_records.length === 0">No <?php echo $this->caller ?> data available</h5>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t" . '</div>' . PHP_EOL;
        $file .= "\t" . '</div>' . PHP_EOL;
        $file .= '<div id="page-content">' . PHP_EOL;

        return $file;
    }

    private function getDefaultAssociatedContent(): string
    {
        $file = '<table class="table table-striped table-condensed table-hover table-bordered">';

        $file .= "\t" . '<thead>' . PHP_EOL;
        $file .= "\t\t" . '<tr class="bottom-border-color-orange primary">' . PHP_EOL;
        $file .= "\t\t\t" . '<th colspan="{{table_headers.length}}" style="font-size: 12pt; color:#f26e09; text-transform: uppercase;">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<?= $this->caller ?>' . PHP_EOL;
        $file .= "\t\t\t" . '</th>' . PHP_EOL;
        $file .= "\t\t" . '</tr>' . PHP_EOL;
        $file .= "\t" . '</thead>' . PHP_EOL;

        $file .= "\t" . '<thead class="thead-red" ng-if="associated_records.length > 0">' . PHP_EOL;
        $file .= "\t\t" . '<tr>' . PHP_EOL;
        $file .= "\t\t\t" . '<th ng-repeat="header in table_headers track by $index" ng-if="hiddens.indexOf(header) < 0">{{header}}</th>' . PHP_EOL;
        $file .= "\t\t\t" . '<th ng-if="associated_actions.length > 0">Actions</th>' . PHP_EOL;
        $file .= "\t\t" . '</tr>' . PHP_EOL;
        $file .= "\t" . '</thead>' . PHP_EOL;

        $file .= "\t" . '<tbody>' . PHP_EOL;
        $file .= "\t\t" . '<tr ng-repeat="record in associated_records">' . PHP_EOL;

        $file .= "\t\t\t" . '<td ng-repeat="(key, value) in record" ng-if="hiddens.indexOf(key) < 0">' . PHP_EOL;

        $file .= "\t\t\t\t" . '<div ng-switch="formatters[key] != undefined">' . PHP_EOL;

        $file .= "\t\t\t\t\t" . '<div ng-switch-when="true">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<div ng-switch="formatters[key][\'format\']">' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t" . '<div ng-switch-when="number">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '<strong ng-style="formatters[key][\'color\'] != undefined && {\'color\' : formatters[key][\'color\']}">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . ' {{ value | number }} ' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '</strong>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t" . '<div ng-switch-when="label">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '<span class="label" ng-style="' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . 'formatters[key][\'labels\'] == undefined && {\'background\' : \'black\'} || ' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . 'formatters[key][\'labels\'] != undefined && {\'background\' : formatters[key][\'labels\'][value]}' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '"> {{ value }} </span>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t" . '<div ng-switch-when="custom_label">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '<span class="label" ng-style="' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . 'formatters[key][\'labels\'] == undefined && {\'background\' : \'black\'} || ' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . 'formatters[key][\'labels\'] != undefined && {\'background\' : formatters[key][\'labels\'][value][\'color\']}' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '"> {{ formatters[key][\'labels\'][value][\'text\'] }} </span>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t" . '<div ng-switch-when="date">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '{{ reFormatDate(value) | date: formatters[key][\'type\'] }}' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t" . '<div ng-switch-default>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '<strong ng-style="formatters[key][\'color\'] != undefined && {\'color\' : formatters[key][\'color\']}">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . ' {{ value }} ' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t" . '</strong>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t\t" . '<div ng-switch-default>' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<span>{{ value }}</span>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t\t" . '</div>' . PHP_EOL;

        $file .= "\t\t\t" . '</td>' . PHP_EOL;

        $file .= "\t\t\t" . '<td ng-if="associated_actions.length > 0" class="text-center">' . PHP_EOL;
        $file .= "\t\t\t\t" . '<?php foreach ($this->actions as $button) { ?>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '<a href="#" ng-click="<?= $button[\'function\'] ?>" class="<?= $button[\'cssclass\'] ?> associated_records_action" ' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . 'ng-class="{disabledLink: <?= $button[\'disabled\'] ?>}">' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '<i class="<?= $button[\'icon\'] ?>"></i>' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '</a>' . PHP_EOL;
        $file .= "\t\t\t\t" . '<?php } ?>' . PHP_EOL;
        $file .= "\t\t\t" . '</td>' . PHP_EOL;

        $file .= "\t\t" . '</tr>' . PHP_EOL;
        $file .= "\t" . '</tbody>' . PHP_EOL;

        $file .= '</table>';






        $file .= "\t" . '' . PHP_EOL;
        $file .= "\t\t" . '' . PHP_EOL;
        $file .= "\t\t\t" . '' . PHP_EOL;
        $file .= "\t\t\t\t" . '' . PHP_EOL;
        $file .= "\t\t\t\t\t" . '' . PHP_EOL;
        $file .= "\t\t\t\t\t\t" . '' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t" . '' . PHP_EOL;

        $file .= "\t\t\t\t\t\t\t\t" . '' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t" . '' . PHP_EOL;
        $file .= "\t\t\t\t\t\t\t\t\t\t" . '' . PHP_EOL;


        return $file;
    }
}