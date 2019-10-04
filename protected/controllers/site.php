<?php

class site extends Controller
{
    public function index()
    {
        webApp::app()->requireLoginRedir();

        $this->titulo = '';

        $this->render('index');
    }

    public function login()
    {
        if (webApp::app()->isLoggedIn())

            header("Location: " . SITIO);

        else{

            if (isset($_POST["login"])) {

                $form = $this->constructLoginForm();

                $form->setAtributes($_POST['login']);

                if ($form->validate()) {

                    $param = $form->getAtributes();

                    if (webApp::app()->login($param["usuario"], $param["contrasena"])) {

                        header("Location: " . SITIO);

                        return;

                    }

                }

            }

            $this->titulo = 'Iniciar sesión';

            $this->addCss(SITIO . 'assets/css/login.css');

            $this->render('login', array(
                'loginForm' => $this->constructLoginForm()
            ));

        }

    }

    private function constructLoginForm()
    {
        return new webApp_form('login',
            array(
                new webApp_column(array(
                    new webApp_input('usuario'),
                    new webApp_password('contrasena', array(
                        'label' => 'Contraseña',
                    ))
                ))
            ),
            array(
                'buttons' => array(
                    new webApp_button('login', webApp_button::SUBMIT, webApp_button::PRIMARY, array(
                        'label' => 'Iniciar sesión'
                    ))
                )
            )
        );
    }

    public function logout()
    {
        webApp::app()->logout();

        header("Location: " . SITIO . 'site/login');
    }

}