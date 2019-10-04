<?php

class grid
{
    const
        FMT_INTEGER = "INT",
        FMT_MONEY = "MON",
        FMT_SELECT = "SEL",
        FMT_STRING = "STR",
        FMT_DATE = "DAT",
        FMT_DATETIME = "DTM",
        FMT_BOOLEAN = "BLN";

    public
        $id,
        $jsonUrl,
        $uniqueIdFields,
        $colModel,
        $defaultRowsPerPage,
        $defaultSortName,
        $defaultSortOrder,
        $filterToolbar,
        $subGrid = false,
        $subGridUrl,
        $subGridModel,
        $actions;

    public function __construct($id, $jsonUrl = null, $uniqueIdFields = null, $colModel = null, $defaultSortName = null, $defaultSortOrder = 'asc', $defaultRowsPerPage = 30, $filterToolbar = true)
    {
        $this->id = $id;

        $this
            ->setJsonUrl($jsonUrl)
            ->setUniqueIdFields($uniqueIdFields)
            ->setColModel($colModel)
            ->setDefaultSortName($defaultSortName)
            ->setDefaultSortOrder($defaultSortOrder)
            ->setDefaultRowsPerPage($defaultRowsPerPage)
            ->setFilterToolbar($filterToolbar);

        $site = webApp::app()->getSite() . 'webApp/assets/';

        controller::addCss($site . 'css/ui.jqgrid.css');
        controller::addCss($site . 'css/bootstrap-datepicker3.min.css');

        controller::addJs($site . 'js/grid.locale-es.js');
        controller::addJs($site . 'js/jquery.jqgrid.min.js');
        controller::addJs($site . 'js/bootstrap-datepicker.min.js');

        webApp_form::addJsCss();
    }

    public function setFilterToolbar($filterToolbar)
    {
        $this->filterToolbar = $filterToolbar;

        return $this;
    }

    public function setDefaultRowsPerPage($rows)
    {
        $this->defaultRowsPerPage = $rows;

        return $this;
    }

    public function setDefaultSortOrder($defaultSortOrder, $force = false)
    {
        $this->defaultSortOrder = $defaultSortOrder;

        if ($force and isset($_COOKIE[$this->id . "-sord"])) {
            setcookie($this->id . "-sord", "", time(), "/");
            unset($_COOKIE[$this->id . "-sord"]);
        }

        return $this;
    }

    public function setDefaultSortName($defaultSortName, $force = false)
    {
        $this->defaultSortName = $defaultSortName;

        if ($force and isset($_COOKIE[$this->id . "-sidx"])) {
            setcookie($this->id . "-sidx", "", time(), "/");
            unset($_COOKIE[$this->id . "-sidx"]);
        }

        return $this;
    }

    public function setColModel($colModel)
    {
        if (is_array($colModel))
            $this->colModel = $this->constructColModel($colModel);

        return $this;
    }

    private function constructColModel($colModel)
    {
        foreach ($colModel as $k => $v) {

            if (isset($v["format"])) {

                switch ($v["format"]) {

                    case $this::FMT_STRING:

                        if (!isset($v["search"]))
                            $colModel[$k]['search'] = $v['name'] . ' LIKE %:' . $v['name'] . '%';

                        break;

                    case $this::FMT_INTEGER:

                        if (!isset($v["width"]))
                            $colModel[$k]['width'] = 80;

                        if (!isset($v["align"]))
                            $colModel[$k]['align'] = 'right';

                        break;

                    case $this::FMT_MONEY:

                        if (!isset($v["width"]))
                            $colModel[$k]['width'] = 100;

                        if (!isset($v["align"]))
                            $colModel[$k]['align'] = 'right';

                        break;

                    case $this::FMT_DATE:
                    case $this::FMT_DATETIME:

                        if (!isset($v['search']))
                            $colModel[$k]['search'] = 'DATE(' . $v['name'] . ') = :' . $v['name'];

                        if (!isset($v["align"]))
                            $colModel[$k]['align'] = 'center';

                        if (!isset($v["width"]))
                            $colModel[$k]['width'] = $v["format"] == $this::FMT_DATETIME ? 120 : 80;

                        break;

                    case $this::FMT_SELECT:

                        $colModel[$k]['formatter']     = 'select';
                        $colModel[$k]['stype']         = 'select';
                        $colModel[$k]['searchoptions'] = $colModel[$k]['formatoptions'];

                        $colModel[$k]['searchoptions']['value'] = array('' => '') + $colModel[$k]['searchoptions']['value'];

                        if (!isset($v["align"]))
                            $colModel[$k]['align'] = 'center';

                        break;

                    case $this::FMT_BOOLEAN:

                        $colModel[$k]['formatoptions'] = array(
                            'value' => array(
                                0 => 'No',
                                1 => 'Sí'
                            )
                        );

                        $colModel[$k]['formatter']     = 'select';
                        $colModel[$k]['stype']         = 'select';
                        $colModel[$k]['searchoptions'] = $colModel[$k]['formatoptions'];

                        $colModel[$k]['searchoptions']['value'] = array('' => '') + $colModel[$k]['searchoptions']['value'];

                        if (!isset($v["align"]))
                            $colModel[$k]['align'] = 'center';

                        break;
                }

            }

            if (!isset($v['label']))
                $colModel[$k]['label'] = ucfirst(str_replace('_', ' ', $v['name']));

            if (isset($v['dataFunction']))
                $colModel[$k]['formatter'] = $v['dataFunction'];

        }

        return $colModel;
    }

