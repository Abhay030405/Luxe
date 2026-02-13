<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function getUser(){
        return "Hello Aadya";
    }
    function aboutAadya(){
        return "She has a boyfriend named anshuman";
    }
}
