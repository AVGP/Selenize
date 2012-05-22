<h2>Add Repository</h2>
<?php echo $this->Form->create('Repository'); ?>
<?php echo $this->Form->input('name'); ?>
<?php echo $this->Form->hidden('user_id', array('value' => $user_id)); ?>
<?php echo $this->Form->end('Add this repository'); ?>