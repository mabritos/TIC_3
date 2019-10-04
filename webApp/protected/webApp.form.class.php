<?php

class webApp_form
{
    public
        $name,
        $method,
        $action,
        $ajax = false,
        $ajaxCallback,
        $gridId,
        $atributes,
        $columns;

    public function __construct($name, $columns = null, $param = array())
    {
        $this->addJsCss();

        $this->name    = $name;
        $this->columns = $columns;
        $this->method  = isset($param['method']) ? $param['method'] : 'post';

        if (isset($param['action']))
            $this->action = $param['action'];

        if (isset($param['ajax']))
            $this->ajax = $param['ajax'];

        if (isset($param['ajaxCallback']))
            $this->ajaxCallback = $param['ajaxCallback'];

        if (isset($param['gridId']))
            $this->gridId = $param['gridId'];

        if (isset($param['buttons']))

            $this->buttons = $param['buttons'];

        else

            $this->buttons = array(
                new webApp_button('guardar', webApp_button::SUBMIT, webApp_button::PRIMARY)
            );

        return $this;
    }

    public static function addJsCss()
    {
        $site = webApp::app()->getSite() . 'webApp/assets/';

        controller::addCss($site . 'css/bootstrap-datepicker3.min.css');

        controller::addJs($site . 'js/jquery.validate.min.js');
        controller::addJs($site . 'js/additional-methods.min.js');
        controller::addJs($site . 'js/bootstrap-datepicker.min.js');
    }

    public function addColumn($c)
    {
        $this->columns[] = $c;

        return $this;
    }

    public function validate()
    {
        return true;
    }

    public function render()
    {
        $rules = array();

        $html = '<form role="form" id="' . $this->name . '" method="' . $this->method . '"' . ($this->action != null ? (' action="' . $this->action . '"') : '') . '>';

        $html .= '<div class="row">';

        $html .= '<div class="col-md-12">';

        $html .= '<div class="row">';

        foreach ($this->columns as $c) {

            $html .= '<div class="col-md-' . (12 / count($this->columns)) . '">';

            $html .= $c->render($this->name, $this->atributes, $rules);

            $html .= '</div>';

        }

        $html .= '</div>';

        $html .= '</div>';

        $html .= '</div>';

        foreach ($this->buttons as $b)
            $html .= $b->render($this->name, $this->atributes, $rules);

        $html .= '</form>';

        $html .= '<script>';

        $html .= '$("#' . $this->name . '").validate(' . json_encode(array('rules' => $rules)) . ');';

        $html .= '$(".webAppForm-datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, language: "es"});';

        if ($this->ajax) {

            if ($this->ajaxCallback != null)
                $ajaxCallback = 'f = ' . $this->ajaxCallback . ';f(res);';
            elseif ($this->gridId != null)
                $ajaxCallback = 'f = function(){$("#' . $this->gridId . '-webApp_grid").trigger("reloadGrid", { fromServer: true });};f(res);';
            else
                $ajaxCallback = '';

            $html .= '$("#' . $this->name . '").submit(function(){
                if ($("#' . $this->name . '").valid()) {
                    $.ajax({
                        url    : "' . $this->action . '",
                        type   : "' . $this->method . '",
                        data   : $("#' . $this->name . '").serialize() + "&ua=' . $_COOKIE["user_account"] . '&ut=' . $_COOKIE["user_token"] . '",
                        success: function (res) {
                            if (res != "" && "error" in res && res.error == 0) {
                                ' . $ajaxCallback . '
                                $("#myModal").modal("hide");
                            }else if (res != "" && "error" in res && "mensaje" in res)
                                $.webApp_modal(res.mensaje);
                            else
                                $.webApp_modal("Ha ocurrido un error, intentá de nuevo más tarde.");
                        }
                    });
                }
                return false;
            })';

        }

        $html .= '</script>';

        return $html;
    }

    public function getAtributes()
    {
        $this->loadAtributes($this, $this->atributes);

        return $this->atributes;
    }

    public function setAtributes($atributes)
    {
        $this->loadAtributes($this, $this->atributes);

        if (is_array($atributes)) {

            foreach ($atributes as $k => $v) {
                if (isset($this->atributes[$k]))
                    $this->atributes[$k] = $v;
            }

        }elseif (is_object($atributes)) {

            foreach ($this->atributes as $k => $v) {
                if (isset($atributes->$k) and isset($this->atributes[$k]))
                    $this->atributes[$k] = $atributes->$k;
            }

        }
    }

    private function loadAtributes($obj, &$atributes = null)
    {
        if ($atributes == null)
            $atributes = array();

        if (isset($obj->columns)) {
            foreach ($obj->columns as $c)
                $this->loadAtributes($c, $atributes);
        }

        if (isset($obj->tabs)) {
            foreach ($obj->tabs as $t)
                $this->loadAtributes($t, $atributes);
        }

        if (isset($obj->objs)) {
            foreach ($obj->objs as $o) {
                if (isset($o->name) and !isset($atributes[$o->name]) and !isset($o->htmlOptions['disabled']))
                    $atributes[$o->name] = '';
                else
                    $this->loadAtributes($o, $atributes);
            }
        }
    }

}

