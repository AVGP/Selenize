<h2>Getting started</h2>
<div class="well">
    <h3>Before you start</h3>
    <p>Selenize is there to help you <u>test</u> your web project. To be able to use it, you need the following:</p>
    <ul>
        <li>A publicly available web server where your tests should be run</li>
        <li>PHPUnit-based Selenium tests in the /tests/ directory of your repository</li>
        <li>test files names ending in &quot;Test.php&quot;</li>
        <li>A git repository for your project (can be local)</li>
    </ul>
    <div class="alert alert-info">
        <h3 class="alert-heading">Info</h3>
        In future versions, you will get a database and webspace from Selenize for testing purposes.
    </div>
</div>

<div class="well">
    <h3>Selenium 101</h3>
    <p>
        <a href="http://www.seleniumhq.com">Selenium</a> is a powerful testing tool to automate testing in various browsers.
        It allows you to simulate a user on your website doing all the tasks your real user would do and you want to test.
        For example you can automate the sign up process of a user with Selenium and replay it over and over again, without having to do it all by yourself.
        With the PHP Formatters and PHPUnit/PHPUnit_Selenium you can run them automatically from phpunit.
    </p>
    <p>
        The easiest way to start using Selenium is installing <a href="http://release.seleniumhq.org/selenium-ide/1.8.0/selenium-ide-1.8.0.xpi">Selenium-IDE</a>
        in your browser and the <a href="https://addons.mozilla.org/en-US/firefox/addon/selenium-ide-php-formatters/">PHP Formatters</a>.
    </p>    
    
    <div class="alert alert-info">
        <h3>Optional: Set up PHPUnit and PHPUnit_Selenium locally</h3>
        <p>The PHP side requires having PHPUnit and PHPUnit_Selenium to be installed. You can use pear to install them:</p>
        <pre><code>
            pear config-set auto_discover 1
            pear upgrade
            pear channel-discover pear.phpunit.de
            pear install phpunit/PHPUnit
            pear install --all-deps phpunit/PHPUnit_Selenium
            </code></pre>
        <p>If your installation was successul, you should be abled to run "phpunit --version" and get some information.</p>
        <p>Selenize requires your test file to be stored in the "tests/" directory in your project root path. Create this directory, if you haven&apos;t done yet.</p>
    </div>
    <p>Enough of an intro, let's get our hands on it</p>
</div>

<div class="well">
    <h3>Creating tests for your project</h3>
    <p>Coming soon.</p>
</div>

<div class="well">
    <h3>Run the tests</h3>
    <p>Coming soon.</p>
</div>

<div class="well">
    <h3>What happened?</h3>
    <p>Coming soon.</p>
</div>