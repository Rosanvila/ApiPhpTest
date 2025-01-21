<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('POST', '/ApiPhpTest/api/users', 'UserController@create');
    $r->addRoute('GET', '/ApiPhpTest/api/users', 'UserController@index');
    $r->addRoute('GET', '/ApiPhpTest/meteo', 'WeatherController@index');
};