    public function setUniqueIdFields($uniqueIdFields)
    {
        if (is_string($uniqueIdFields))
            $this->uniqueIdFields = array($uniqueIdFields);
        elseif (is_array($uniqueIdFields))
            $this->uniqueIdFields = $uniqueIdFields;
        else
            $this->uniqueIdFields = array();

        return $this;
    }

    public function setJsonUrl($jsonUrl)
    {
        $this->jsonUrl = $jsonUrl;

        return $this;
    }

    public function setSubGrid($jsonUrl, $colModel)
    {
        if (is_array($colModel)) {

            $this->subGrid      = true;
            $this->subGridUrl   = $jsonUrl;
            $this->subGridModel = $this->constructColModel($colModel);

        }

        return $this;
    }

    public function render($id = null)
    {
        if ($id == null)
            $id = $this->id . '-webApp_grid';

        foreach ($this->colModel as $k => $v) {

            if (isset($v['format']) and in_array($v['format'], array($this::FMT_DATE, $this::FMT_DATETIME))) {

                $this->colModel[$k]['searchoptions'] = array(
                    'sopt'     => array('eq'),
                    'dataInit' => '##@@dateSearch@@##'
                );

            }

        }

        $colModel = json_encode($this->colModel);

        $colModel = str_replace(
            '"##@@dateSearch@@##"',
            'function (elem, options) {
                var self = this, $elem = $(elem);
                $elem.on("change", function(e) {
                    self.triggerToolbar();
                });
                setTimeout(function () {
                    $elem.datepicker({
                        format: "yyyy-mm-dd",
                        autoclose: true,
                        language: "es"
                    })
                }, 50);
            }',
            $colModel
        );

        if ($this->subGrid)
            $subGridModel = json_encode($this->subGridModel);
        else
            $subGridModel = '[]';

        $html = '<table id="' . $id . '"></table>';

        $html .=
            '<script>
                $(document).ready(function(){
                    $("#' . $id . '").webApp_grid({
                        jsonUrl         : "' . $this->jsonUrl . '",
                        colModel        : ' . $colModel . ',
                        rowsPerPage     : ' . $this->getRowsPerPage() . ',
                        sortName        : "' . $this->getSortName() . '",
                        sortOrder       : "' . $this->getSortOrder() . '",
                        filterToolbar   : ' . ($this->filterToolbar ? 'true' : 'false') . ',
                        subGrid         : ' . ($this->subGrid ? 'true' : 'false') . ',
                        subGridUrl      : "' . ($this->subGrid ? $this->subGridUrl : '') . '",
                        subGridModel    : ' . $subGridModel . '
                    });
                });
            </script>';

