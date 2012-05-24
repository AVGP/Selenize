# Selenize

## Installation

### Prerequisites:

*   makejail
*   chroot
*   Beanstalkd
*   PHP (cli & apache - incl. curl extension)
*   mod_rewrite, mod_dav & mod_dav_fs
*   pear
*   phpunit & PHPUnit_Selenium
*   git
*   Xvfb
*   java JRE
*   selenium-server-standalone
*   sudo

### Step 1: Setup the database

Create a new user "selenize" and a database with the same name.
Afterwards, set your chosen password for the user in app/Config/database.php.
To setup the initial database schema, import app/Config/Schema/init.sql into your newly created database.

### Step 2: Setup the vhost

1.  Activate mod_rewrite, mod_dav and mod_dav_fs by running `a2enmod rewrite && a2enmod dav_fs`
2.  Activating dav_fs will usually activate dav, too.
3.  Copy the Selenize/app/Config/selenize_vhost to your /etc/apache2/sites-enabled/ and adjust ServerName and ServerAlias.
4.  Make sure the Selenize/app/webroot/filestore/users directory exists.
5.  Restart Apache.

### Step 3: Setup Beanstalkd

After installing Beanstalkd via apt, you just have to uncomment the last line in /etc/default/beanstalkd and run `/etc/init.d/beanstalkd start`
Setup the CakePHP-Plugin by running `git submodule init --update`.

### Step 4: Install PHPUnit and PHPUnit_Selenium
Run the following
    pear upgrade
    pear config-set auto_discover 1
    pear channel-discover pear.phpunit.de
    pear install phpunit/PHPUnit
    pear install phpunit/PHPUnit_Selenium

### Step 4: Setup the environment

1.  Create a user "selenium", cd into /home/selenium and get selenium-server with `wget http://selenium.googlecode.com/files/selenium-server-standalone-2.21.0.jar`
2.  Copy Selenize/Xvfb and Selenize/selenium to /etc/init.d.
3.  Copy Selenize/selenium-server.sh to /usr/bin/selenium-server.sh
4.  Edit sudoers to allow www-data to run /etc/mk_user_jail and chroot: `www-data localhost=(ALL) NOPASSWD:/etc/mk_user_jail.sh,/usr/sbin/chroot`
5.  Start the daemons by running `/etc/init.d/Xvfb start && /etc/init.d/selenium start`
6.  Wait a moment and check if Xvfb and java is running (Opening Ports 6099 and 4444)
7.  Update rc.d with `update-rc.d Xvfb defaults && update-rc.d selenium defaults` - ignore the warnings.
9.  Create a cronjob for the create_env console: @reboot cd /var/www/Selenize/app;nohup Console/cake create_env &> console.log
