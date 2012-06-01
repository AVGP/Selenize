<ul class="nav nav-pills nav-stacked">
    <li><a href="/">Home</a></li>
    <li><a href="/pages/learn-more">Learn more</a></li>
    <li><a href="/pages/getting-started">Get started</a></li>
    <?php if(!is_array($this->Session->read('Auth.User'))): ?>
        <li><a href="/users/login">Login</a></li>
        <li><a href="/users/add">Signup</a></li>
    <?php else: ?>
        <li><a href="/users/logout">Logout</a></li>    
    <?php endif; ?>
    <li><a href="http://www.github.com/AVGP/Selenize" target="_blank">Selenize on GitHub</a></li>
</ul>