class webApp_column
{
    public
        $objs;

    public function __construct($objs = null)
    {
        $this->objs = $objs == null ? array() : $objs;
    }

    public function add($obj)
    {
        $this->objs[] = $obj;

        return $this;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        $html = '';

        foreach ($this->objs as $o)
            $html .= $o->render($formName, $formAtributes, $formRules);

        return $html;
    }

}

class webApp_tabs
{
    public
        $name,
        $title,
        $tabs;

    public function __construct($name, $tabs = null, $title = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->tabs = $tabs == null ? array() : $tabs;

        return $this;
    }

    public function addTab($tab)
    {
        $this->tabs[] = $tab;

        return $this;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        $ok = false;
        foreach ($this->tabs as $t) {
            if ($t->active) {
                $ok = true;
                break;
            }
        }
        if (!$ok and isset($this->tabs[0]))
            $this->tabs[0]->active = true;

        $html = '<div class="panel panel-default">';

        if ($this->title != null)
            $html .= '<div class="panel-heading">' . $this->title . '</div>';

        $html .= '<div class="panel-body">';

        $html .= '<ul class="nav nav-tabs">';

        foreach ($this->tabs as $t)
            $html .= '<li' . ($t->active ? ' class="active"' : '') . '><a href="#' . $t->name . '" data-toggle="tab" aria-expanded="' . ($t->active ? 'true' : 'false') . '">' . $t->title . '</a></li>';

        $html .= '</ul>';

        $html .= '<div class="tab-content">';

        foreach ($this->tabs as $t)
            $html .= $t->render($formName, $formAtributes, $formRules);

        $html .= '</div>';

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_tab
{
    public
        $name,
        $title,
        $active,
        $columns;

    public function __construct($name, $columns = null, $param = array())
    {
        $this->name    = $name;
        $this->title   = isset($param['title']) ? $param['title'] : ucfirst($name);
        $this->active  = isset($param['active']) ? $param['active'] : false;
        $this->columns = $columns;

        return $this;
    }

    public function addColumn($c)
    {
        $this->columns[] = $c;

        return $this;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        $html = '<div class="tab-pane fade in padding-top' . ($this->active ? ' active' : '') . '" id="' . $this->name . '">';

        $html .= '<div class="row">';

        foreach ($this->columns as $c) {

            $html .= '<div class="col-md-' . (12 / count($this->columns)) . '">';

            $html .= $c->render($formName, $formAtributes, $formRules);

            $html .= '</div>';

        }

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_form_element
{
    public
        $name,
        $label,
        $value,
        $rules,
        $htmlOptions;

    public function __construct($name, $param = array())
    {
        $this->name  = $name;
        $this->label = isset($param['label']) ? $param['label'] : ucfirst($this->name);
        $this->rules = isset($param['rules']) ? $param['rules'] : null;

        $this->htmlOptions = isset($param['htmlOptions']) ? $param['htmlOptions'] : array();
    }

    protected function render($formName = null, $formAtributes = null, &$rules)
    {
        if (!empty($this->rules))
            $rules[$formName . '[' . $this->name . ']'] = $this->rules;
    }

    protected function renderHtmlOptions($class = '')
    {
        $res = '';

        foreach ($this->htmlOptions as $k => $v) {

            if (strtolower($k) == 'class')
                $v = $class . ' ' . $v;

            $res .= ' ' . $k . '="' . $v . '"';

        }

        if (!empty($class) and !isset($this->htmlOptions['class']))
            $res .= ' class="' . $class . '"';

        return $res;
    }

}

class webApp_input extends webApp_form_element
{
    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->value = isset($param['value']) ? $param['value'] : '';
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        if ($this->label)
            $html .= '<label>' . $this->label . '</label>';

        $html .= '<input type="text" name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '" value="' . $this->value . '"' . parent::renderHtmlOptions('form-control') . '>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_email extends webApp_form_element
{
    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->value = isset($param['value']) ? $param['value'] : '';
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        $html .= '<label>' . $this->label . '</label>';

        $html .= '<input type="email" name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '" value="' . $this->value . '"' . parent::renderHtmlOptions('form-control') . '">';

        $html .= '</div>';

        return $html;
    }

}

class webApp_date extends webApp_form_element
{
    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->value = isset($param['value']) ? $param['value'] : '';
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        $html .= '<label>' . $this->label . '</label>';

        $html .= '<input type="text" name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '" value="' . $this->value . '"' . parent::renderHtmlOptions('form-control webAppForm-datepicker') . '">';

        $html .= '</div>';

        return $html;
    }

}

class webApp_password extends webApp_form_element
{
    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->value = isset($param['value']) ? $param['value'] : '';
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        $html .= '<label>' . $this->label . '</label>';

        $html .= '<input type="password" name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '" value="' . $this->value . '"' . parent::renderHtmlOptions('form-control') . '>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_hidden extends webApp_form_element
{
    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->value = isset($param['value']) ? $param['value'] : '';
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        return  '<input type="hidden" name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '" value="' . $this->value . parent::renderHtmlOptions() . '">';
    }

}

class webApp_select extends webApp_form_element
{
    public
        $options;

    public function __construct($name, $options, $param = array())
    {
        parent::__construct($name, $param);

        $this->options = $options;
        $this->value   = isset($param['value']) ? $param['value'] : null;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        $html .= '<label>' . $this->label . '</label>';

        $html .= '<select name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '"' .parent::renderHtmlOptions('form-control') . '">';

        foreach ($this->options as $k => $o)
            $html .= '<option value="' . $k . '"' . ($k == $this->value ? ' selected' : '') . '>' . $o . '</option>';

        $html .= '</select>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_checkbox extends webApp_form_element
{
    public
        $checked;

    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->checked = isset($param['checked']) ? $param['checked'] : false;
        $this->value   = isset($param['value']) ? $param['value'] : 1;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->checked = ($formAtributes[$this->name] == $this->value);

        $html = '<div class="form-group">';

        $html .= '<div class="checkbox">';

        $html .= '<label>';

        $html .= '<input type="checkbox" name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '" value="' . $this->value . '"' . ($this->checked ? ' checked' : '') . parent::renderHtmlOptions() . '>';

        $html .= $this->label;

        $html .= '</label>';

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_radios extends webApp_form_element
{
    public
        $options;

    public function __construct($name, $options, $param = array())
    {
        parent::__construct($name, $param);

        $this->options = $options;
        $this->value   = isset($param['value']) ? $param['value'] : null;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        $html .= '<label>' . $this->label . '</label>';

        $n = 0;
        foreach ($this->options as $k => $o) {

            $n ++;

            $html .= '<div class="radio">';

            $html .= '<label>';

            $html .= '<input type="radio" name="' . $formName . '[' . $this->name . ']" id="' . ($this->name . '-' . $k) . '" value="' . $k . '"' . (($this->value == $k or ($this->value == null and $n == 1)) ? ' checked' : '') . parent::renderHtmlOptions() . '>';

            $html .= $o;

            $html .= '</label>';

            $html .= '</div>';

        }

        $html .= '</div>';

        return $html;
    }

}

class webApp_textarea extends webApp_form_element
{
    public function __construct($name, $param = array())
    {
        parent::__construct($name, $param);

        $this->value = isset($param['value']) ? $param['value'] : '';
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        parent::render($formName, $formAtributes, $formRules);

        if (isset($formAtributes[$this->name]))
            $this->value = $formAtributes[$this->name];

        $html = '<div class="form-group">';

        if ($this->label)
            $html .= '<label>' . $this->label . '</label>';

        $html .= '<textarea name="' . $formName . '[' . $this->name . ']" id="' . $this->name . '"' . parent::renderHtmlOptions('form-control') . '>' . $this->value . '</textarea>';

        $html .= '</div>';

        return $html;
    }

}

class webApp_button extends webApp_form_element
{
    const
        SUBMIT    = 'submit',
        BUTTON    = 'button',
        PRIMARY   = 'primary',
        SECONDARY = 'secondary',
        STANDARD  = 'default';

    public
        $type,
        $style;

    public function __construct($name, $type = self::SUBMIT, $style = self::STANDARD, $param = array())
    {
        parent::__construct($name, $param);

        $this->type  = $type;
        $this->style = $style;
    }

    public function render($formName, $formAtributes, &$formRules)
    {
        return '<button type="' . $this->type . '"' . parent::renderHtmlOptions('btn btn-' . $this->style) . '">' . $this->label . '</button>';
    }

}