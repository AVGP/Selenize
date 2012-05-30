<?php

App::uses('Sanitize', 'Utility');

class CreateEnvShell extends AppShell {
    public $uses = array('Queue.Job', 'Repository', 'Testdrive');
    public $tubes = array('default');
    public function main() {
        while(true) {
    		$job = $this->Job->reserve(array('tube' => $this->tubes));
            $repo_id = $job['Job']['body']['repo_id'];
            $testdrive_id = $job['Job']['body']['testdrive_id'];

            $this->Testdrive->id = $testdrive_id;
            $this->Testdrive->set('result', 'Setting up: Sandbox');
            $this->Testdrive->save();


            //Create a chroot jail
            $chrootPath = $this->setupChroot($repo_id);


            //Clone repository and create testrunner
            $repo = $this->Repository->findById($repo_id);
            $this->setupRepository($chrootPath, $repo);
            $this->setupTestrunner($chrootPath, $repo);

            //Setup the test webserver
            $this->setupWebserver($chrootPath, $repo);

            //Setup database
            $this->setupDatabase();
                        
            //Running tests
            $this->runTests($repo_id);
            
            //Teardown
            $this->Job->delete();
            exec('rm -rf ' . $chrootPath);
            exec('killall lighttpd && rm /tmp/server.conf');
            $this->out('Done.');
        }
    }

    /**
    * Sets up the chroot and returns the chroot path
    */
    protected function setupChroot($repo_id) {
        $this->out('Creating Environment #' . $repo_id);
        exec('sudo /etc/mk_user_jail.sh ' . $repo_id);
        $this->out('Created Environment #' . $repo_id);
        return '/tmp/chroot_' . $repo_id;
    }
    
    protected function setupRepository($chrootPath ,$repo) {
        $this->Testdrive->set('result', 'Setting up: Repository');
        $this->Testdrive->save();
        $repoPath = '/var/www/Selenize/app/webroot/filestore/users/' . $repo['User']['username'] . '/' . $repo['Repository']['name'];
        exec('cd ' . $chrootPath . ' && git clone ' . $repoPath . ' > ' . $chrootPath . '/git.log');  
    }
    
    protected function setupTestrunner($chrootPath, $repo) {
        $testrunnerTpl = file_get_contents('/var/www/Selenize/app/webroot/filestore/templates/test_runner.sh');
        $testrunnerTpl = str_replace('TESTPATH','/' . $repo['Repository']['name'].'/tests/', $testrunnerTpl);
        file_put_contents($chrootPath . '/test_runner.sh', $testrunnerTpl);
        exec('chmod a+x ' . $chrootPath . '/test_runner.sh');       
    }
    
    protected function setupWebserver($chrootPath, $repo) {
        $this->Testdrive->set('result', 'Setting up Testserver');
        $this->Testdrive->save();            
        $serverTpl = file_get_contents('/var/www/Selenize/app/webroot/filestore/templates/lighttpd.conf');
        $serverTpl = str_replace('DOCROOT', $chrootPath . '/' . $repo['Repository']['name'], $serverTpl);
        file_put_contents('/tmp/server.conf', $serverTpl);
        exec('nohup lighttpd -f /tmp/server.conf &');        
    }
    
    protected setupDatabase($repo) {
        $this->Testdrive->set('result', 'Setting up Database');
        $this->Testdrive->save();
        
        $prefixedUser = $repo['User']['username'];
        $db->query('CREATE DATABASE ' .Sanitize::clean($prefixedUser, array('encode' => false)));
        $db->query('GRANT ALL ON ' . Sanitize::clean($prefixedUser, array('encode' => false)) . '.* TO ' . Sanitize::clean($prefixedUser, array('encode' => false)) . '@localhost');
    }
    
    protected function runTests($repo_id) {
        $this->Testdrive->set('result', 'Running tests');
        $this->Testdrive->save();
            
        exec('sudo chroot ' . $chrootPath . ' /test_runner.sh');
        $this->out('Tests finished.');
        $this->Testdrive->set('logtext', file_get_contents($chrootPath . '/result.log'));
        $lines = file($chrootPath . '/result.log');
        if(count($lines) > 1) {
            if(substr($lines[count($lines)-1],0,2) == 'OK')
                $this->Testdrive->set('result', 'Success');
            else
                $this->Testdrive->set('result', 'Failure');
        } else
            $this->Testdrive->set('result', 'Aborted');
        $this->Testdrive->save();        
    }
}