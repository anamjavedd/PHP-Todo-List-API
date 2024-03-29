<?php

class Request

{

    public static function uri()

    {
        return trim (
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); //from this url http://localhost:8888/names?name=nitya this gives names
            //return trim($_SERVER['REQUEST_URI'], '/');  //from this url http://localhost:8888/names?name=nitya this gives names?name=nitya
    }

    public static function method()

    {
        return $_SERVER['REQUEST_METHOD']; //GET or POST
    }
}