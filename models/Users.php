<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $id;

    public $name;

    public $email;
    
    public $password;
    
    public $date_registration;
    
    public $active;

    public function initialize()
    {
        $this->hasMany(
            'id',
            'Boards',
            'user',
            [
                'alias' => 'boards'
            ]
        );
    }
}