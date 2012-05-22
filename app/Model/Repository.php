<?php

class Repository extends AppModel {
    public $belongsTo = array('User');
    public $hasMany = array('Testdrive' => array('order' => array('Testdrive.id' => 'DESC')));
}