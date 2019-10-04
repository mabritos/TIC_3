<?php

class usuario extends controller
{
    public function index()
    {
        webApp::app()->requireLoginRedir();

        $this->titulo = 'Usuarios';

        $grid = $this->configGrid();

        $this->render("index", array(
            'grid' => $grid
        ));
    }

    private function configGrid()
    {
        $grid = new grid('usuarios');

        $grid
            ->setJsonUrl($this->getMethodUrl('data'))
            ->setUniqueIdFields('id')
            ->setColModel(array(
                array(
                    'name'   => 'usuario',
                    'width'  => 150,
                    'format' => grid::FMT_STRING
                ),
                array(
                    'name'   => 'nombre',
                    'width'  => 200,
                    'format' => grid::FMT_STRING
                ),
                array(
                    'name'   => 'email',
                    'width'  => 200,
                    'format' => grid::FMT_STRING
                ),
                array(
                    'name'          => 'role',
                    'width'         => 150,
                    'format'        => grid::FMT_SELECT,
                    'formatoptions' => array('value' => $this->getRoleDescription())
                ),
                array(
                    'name'   => 'ultimoLogin',
                    'label'  => 'Última sesión',
                    'format' => grid::FMT_DATETIME
                )
            ))
            ->setDefaultSortName('usuario')
            ->setDefaultSortOrder('asc')
            ->setActions(array(
                new gridActionButton(gridActionButton::ADD, webApp::app()->getSite() . 'usuario/add'),
                new gridActionButton(gridActionButton::EDIT, webApp::app()->getSite() . 'usuario/edit'),
                new gridActionButton(gridActionButton::MULTI_DELETE, webApp::app()->getSite() . 'usuario/delete')
            ));

        return $grid;
    }

    private function getRoleDescription($role = null)
    {
        $arr = array(
            webApp::ROLE_ADMIN  => 'Administrador',
            webApp::ROLE_EDITOR => 'Editor',
            webApp::ROLE_USER   => 'Usuario'
        );

        if ($role == null)
            return $arr;
        elseif (isset($arr[$role]))
            return $arr[$role];

        return false;
    }

    public function add()
    {
        webApp::app()->requireLogin();

        $form = new webApp_form('usuario', array(

            new webApp_column(array(

                new webApp_input('usuario', array(
                    'rules' => array(
                        'required' => true
                    )
                )),

                new webApp_input('email', array(
                    'rules' => array(
                        'required' => true,
                        'email'    => true
                    )
                )),

                new webApp_password('pass', array(
                    'label' => 'Contraseña',
                    'rules' => array(
                        'required' => true
                    )
                )),

                new webApp_input('nombre', array(
                    'rules' => array(
                        'required' => true
                    )
                )),

                new webApp_select('role', $this->getRoleDescription())

            ))

        ), array(
            'action' => webApp::app()->getSite() . 'usuario/add',
            'ajax'   => true,
            'gridId' => "usuarios"
        ));

        if (isset($_POST['usuario'])) {

            $form->setAtributes($_POST['usuario']);

            if ($form->validate()) {

                $param = $form->getAtributes();

                $param['pass'] = md5($param['pass']);

                webApp::app()->db()->insert('usuarios', $param);

                $this->returnJson(array(
                    'error' => 0
                ));

            }

        } else {

            echo $form->render();

        }

    }

