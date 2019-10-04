<?php

class controller
{
    public $titulo;

    static private
        $js,
        $css
    ;

    public function __construct($titulo = "")
    {
        $this->titulo   = $titulo;
        self::$js       = array();
        self::$css      = array();

        $this->addJs(webApp::app()->getSite() . 'webApp/assets/js/webApp.js');

        $this->init();
    }

    public function init()
    {
    }

    public function index()
    {
        header("HTTP/1.0 404 Not Found");
    }

    static public function addJs($jsFile)
    {
        self::$js[$jsFile] = $jsFile;
    }

    static public function addCss($cssFile)
    {
        self::$css[$cssFile] = $cssFile;
    }

    public function render($view, $param = array())
    {
        $class  = get_class($this);

        $dir = webApp::app()->getDirProtected() . '/views/' . $class . '/';

        extract($param);

        ob_start();

        /** @noinspection PhpIncludeInspection */
        require($dir . $view . '.php');

        $html = ob_get_clean();

        echo $html;

    }

    public function renderHeadIncludes()
    {
        echo '<script src="' . webApp::app()->getSite() . 'webApp/assets/js/jquery.min.js"></script>';
        echo '<link rel="stylesheet" href="' . webApp::app()->getSite() . 'webApp/assets/css/bootstrap.min.css" />';
        echo '<script src="' . webApp::app()->getSite() . 'webApp/assets/js/bootstrap.min.js"></script>';
        echo '<link rel="stylesheet" href="' . webApp::app()->getSite() . 'webApp/assets/css/webApp.css" />';

        foreach (self::$css as $css)
            echo '<link rel="stylesheet" href="' . $css . '" />';

        foreach (self::$js as $js)
            echo '<script src="' . $js . '"></script>';

    }

    public function renderInclude($view, $param = array())
    {
        $file = webApp::app()->getDirProtected() . '/views/_includes/' . $view . '.php';

        if (is_array($param))
            extract($param);

        /** @noinspection PhpIncludeInspection */
        require($file);

    }

    public function log($log, $text)
    {
        webApp::app()->log($log, $text);
    }

    public function getMethodUrl($method)
    {
        $prefix = '';
        $class = get_class($this);

        return webApp::app()->getSite() . $prefix . $class . '/' . $method;
    }

    public function returnJson($arr)
    {
        header("Content-type: text/json;charset=utf-8");

        echo json_encode($arr);
    }

}