<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;


// function baseUrl(){

//      return ('http://localhost:8000');
// }

function token()
{
    return Request::cookie('access_token');
}

function fullname()
{
    return Request::cookie('name');
}

function username()
{
    return Request::cookie('username');
}

function user_id()
{
    return Request::cookie('id');
}