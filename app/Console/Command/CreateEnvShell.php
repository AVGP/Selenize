<?php

class CreateEnvShell extends AppShell {
    public $uses = array('Queue.Job', 'Repository', 'Testdrive');
    public $tubes = array('default');
    public function main() {
        while(true) {
    		$job = $this->Job->reserve(array('tube' => $this->tubes));
            $repo_id = $job['Job']['body']['repo_id'];
            $testdrive_id = $job['Job']['body']['testdrive_id'];

            $this->Testdrive->id = $testdrive_id;
            $this->Testdrive->set('result', 'Setting up');
            $this->Testdrive->save();

            //Create a chroot jail
            exec('sudo /etc/mk_user_jail.sh ' . $repo_id);
            $chrootPath = '/tmp/chroot_' . $repo_id;

            //Clone repository and create testrunner
            $repo = $this->Repository->findById($repo_id);
            $repoPath = '/var/www/Selenize/app/webroot/filestore/users/' . $repo['User']['username'] . '/' . $repo['Repository']['name'];
            exec('cd ' . $chrootPath . ' && git clone ' . $repoPath . ' > ' . $chrootPath . '/git.log');                
            $testrunnerTpl = file_get_contents('/var/www/Selenize/app/webroot/filestore/templates/test_runner.sh');
            $testrunnerTpl = str_replace('TESTPATH','/' . $repo['Repository']['name'].'/tests/', $testrunnerTpl);
            file_put_contents($chrootPath . '/test_runner.sh', $testrunnerTpl);
            exec('chmod a+x ' . $chrootPath . '/test_runner.sh');

            $this->Testdrive->set('result', 'Running');
            $this->Testdrive->save();
            $this->out('Created Environment #' . $repo_id);
            
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
            $this->Job->delete();
            exec('rm -rf ' . $chrootPath);
        }
    }
}