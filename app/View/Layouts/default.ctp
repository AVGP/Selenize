<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
        Selenize
		<?php if(!empty($title_for_layout)) echo ' - ' . $title_for_layout; ?>
	</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <?php
		echo $this->Html->css('bootstrap.min.css');
    	echo $this->Html->css('main.css');
        
        echo $this->Html->script('bootstrap.min.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    <link href='http://fonts.googleapis.com/css?family=Cuprum:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
    <div id="wrap">
    	<div id="container" class="container-fluid">
	    	<div id="header" class="row-fluid">
		    	<h1 class="row-fluid">Selenize</h1>
		    </div>
		    <div id="content" class="row-fluid">
                <div class="span2"><?php echo $this->element('navigation'); ?></div>
			    <div class="span10">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->fetch('content'); ?>                    
                </div>
		    </div>
		</div>
	    <div id="footer" class="row-fluid">
            <div class="span12">
            <small>&copy; 2012 by Geekonaut</small>
		    <?php echo $this->Html->link(
				$this->Html->image('cake.power.gif', array('alt' => 'CakePHP', 'border' => '0')),
				'http://www.cakephp.org/',
				array('target' => '_blank', 'escape' => false)
			);
		    ?>
        </div>
	</div>
    <a href="https://github.com/AVGP/Selenize" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png" alt="Fork me on GitHub"></a>    
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>
