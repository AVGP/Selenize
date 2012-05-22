<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
    public $name = 'User';
    
    public $hasMany = array('Repository');
    
    public $validate = array(
        'username' => array(
            'uniqueness' => array(
                'rule' => array('isUnique'),
                'message' => 'This username is already taken'
            ),
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please choose a username'                
            )
        ),
        'password' => array(
            'rule' => array('notEmpty'),
            'message' => 'Please choose a password'
        )
    );
    
    public function beforeSave() {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }    
}