        return $html;
    }

    private function getRowsPerPage()
    {
        if (isset($_COOKIE["webApp_gridRows"]) and intval($_COOKIE["webApp_gridRows"]) == $_COOKIE["webApp_gridRows"])
            return $_COOKIE["webApp_gridRows"];

        return $this->defaultRowsPerPage;
    }

    private function getSortName()
    {
        if (isset($_COOKIE[$this->id . "-sidx"])) {

            foreach ($this->colModel as $c) {
                if ($c["name"] == $_COOKIE[$this->id . "-sidx"])
                    return $_COOKIE[$this->id . "-sidx"];
            }

        }

        return $this->defaultSortName;
    }

    private function getSortOrder()
    {
        if (isset($_COOKIE[$this->id . "-sord"]) and in_array(strtolower($_COOKIE[$this->id . "-sord"]), array("asc", "desc")))
            return $_COOKIE[$this->id . "-sord"];

        return $this->defaultSortOrder;
    }

    public function renderData($db, $sqlQuery, $sqlParams = null)
    {
        if ($sqlParams == null)
            $sqlParams = array();

        $page  = $_REQUEST['page'];
        $limit = $_REQUEST['rows'];
        $sidx  = $_REQUEST['sidx'];
        $sord  = $_REQUEST['sord'];

        setcookie("webApp_gridRows", $limit, time() + 60 * 60 * 24 * 365, "/");
        setcookie($this->id . "-sidx", $sidx, time() + 60 * 60 * 24 * 365, "/");
        setcookie($this->id . "-sord", $sord, time() + 60 * 60 * 24 * 365, "/");

        $wh = "";

        $searchOn = $this->Strip($_REQUEST['_search']);

        if ($searchOn == 'true') {

            $sarr = $this->Strip($_REQUEST);

            foreach ($sarr as $k => $v) {

                foreach ($this->colModel as $c) {

                    if ($c['name'] == $k) {

                        $replaceString = isset($c['search']) ? $c['search'] : ($k . ' = :' . $k);

                        $wh .= ' AND (' . str_replace(':k', $k, $replaceString) . ')';

                        $sqlParams[$k] = $v;

                    }

                }

            }

        }

        $total = $this->getSQLCount($db, $sqlQuery, $sqlParams, $wh);

        if ($total > 0)
            $total_pages = ceil($total / $limit);
        else
            $total_pages = 0;

        if ($page > $total_pages)
            $page = $total_pages;

        $start = $limit * $page - $limit;

        if ($start < 0)
            $start = 0;

        $arr            = array();
        $arr["page"]    = $page;
        $arr["total"]   = $total_pages;
        $arr["records"] = $total;

        $result = $this->getSQLResult($db, $sqlQuery, $sqlParams, $wh, $sidx, $sord, $start, $limit);

        while ($row = $db->getRow($result)) {

            $cell = array();

            foreach ($this->colModel as $c) {

                if (isset($c["dataFunction"]))
                    $cell[$c["name"]] = call_user_func($c["dataFunction"], $row);
                else
                    $cell[$c["name"]] = $row->{$c["name"]};

                if (isset($c["format"]))
                    $cell[$c["name"]] = $this->format($cell[$c["name"]], $c["format"]);

            }

            $arr["rows"][] = array(
                'id'   => $this->getUniqueId($row),
                'cell' => $cell
            );

        }

        header("Content-type: text/json;charset=utf-8");

        echo json_encode($arr);

    }

    private function Strip($value)
    {
        if (get_magic_quotes_gpc() != 0) {

            if (is_array($value))

                if ($this->array_is_associative($value)) {

                    foreach ($value as $k => $v)
                        $tmp_val[$k] = stripslashes($v);

                    $value = $tmp_val;

                } else

                    for ($j = 0; $j < sizeof($value); $j++)
                        $value[$j] = stripslashes($value[$j]);

            else

                $value = stripslashes($value);

        }

        return $value;
    }

    private function array_is_associative($array)
    {
        if (is_array($array) && !empty($array)) {

            for ($iterator = count($array) - 1; $iterator; $iterator--) {
                if (!array_key_exists($iterator, $array))
                    return true;
            }

            return !array_key_exists(0, $array);

        }

        return false;
    }

    private function getSQLCount($db, $sqlQuery, $sqlParams, $wh)
    {
        $sqlQuery = str_replace('SELECT * FROM', 'SELECT COUNT(*) AS total FROM', $sqlQuery);

        if (strpos($sqlQuery, ' WHERE ') === false)
            $sqlQuery .= ' WHERE 1=1';

        $sqlQuery .= $wh;

        $row = $db->queryRow($sqlQuery, $sqlParams);

        return $row->total;
    }

    private function getSQLResult($db, $sqlQuery, $sqlParams, $wh = '', $sidx = null, $sord = null, $start = null, $limit = null)
    {
        if (strpos($sqlQuery, ' WHERE ') === false)
            $sqlQuery .= ' WHERE 1=1';

        $sqlQuery .= $wh;

        if (!empty($sidx)) {

            $sqlQuery .= ' ORDER BY ' . $sidx;

            if (!empty($sord))
                $sqlQuery .= ' ' . $sord;

        }

        if (!empty($start) and !empty($limit))
            $sqlQuery .= ' LIMIT ' . $start . ',' . $limit;

        return $db->query($sqlQuery, $sqlParams);

    }

    private function format($data, $format)
    {
        switch ($format) {

            case $this::FMT_INTEGER:
                return number_format($data, 0, ",", ".");

            case $this::FMT_MONEY:
                return number_format($data, 2, ",", ".");

        }

        return $data;

    }

    private function getUniqueId($row)
    {
        $id = '';

        foreach ($this->uniqueIdFields as $i)
            $id .= $row->$i;

        return $id;
    }

    public function subGridRenderData($db, $sqlQuery, $sqlParams = null)
    {
        if ($sqlParams == null)
            $sqlParams = array();

        $arr = array();

        $result = $this->getSQLResult($db, $sqlQuery, $sqlParams);

        while ($row = $db->getRow($result)) {

            $cell = array();

            foreach ($this->subGridModel as $c) {

                if (isset($c["dataFunction"]))
                    $cell[$c["name"]] = call_user_func($c["dataFunction"], $row);
                else
                    $cell[$c["name"]] = $row->{$c["name"]};

                if (isset($c["format"]))
                    $cell[$c["name"]] = $this->format($cell[$c["name"]], $c["format"]);

            }

            $arr["rows"][] = array(
                'id'   => $this->getUniqueId($row),
                'cell' => $cell
            );

        }

        header("Content-type: text/json;charset=utf-8");

        echo json_encode($arr);

    }

    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    public function renderActionButtons()
    {
        $html = '';

        foreach ($this->actions as $a)
            $html .= $a->render($this->id);

        return $html;
    }

}

