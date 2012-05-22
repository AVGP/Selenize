<?php

class RepositoriesController extends AppController {
    public $uses = array('Repository', 'Queue.Job');
    public $tube = 'default';
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');
    }    
    
    public function add() {
        if($this->request->is('post')) {
            $this->Repository->create();
            if($this->Repository->save($this->request->data)) {
                $homepath = '/var/www/Selenize/app/webroot/filestore/users/' . $this->Auth->user('username') . '/';
                $repopath = $homepath . $this->request->data['Repository']['name'] . '/';
                
                mkdir($repopath);
                exec('cd ' . $repopath . ' && git init --bare && git update-server-info');
                
                $this->Session->setFlash('Repository created :)');
                $this->redirect('/users/dashboard');
            }
            else {
                $this->Session->setFlash('Uh oh! Something went wrong!');
            }
        }
        else $this->set('user_id', $this->Auth->user('id'));
    }
    
    public function test($id) {
        $id = intval($id);
        $this->Repository->id = $id;
        $this->Repository->Testdrive->create();
        $this->Repository->Testdrive->save(array('Testdrive' => array(
            'repository_id' => $id,
            'logtext' => '',
            'result' => 'running',
            'created' => date('Y-m-d H:i:s')
        )));
        $chrootTpl = file_get_contents('/var/www/Selenize/app/webroot/filestore/templates/temp_chroot');
        $chrootTpl = str_replace('PATH', '/tmp/chroot_' . $id, $chrootTpl);
        file_put_contents('/tmp/chroot_conf_' . $id, $chrootTpl);
        
        //making the chroot jail
        $chrootPath = '/tmp/chroot_' . $id;
        mkdir($chrootPath);
        $this->Job->put(array('body' => array('repo_id' => $id, 'testdrive_id' => $this->Repository->Testdrive->id)));
        $this->Session->setFlash('Testdrive started. Reload this page to see the progress');
        $this->redirect('/repositories/');
    }    
    
    public function index() {
        $repositories = $this->Repository->find('all', array('conditions' => array( 'user_id' => $this->Auth->user('id'))));
        $this->set('repositories', $repositories);
    }    
}