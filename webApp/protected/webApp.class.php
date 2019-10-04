<?php

class webApp
{
    const
        COOKIE_LOGIN_TIME = 315360000,   // forever!
        ROLE_ADMIN        = "A",
        ROLE_EDITOR       = "E",
        ROLE_USER         = "U"
    ;

    const
        LOG_GENERAL         = 'general.log',
        LOG_CHATAPI         = 'chatapi.log',
        LOG_FBMESSENGER     = 'fbmessenger.log',
        LOG_WEBCHAT         = 'webchat.log',
        LOG_TWILIO_WHATSAPP = 'twilio_whatsapp.log',
        LOG_ERROR           = 'error.log',
        LOG_AGENT           = 'agent.log'
    ;

    private static
        $instance,
        $site,
        $notifications
    ;

    private
        $debugFileHandle,
        $dirs,
        $urls
    ;

    public function __construct($site)
    {
        $this->dirs = array(
            'protected' => (__DIR__ . '/../../protected'),
        );

        $this->urls = array(
            'assets' => $site . 'assets/',
            'login'  => $site . 'admin/login'
        );

        $this->setAutoloadClasses();

        self::$site = $site;

        session_start();

        if ($this->isLoggedIn()) {

            self::$notifications = isset($_SESSION["notifications"]) ? $_SESSION["notifications"] : array();

        }else

            self::$notifications = array();
    }

    public static function app()
    {
        if (self::$instance == null)
            self::$instance = new self(self::$site);

        return self::$instance;
    }

    public function run($url = null)
    {
        if ($url == null)
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $u = str_replace(self::$site, '', $url);
        $q = explode('?', $u);

        if (isset($q[0]) and !empty($q[0])) {

            $f = explode('/', $q[0]);

            $class  = (isset($f[0]) and !empty($f[0])) ? $f[0] : 'site';
            $method = (isset($f[1]) and !empty($f[1])) ? $f[1] : 'index';

            if ($class == 'site' and $method == 'index') {

                header("HTTP/1.0 404 Not Found");

                return;

            }

        }else{

            $class    = 'site';
            $method   = 'index';

        }

        $params = array();

        if (isset($q[1])) {

            $par = explode('&', $q[1]);

            foreach ($par as $p) {

                $a = explode('=', $p);

                if (isset($a[0]) and isset($a[1]))
                    $params[$a[0]] = $a[1];

            }

        }

        $dir = $this->getDirProtected() . '/controllers/';

        if (!empty($class) and file_exists($dir . $class . ".php")) {

            /** @noinspection PhpIncludeInspection */
            require_once($dir . $class . ".php");

            $titulo = $class . ' | ' . $method;

            $o = new $class($titulo);

            if (method_exists($o, $method)) {

                if (!empty($params))
                    $o->$method($params);
                else
                    $o->$method();

            }else{

                header("HTTP/1.0 404 Not Found");

                return;

            }

        }else{

            header("HTTP/1.0 404 Not Found");

            return;

        }

    }

    public function db()
    {
        static $dbGeneral;

        if (empty($dbGeneral))
            return new myDB(DB_SERVER, DB_USER, DB_PASS, DB_DB);
        else
            return $dbGeneral;

    }

    public function log($log, $text)
    {
        $dir = $this->getDirProtected() . '/logs';

        if (!file_exists($dir))
            mkdir($dir);

        file_put_contents($dir . '/' . $log, date('Y-m-d H:i:s') . ' - ' . get_class($this) . ' - ' . $text . PHP_EOL, FILE_APPEND);

    }

    public function startDebug() {

        if (defined('DEBUG') and DEBUG) {

            $dir = $this->getDirProtected() . '/logs';

            if (!file_exists($dir))
                mkdir($dir);

            $file = $dir . '/debug.log';

            $this->debugFileHandle = @fopen($file,'a+');

            if(!$this->debugFileHandle)
                die('Cannot open '.$file.' for writing!');

            ob_start(array($this, 'debugOutputHandler'));

        }

    }

    public function endDebug() {

        @ob_end_flush();

        if ($this->debugFileHandle)
            fclose($this->debugFileHandle);

        $this->debugFileHandle = null;

    }

    public function debugOutputHandler($buffer)
    {
        fwrite($this->debugFileHandle, $buffer);
    }

    public function login($user, $pass)
    {
        if (!empty($user) and !empty($pass)) {

            $usuario = $this->db()->queryRow('SELECT * FROM usuarios WHERE usuario = :usuario', array(
                'usuario' => $user
            ));

            if (md5($pass) == $usuario->pass) {

                $this->doLogin($usuario);

                return true;

            }

        }

        return false;

    }