class navbar
{
    private $nav = array();

    public function __construct($nav)
    {
        $this->nav = $nav;
    }

    public function addButton($b)
    {
        $this->nav[] = $b;
    }

    public function render()
    {
        $html = '';

        foreach ($this->nav as $b)
            $html .= $b->render();

        return $html;
    }
}

class actionButton
{
    const
        EXPORT  = '<i class="fa fa-download"></i> Exportar',
        REFRESH = '<i class="fa fa-refresh"></i> Actualizar',
        ADD     = '<i class="fa fa-plus"></i> Nuevo',
        EDIT    = '<i class="fa fa-edit"></i> Editar',
        DELETE  = '<i class="fa fa-trash"></i> Eliminar'
    ;

    public
        $id,
        $text,
        $url = '#'
    ;

    public function __construct($text, $param)
    {
        $this->text = $text;

        if (substr($param, 0, 4) == 'http')
            $this->url = $param;
        else
            $this->id = $param;
    }

    public function render()
    {
        return '<a type="button" href="' . $this->url . '" class="btn btn-default" id="' . $this->id . '" onclick="' . $this->id . '();return false;">' . $this->text . '</a>';
    }

}

class gridActionButton
{
    const
        EDIT = "E",
        DELETE = "D",
        MULTI_DELETE = "MD",
        ADD = "A";

    const
        TYPE_REDIRECT = "R",
        TYPE_MODAL = "M",
        TYPE_AJAX = "A",
        TYPE_CUSTOM = "C";

    public
        $action,
        $type,
        $url,
        $func,
        $size;

    public function __construct($action, $param = array())
    {
        $this->action = $action;

        if (is_string($param))
            $this->url = $param;
        elseif (is_array($param))
            extract($param);

        if (isset($url))
            $this->url = $url;

        if (isset($function))
            $this->func = $function;

        if (isset($size))
            $this->size = $size;

        if (isset($type))

            $this->type = $type;

        else{

            switch ($this->action) {

                case $this::ADD:
                case $this::EDIT:
                    $this->type = $this::TYPE_MODAL;
                    break;

                case $this::DELETE:
                case $this::MULTI_DELETE:
                    $this->type = $this::TYPE_AJAX;

            }

        }

    }