    public function edit()
    {
        webApp::app()->requireLogin();

        $usuario = webApp::app()->db()->queryRow('SELECT id, usuario, email, nombre, role FROM usuarios WHERE id = :id', array(
            'id' => isset($_POST['usuario']['id']) ? $_POST['usuario']['id'] : $_POST['id']
        ));

        if ($usuario) {

            $form = new webApp_form('usuario', array(

                new webApp_column(array(

                    new webApp_hidden('id'),

                    new webApp_input('email', array(
                        'rules' => array(
                            'required' => true,
                            'email'    => true
                        )
                    )),

                    new webApp_password('pass', array(
                        'label'       => 'Contraseña',
                        'htmlOptions' => array(
                            'placeholder' => 'dejar vacío para no cambiar la contraseña'
                        )
                    )),

                    new webApp_input('nombre', array(
                        'rules' => array(
                            'required' => true
                        )
                    )),

                    new webApp_select('role', $this->getRoleDescription(), $usuario->usuario == 'admin' ? array('htmlOptions' => array('disabled' => 'disabled')) : null)

                ))

            ), array(
                'action' => webApp::app()->getSite() . 'usuario/edit',
                'ajax'   => true,
                'gridId' => "usuarios"
            ));

            if (isset($_POST['usuario'])) {

                $form->setAtributes($_POST['usuario']);

                if ($form->validate()) {

                    $param = $form->getAtributes();

                    if (!empty($param['pass']))
                        $param['pass'] = md5($param['pass']);
                    else
                        unset($param['pass']);

                    webApp::app()->db()->update('usuarios', $param,
                        array(
                            'id' => $param['id']
                        )
                    );

                    $this->returnJson(array(
                        'error' => 0
                    ));

                }

            }else{

                $form->setAtributes($usuario);

                echo $form->render();

            }

        }

    }

    public function delete()
    {
        webApp::app()->requireLogin();

        if (isset($_POST['id'])) {

            $db = webApp::app()->db();

            $usuario = $db->queryRow('SELECT * FROM usuarios WHERE id IN(:ids) AND usuario = "admin"', array(
                'ids' => implode(',', $_POST['id'])
            ));

            if (!$usuario) {

                $sessions = $db->queryRow('SELECT * FROM sessions WHERE usuario_id IN(:ids) LIMIT 1', array(
                    'ids' => implode(',', $_POST['id'])
                ));

                if (!$sessions) {

                    $db->query('DELETE FROM usuarios WHERE id IN(:ids)', array(
                        'ids' => implode(',', $_POST['id'])
                    ));

                    $this->returnJson(array(
                        'error' => 0
                    ));

                }else{

                    $this->returnJson(array(
                        'error'   => 1,
                        'mensaje' => 'Imposible eliminar, al menos uno de los usuarios seleccionados tiene conversaciones registradas.'
                    ));

                }

            }else{

                $this->returnJson(array(
                    'error'   => 1,
                    'mensaje' => 'No se permite eliminar el usuario admin.'
                ));

            }

        }

    }

    public function data()
    {
        webApp::app()->requireLogin();

        $grid = $this->configGrid();

        $grid->renderData(webApp::app()->db(), "SELECT * FROM usuarios");
    }

    public function miperfil()
    {
        webApp::app()->requireLoginRedir();

        $this->titulo = 'Mi perfil';

        $form = new webApp_form('usuario', array(

            new webApp_column(array(

                new webApp_input('email', array(
                    'rules' => array(
                        'required' => true,
                        'email'    => true
                    )
                )),

                new webApp_password('pass', array(
                    'label'       => 'Contraseña',
                    'htmlOptions' => array(
                        'placeholder' => 'dejar vacío para no cambiar la contraseña'
                    )
                )),

                new webApp_input('nombre', array(
                    'rules' => array(
                        'required' => true
                    )
                ))

            ))

        ));

        if (isset($_POST['usuario'])) {

            $form->setAtributes($_POST['usuario']);

            if ($form->validate()) {

                $param = $form->getAtributes();

                if (!empty($param['pass']))
                    $param['pass'] = md5($param['pass']);
                else
                    unset($param['pass']);

                webApp::app()->db()->update('usuarios', $param,
                    array(
                        'id' => webApp::app()->getUsuarioId()
                    )
                );

                webApp::notify('Sus datos se actualizaron correctamente', notificacion::SUCCESS);

            }

        } else {

            $usuario = webApp::app()->db()->queryRow('SELECT email, nombre FROM usuarios WHERE id = :id', array(
                'id' => webApp::app()->getUsuarioId()
            ));

            $form->setAtributes($usuario);

        }

        $this->render("miperfil", array(
            'form' => $form
        ));
    }

}