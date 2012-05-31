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
                
                $this->Session->setFlash('Repository created :)', 'default', array('class' => 'alert alert-success'));
                $this->redirect('/repositories');
            }
            else {
                $this->Session->setFlash('Uh oh! Something went wrong!', 'default', array('class' => 'alert alert-error'));
            }
        }
        else $this->set('user_id', $this->Auth->user('id'));
    }
    
    public function test($id) {
        $id = intval($id);
        $this->Repository->id = $id;
        $repo = $this->Repository->findById($id);
        
        if($repo['User']['id'] != $this->Auth->user('id'))
            $this->denyAccess();
        
        $this->Repository->Testdrive->create();
        $this->Repository->Testdrive->save(array('Testdrive' => array(
            'repository_id' => $id,
            'logtext' => '',
            'result' => 'Starting',
            'created' => date('Y-m-d H:i:s')
        )));
        $chrootTpl = file_get_contents('/var/www/Selenize/app/webroot/filestore/templates/temp_chroot');
        $chrootTpl = str_replace('PATH', '/tmp/chroot_' . $id, $chrootTpl);
        file_put_contents('/tmp/chroot_conf_' . $id, $chrootTpl);
        
        //making the chroot jail
        $this->Job->put(array('body' => array('repo_id' => $id, 'testdrive_id' => $this->Repository->Testdrive->id)));
        $this->Session->setFlash('Testdrive started. Running the tests will take a few minutes. Reload this page from time to time to see the progress', 'default', array('class' => 'alert alert-info'));
        $this->redirect('/repositories/');
    }    
    
    public function index() {
        $repositories = $this->Repository->find('all', array('conditions' => array( 'user_id' => $this->Auth->user('id'))));
        $this->set('repositories', $repositories);
    }    
    
    public function show($id = null) {
        $repository = $this->Repository->findById($id);
        if($repository === null || $repository['User']['id'] != $this->Auth->user('id'))
            $this->denyAccess();
        $this->set('repository', $repository);
    }
    
    public function denyAccess($msg = 'You are not allowed to access this repository') {
            $this->Session->setFlash($msg, 'default', array('class' => 'alert alert-error'));
            $this->redirect('/repositories');        
    }
}
