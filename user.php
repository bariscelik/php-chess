<?php

class User
{
    public function index()
    {
        echo 'test';
    }

    public function login($name)
    {
        $context = "<h1>This is order: {$name}!</h1>";
        $this->response->setContext($context);

        return $this->response;
    }
}