<?php

$router->get('','TodoController@read');
$router->post('createTodo','TodoController@create');
$router->put('updateTodo/{id}', 'TodoController@update');
$router->delete('deleteTodo/{id}','TodoController@delete');
