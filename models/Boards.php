<?php


use Phalcon\Mvc\Model;

class Boards extends Model
{
    public $id;

    public $tm;

    public $sm;
    
    public $history;
    
    public $user_white;
    
    public $user_black;
    
    public $date_created;
    
    public $user;
    
    public $active;

    public function initialize()
    {
        $this->belongsTo(
            'user_white',
            'Users',
            'id',
            [
                'alias' => 'al_user_white'
            ]
        );

        $this->belongsTo(
            'user_black',
            'Users',
            'id',
            [
                'alias' => 'al_user_black'
            ]
        );
    }
}