    public function render($gridId)
    {
        switch ($this->action) {

            case $this::ADD:
                $ask   = false;
                $icon  = "";
                $idExt = 'new';
                $title = '<i class="fa fa-plus"></i> Nuevo';
                $cant  = 0;
                break;

            case $this::EDIT:
                $ask   = false;
                $icon  = "";
                $idExt = 'edit';
                $title = '<i class="fa fa-edit"></i> Editar';
                $id    = '$("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow")[0]';
                $cant  = 1;
                break;

            case $this::DELETE:
                $ask   = '¿Desea eliminar el registros seleccionado?';
                $icon  = '<i class="fa fa-trash"></i>';
                $idExt = 'delete';
                $title = '<i class="fa fa-trash"></i> Eliminar';
                $id    = '$("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow")[0]';
                $cant  = 1;
                break;

            case $this::MULTI_DELETE:
                $ask   = '¿Desea eliminar los registros seleccionados?';
                $icon  = '<i class="fa fa-trash"></i>';
                $idExt = 'delete';
                $title = '<i class="fa fa-trash"></i> Eliminar';
                $id    = '$("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow")';
                $cant  = 2;
                break;
        }

        $html = '<a type="button" href="#" class="btn btn-default" id="' . $gridId . '-webApp_grid-' . $idExt . '">' . $title . '</a>';

        switch ($this->type) {

            case $this::TYPE_REDIRECT:
                $html .= '<script>
                        $("#' . $gridId . '-webApp_grid-' . $idExt . '").click(function(e){
                            if (' . $cant . ' == 1 && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length > 1)
                                $.webApp_modal("Seleccioná un solo item", null, null, "S");
                            else if (' . $cant . ' == 0 || ((' . $cant . ' == 1 || ' . $cant . ' == 2) && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length >= 1))
                                ' . $this->getAskCode($ask, $icon, 'window.location = "' . $this->url . '"' . (isset($id) ? (' + "?id=" + ' . $id) : '') . ';') . '
                            e.preventDefault();
                        });
                    </script>';
                break;

            case $this::TYPE_MODAL:
                $html .= '<script>
                        $("#' . $gridId . '-webApp_grid-' . $idExt . '").click(function(e){
                            if (' . $cant . ' == 1 && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length > 1)
                                $.webApp_modal("Seleccioná un solo item", null, null, "S");
                            else if (' . $cant . ' == 0 || ((' . $cant . ' == 1 || ' . $cant . ' == 2) && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length >= 1)) {
                                ' . $this->getAskCode($ask, $icon, '$.ajax({
                                    url    : "' . $this->url . '",
                                    type   : "POST",
                                    data   : {
                                        id: ' . (isset($id) ? $id : '""') . ',
                                        ua: "' . $_COOKIE["user_account"] . '",
                                        ut: "' . $_COOKIE["user_token"] . '"
                                    },
                                    success: function (res) {
                                        $.webApp_modal(res, "' . addslashes($title) . '", [], ' . ($this->size == null ? 'null' : ('"' . $this->size . '"')) . ');
                                    }
                                });') . '
                            }
                            e.preventDefault();
                        });
                    </script>';
                break;

            case $this::TYPE_AJAX:
                $html .= '<script>
                        $("#' . $gridId . '-webApp_grid-' . $idExt . '").click(function(e){
                            if (' . $cant . ' == 1 && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length > 1)
                                $.webApp_modal("Seleccioná un solo item");
                            else if (' . $cant . ' == 0 || ((' . $cant . ' == 1 || ' . $cant . ' == 2) && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length >= 1)) {
                                ' . $this->getAskCode($ask, $icon, '$.ajax({
                                    url    : "' . $this->url . '",
                                    type   : "POST",
                                    data   : {
                                        id: ' . (isset($id) ? $id : '""') . ',
                                        ua: "' . $_COOKIE["user_account"] . '",
                                        ut: "' . $_COOKIE["user_token"] . '"
                                    },
                                    success: function (res) {
                                        if (res != "" && "error" in res && res.error == 0)
                                            $("#' . $gridId . '-webApp_grid").trigger("reloadGrid", { fromServer: true });
                                        else if (res != "" && "error" in res && "mensaje" in res)
                                            $.webApp_modal(res.mensaje);
                                        else
                                            $.webApp_modal("Ha ocurrido un error, intentá de nuevo más tarde.");
                                    }
                                });') . '
                            }
                            e.preventDefault();
                        });
                    </script>';
                break;

            case $this::TYPE_CUSTOM:
                $html .= '<script>
                        $("#' . $gridId . '-webApp_grid-' . $idExt . '").click(function(e){
                            if (' . $cant . ' == 1 && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length > 1)
                                $.webApp_modal("Seleccioná un solo item", null, null, "S");
                            else if (' . $cant . ' == 0 || ((' . $cant . ' == 1 || ' . $cant . ' == 2) && $("#' . $gridId . '-webApp_grid").jqGrid("getGridParam", "selarrrow").length >= 1)) {
                                ' . $this->getAskCode($ask, $icon, 'var f = ' . $this->func . ';
                                f(' . (isset($id) ? $id : '') . ');') . '
                            }
                            e.preventDefault();
                        });
                    </script>';
                break;
        }

        return $html;
    }

    private function getAskCode($ask, $icon = '<i class="fa fa-save"></i>', $code)
    {
        if (!empty($ask)) {

            return '$.webApp_modal("' . $ask . '", "Atención",
                [
                    {
                        text: \'' . $icon  . ' Aceptar\',
                        eventFunction: function(){
                            ' . $code . '
                        }
                    },
                    {
                        text: \'<i class="fa fa-remove"></i> Cancelar\'
                    }
                ]
            );';

        }else{

            return $code;

        }
    }

}