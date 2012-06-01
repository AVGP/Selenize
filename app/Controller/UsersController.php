<?php
App::uses('Sanitize', 'Utility');

class UsersController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->logoutRedirect = array(Configure::read('Routing.admin') => false, 'controller' => 'pages', 'action' => 'home');
        $this->Auth->allow('add', 'login', 'logout');
    }
    
    public function add() {
        if($this->request->is('post')) {
            $this->User->create();
            if($this->User->save($this->request->data)) {
                
                $db = &ConnectionManager::getDataSource('default');
                $usr = Sanitize::clean($this->request->data['User']['username'], array('encode' => false));
                $rawPassword = $this->request->data['User']['password'];
                $db->query('CREATE USER usr_' . $usr . '@localhost IDENTIFIED BY "' . Sanitize::clean($rawPassword, array('encode' => false))  . '"');
                $db->query('GRANT ALL PRIVILEGES ON `usr\_' . $usr . '`.* TO \'usr_' . $usr . '\'@localhost');
                
                $homepath = '/var/www/Selenize/app/webroot/filestore/users/' . $this->request->data['User']['username'];
                mkdir($homepath); //Without this, realpath will screw us!
                if(strpos(realpath($homepath), '/var/www/Selenize/app/webroot/filestore/users/' ) !== 0) {
                    rmdir($homepath); //This is safe. because it fails for non-empty dirs.
                    $this->Session->setFlash('That username is not allowed.','default', array('class' => 'alert alert-error'));
                    return false;
                }
                
                $username = escapeshellarg($this->request->data['User']['username']);
                $password = escapeshellarg($this->request->data['User']['password']);
                exec('htpasswd -b -c ' . escapeshellarg($homepath . '/.htpasswd'). ' ' . $username . ' ' . $password);
                file_put_contents($homepath . '/.htaccess', "AuthType Basic\nAuthName Git\nAuthUserFile " . $homepath . '/.htpasswd' . "\nRequire valid-user\nAllow from all");
                
                $this->Session->setFlash('Yay! Signup was successful. Welcome on board!', 'default', array('class' => 'alert alert-success'));
                $this->redirect($this->Auth->redirect());                            
            }
            else {
                $this->Session->setFlash('User could not be created. That makes us very sad.', 'default', array('class' => 'alert alert-error'));
            }
        }        
    }
        
    public function login() {
        if($this->request->is('post')) {
            if($this->Auth->login()) {
                $this->Session->setFlash('Hey there. Good you\'re back :)', 'default', array('class' => 'alert alert-success'));
                $this->redirect('/repositories/');
            }
            else {
                $this->Session->setFlash('No. That was wrong - sorry.', 'default', array('class' => 'alert alert-error'));
            }
        }        
    }
    
    public function logout() {
        $this->redirect($this->Auth->logout());
    }
}