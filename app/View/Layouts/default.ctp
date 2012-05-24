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
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap.min.css');
    	echo $this->Html->css('main.css');
        
        echo $this->Html->script('bootstrap.min.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container" class="container">
		<div id="header" class="row">
			<h1 class="row">Selenize</h1>
    		<div class="row"><div class="span8 offset2"><?php echo $this->Session->flash(); ?></div></div>
		</div>
		<div id="content" class="row">
            <div class="span2"><?php echo $this->element('navigation'); ?></div>
			<div class="span10"><?php echo $this->fetch('content'); ?></div>
		</div>
		<div id="footer" class="row">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => 'CakePHP', 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
