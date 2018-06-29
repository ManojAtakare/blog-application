<?php
/**
 * Created by PhpStorm.
 * User: manoj
 * Date: 25/6/18
 * Time: 5:22 PM
 */


// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function index()
    {
        return new Response('Hello!');
    }

    public function admin()
    {
        return new Response('Hello!');
    }


}