# Selenize

## Installation

### Prerequisites:

*   makejail
*   chroot
*   Beanstalkd
*   PHP (cli & apache)
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

Activate mod_rewrite, mod_dav and mod_dav_fs by running `a2enmod rewrite && a2enmod dav_fs`
Activating dav_fs will usually activate dav, too.

### Step 3: Setup the environment

1.  Make sure the Selenize/app/webroot/filestore/users directory exists.
2.  Create a user "selenium", cd into /home/selenium and get selenium-server with `wget http://selenium.googlecode.com/files/selenium-server-standalone-2.21.0.jar`
3.  Copy Selenize/Xvfb and Selenize/selenium to /etc/init.d.
4.  Copy Selenize/selenium-server.sh to /usr/bin/selenium-server.sh
5.  Edit sudoers to allow www-data to run /etc/mk_user_jail and chroot:
    www-data localhost=(ALL) NOPASSWD:/etc/mk_user_jail.sh,/usr/sbin/chroot 
6.  Start the daemons by running `/etc/init.d/Xvfb start && /etc/init.d/selenium start
7.  Wait a moment and check if Xvfb and java is running (Opening Ports 6099 and 4444)

