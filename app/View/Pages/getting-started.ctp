<h2>Getting started</h2>
<div class="well">
    <h3>Before you start</h3>
    <p>Selenize is there to help you <u>test</u> your web project. To be able to use it, you need the following:</p>
    <ul>
        <li>PHPUnit and/or PHPUnit_Selenium tests in the /tests/ directory of your repository</li>
        <li>Test files have names ending in &quot;Test.php&quot;</li>
        <li>A git repository for your project (can be local-only)</li>
    </ul>
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
    <p>
        When you have installed Selenium IDE you can start it (usually via the &quot;Tools&quot; menu)
        Click on the little record button and start browsing like your user would on your site.
    </p>
    <p>Selenium IDE will record every click, every selection, every text you entered and store it to a test case.</p>
    <p>When you're done recording, you hit the record button again to stop. Now you can edit the recorded actions.</p>
    <p>You should validate your Selenium test by running it via the play button.</p>
    <p>
        When you have your Selenium test working, you can export it to PHPUnit_Selenium by choosing 
        &quot;File&quot; &mdash; &quot;Export Testcase as&quot; &mdash; choose PHPUnit_Selenium.
    </p>
    <p>
        Put the testcase files into the &quot;/tests&quot; directory in the root of your git repository and
        make sure their filenames end in &quot;Test.php&quot; (e.g. &quot;myTest.php&quot;)
    </p>
    <div class="alert alert-info">
        <p><strong>Info on the test web- and database-server:</strong></p>
        <p><strong>Webserver:</strong></p>
        <p>Please note that the local test server for your project is available at <strong>http://localhost:8080</strong></p>
        <br />
        <p><strong>Database</strong></p>
        <p>The connection parameters for your test database:</p>
        <ul>
            <li><strong>Username:</strong> Your Selenize account username</li>
            <li><strong>Password:</strong> Your Selenize account password</li>
            <li><strong>Database:</strong> usr_USERNAME, where USERNAME is your username.</li>
        </ul>
        <br />
        <p><strong>Initializing the database:</strong></p>
        <p>Your test database is created upon starting the tests and dropped when tests are completed</p>
        <p>To create your database schema and/or import test data, place an <strong>&quot;init.sql&quot;</strong> file into the tests directory.</p>
    </div>
</div>

<div class="well">
    <h3>Upload and run the tests</h3>
    <p>Now that you have your test files in place, make sure your git repository has the selenize repository as a remote.</p> 
    <div class="alert alert-info">
        <p>If you are unsure if your Selenize repository is set up as a remote, run <pre><code>git remote -v show</code></pre></p>
        <p>If your selenize repository does not show up, use the following command:</p>
        <p><u>Please substitute</u> <strong>USER</strong> with your username and <strong>REPOSITORY</strong> with the name of the repository you created on Selenize in the command below</p>
        <p><pre><code>git remote add selenize http://<strong>USER</strong>@selenize.tk/filestore/users/<strong>USER</strong>/<strong>REPOSITORY</strong></code></pre></p>
    </div>
    <p>To upload your files, you just do a <pre><code>git push selenize</code></pre></p>
    <p>You can now start your tests by <a href="http://www.selenize.tk/users/login">logging in on Selenize</a> and hit "Run tests" in the repository dashboard</p>
</div>

<div class="well">
    <h3>What happened?</h3>
    <p>For security reasons, the system will set up a <strong>safe sandbox</strong> for your repository and starts a <strong>local-only</strong> webserver.</p>
    <p>This may take a few minutes, so you should refresh the dashboard after a few minutes and the status should be updated</p>
    <p>When you see &quot;Running tests&quot; as the status, your tests are running - this will take some time, too.</p>
    <p>After your tests are finished, you will see a status of &quot;Success&quot;, &quot;Failure&quot; or &quot;Aborted&quot;</p>
    <p>
        If you want to find out, what happened, you can click on &quot;History&quot;
        And then click on &quot;Show logs&quot;.
    </p>
    <p>You can also access the logs of all previous test runs here.</p>
</div>
<div class="well">
    <h3>And that&apos;s it!</h3>
    <p>If you have a question, drop me a note via the Github-Issue page or via <a href="https://www.twitter.com/avgp">Twitter</a></p>
    <p>Feedback is very welcome!</p>
</div>