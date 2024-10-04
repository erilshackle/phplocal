<?php

namespace src\model;
use core\database\DBModel;

class User extends DBModel {
    public $id;
    public $name;
    public $email;
    
    public function __construct() {
        parent::__construct('users', 'id');
    }

}