    public function logout()
    {
        if ($this->isLoggedIn()) {

            $this->db()->query('UPDATE usuarios SET token = :token WHERE id = :id', array(
                'token' => '',
                'id'    => $_SESSION["usuarioId"]
            ));

        }

        setcookie("user_token", null, -1, '/');

        session_start();
        session_destroy();

    }

    public function isLoggedIn()
    {
        session_start();

        if (isset($_SESSION["autenticado"]) and $_SESSION["autenticado"] === true)

            return true;

        else{

            if (!isset($_COOKIE["user_token"]) and isset($_POST["ut"]))
                $_COOKIE["user_token"] = $_POST['ut'];

            if (isset($_COOKIE["user_token"])) {

                $usuario = $this->db()->queryRow('SELECT * FROM usuarios WHERE token = :token', array(
                    'token' => $_COOKIE["user_token"]
                ));

                if ($usuario) {

                    $this->doLogin($usuario);

                    return true;

                }

            }

        }

        return false;

    }

    public function requireLogin($role = null)
    {
        if (!$this->isLoggedIn() or ($role != null and !$this->validateRole($role))) {

            $this->logout();

            exit;

        }

    }

    public function requireLoginRedir($role = null)
    {
        if (!$this->isLoggedIn() or ($role != null and !$this->validateRole($role))) {

            $this->logout();

            header("Location: " . $this->urls['login']);

            exit;

        }

    }

    public function validateRole($roles)
    {
        if (!is_array($roles))
            $roles = array($roles);

        foreach ($roles as $r) {

            if ($this->getRole() == $r)
                return true;

        }

        return false;

    }

    public function getSite()
    {
        return self::$site;
    }

    public function getUsuarioId()
    {
        if (isset($_SESSION["usuarioId"]))
            return $_SESSION["usuarioId"];
        else
            return false;
    }

    public function getUsuario()
    {
        if (isset($_SESSION["usuario"]))
            return $_SESSION["usuario"];
        else
            return false;
    }

    public function getRole()
    {
        if (isset($_SESSION["usuarioRole"]))
            return $_SESSION["usuarioRole"];
        else
            return false;
    }

    public static function notify($text, $type = notificacion::INFO)
    {
        self::$notifications[] = new notificacion($text, $type);

        $_SESSION['notifications'] = self::$notifications;
    }

    public static function getNotifications($reset = true)
    {
        if ($reset)
            $_SESSION["notifications"] = array();

        return self::$notifications;
    }

    private function setAutoloadClasses()
    {
        spl_autoload_register(function($class) {

            $file = $this->getDirProtected() . '/models/' . $class . '.php';

            if (file_exists($file)) {
                /** @noinspection PhpIncludeInspection */
                require($file);
            }

        });

    }

    private function doLogin($usuario)
    {
        $token = $this->genToken($usuario->usuario);

        $this->db()->query('UPDATE usuarios SET token = :token, ultimoLogin = :ultimoLogin WHERE id = :id', array(
            'token'       => $token,
            'ultimoLogin' => date("Y-m-d H:i:s"),
            'id'          => $usuario->id
        ));

        session_start();

        $_SESSION["autenticado"] = true;
        $_SESSION["usuarioId"]   = $usuario->id;
        $_SESSION["usuario"]     = $usuario->usuario;
        $_SESSION["usuarioRole"] = $usuario->role;

        setcookie("user_token", $token  , time() + $this::COOKIE_LOGIN_TIME, '/');

    }

    private function genToken($usuario)
    {
        $token = sha1($usuario . "dsf9dsaf8u'sadfu0'q39wu4jq" . time() . rand(111111, 999999));

        $usr = $this->db()->queryRow('SELECT * FROM usuarios WHERE token = :token', array(
            'token' => $token
        ));

        if ($usr)
            return $this->genToken($usuario);
        else
            return $token;

    }

    public function getUrlAssets()
    {
        return $this->urls['assets'];
    }

    public function getDirProtected()
    {
        return $this->dirs['protected'];
    }

}

class notificacion {

    const
        SUCCESS = "S",
        ERROR   = "E",
        WARNING = "W",
        INFO    = "I"
    ;

    public
        $text,
        $type
    ;

    public function __construct($text, $type)
    {
        $this->text = $text;
        $this->type = $type;
    }

}