<?php

use Router\Router;

session_start();


require '../vendor/autoload.php';

define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);
define("ROOT", dirname(__DIR__));

$router = new Router($_GET['url']);

/**************************************************************
 **********************Gestion des TAGS************************
 **************************************************************/
$router->get('/admin/tags', 'App\Controllers\Admin\TagsController@index');
$router->get('/admin/tags/create', 'App\Controllers\Admin\TagsController@create');
$router->post('/admin/tags/create', 'App\Controllers\Admin\TagsController@create');
$router->get('/admin/tags/destroy/:id', 'App\Controllers\Admin\TagsController@destroy');
$router->post('/admin/tags/destroy/:id', 'App\Controllers\Admin\TagsController@destroy');
$router->get('/admin/tags/update/:id', 'App\Controllers\Admin\TagsController@update');
$router->post('/admin/tags/update/:id', 'App\Controllers\Admin\TagsController@update');
$router->get('/admin/tags/:id', 'App\Controllers\Admin\TagsController@tag');
/**************************************************************
 **********************Gestion des USERS************************
 **************************************************************/
$router->get('/admin/users', 'App\Controllers\Admin\UsersController@index');
$router->get('/admin/users/create', 'App\Controllers\Admin\UsersController@create');
$router->post('/admin/users/create', 'App\Controllers\Admin\UsersController@create');
$router->get('/admin/users/delete/:id', 'App\Controllers\Admin\UsersController@destroy');
$router->get('/admin/users/update/:id', 'App\Controllers\Admin\UsersController@update');
$router->post('/admin/users/update/:id', 'App\Controllers\Admin\UsersController@update');
$router->get('/admin/users/changePassword', 'App\Controllers\Admin\UsersController@changePassword');
$router->post('/admin/users/changePassword', 'App\Controllers\Admin\UsersController@changePassword');

/**************************************************************
 **********************BlogController**************************
 **************************************************************/
$router->get('/', 'App\Controllers\BlogController@welcome');
$router->get('/posts', 'App\Controllers\BlogController@index');
$router->get('/posts/:id', 'App\Controllers\BlogController@show');
$router->get('/tags/:id', 'App\Controllers\BlogController@tag');
$router->get('/comments/reply/:id', 'App\Controllers\BlogController@reply');
$router->post('/comments/reply/:id', 'App\Controllers\BlogController@reply');
$router->get('/comments/destroy/:id', 'App\Controllers\BlogController@destroy');
$router->get('/comments/response/:id', 'App\Controllers\BlogController@response');
$router->get('/responses/create/:id', 'App\Controllers\BlogController@SendResponse');
$router->post('/responses/create/:id', 'App\Controllers\BlogController@SendResponse');
$router->post('/comments/update/:id', 'App\Controllers\BlogController@update');
$router->post('/comments/create/:id', 'App\Controllers\BlogController@create');
/**************************************************************
 **********************Authentification************************
 **************************************************************/
$router->get('/profile', 'App\Controllers\UsersController@profile');
$router->post('/profile', 'App\Controllers\UsersController@profile');
$router->get('/login', 'App\Controllers\UsersController@login');
$router->post('/login', 'App\Controllers\UsersController@login');
$router->get('/register', 'App\Controllers\UsersController@register');
$router->post('/register', 'App\Controllers\UsersController@register');
$router->get('/logout', 'App\Controllers\UsersController@logout');
$router->get('/changePassword', 'App\Controllers\UsersController@changePassword');
$router->post('/changePassword', 'App\Controllers\UsersController@changePassword');
$router->get('/password', 'App\Controllers\UsersController@password');
$router->post('/password', 'App\Controllers\UsersController@password');
$router->get('/register/confirm/:id/:token', 'App\Controllers\UsersController@registerValidation');
$router->post('/register/confirm/:id/:token', 'App\Controllers\UsersController@registerValidation');
$router->post('/changeProfile', 'App\Controllers\UsersController@changeProfile');

//$router->post('/changePassword', 'App\Controllers\UsersController@changePassword');
/**************************************************************
 **********************PostController************************
 **************************************************************/
$router->get('/admin/posts', 'App\Controllers\Admin\PostsController@index');
$router->get('/admin/posts/create', 'App\Controllers\Admin\PostsController@create');
$router->post('/admin/posts/create', 'App\Controllers\Admin\PostsController@create');
$router->get('/admin/posts/show/:id', 'App\Controllers\Admin\PostsController@show');
$router->get('/admin/posts/update/:id', 'App\Controllers\Admin\PostsController@update');
$router->post('/admin/posts/update/:id', 'App\Controllers\Admin\PostsController@update');
$router->get('/admin/posts/delete/:id', 'App\Controllers\Admin\PostsController@destroy');
$router->post('/admin/posts/published/:id', 'App\Controllers\Admin\PostsController@published');
$router->get('/admin/posts/details/:id', 'App\Controllers\Admin\PostsController@details');

//$router->post('/admin/posts/delete/:id', 'App\Controllers\Admin\PostController@destroy');
/**************************************************************
 **********************CommentController************************
 **************************************************************/
$router->get('/admin/comments', 'App\Controllers\Admin\CommentsController@index');
$router->get('/admin/comments/delete/:id', 'App\Controllers\Admin\CommentsController@destroy');
$router->get('/admin/comments/edit/:id', 'App\Controllers\Admin\CommentsController@edit');
$router->post('/admin/comments/update/:id', 'App\Controllers\Admin\CommentsController@update');
$router->get('/comments/create/:id', 'App\Controllers\BlogController@create');
$router->post('/admin/comments/create', 'App\Controllers\Admin\CommentsController@create');
/**************************************************************
 **********************ContactController**************************
 **************************************************************/
$router->get('/contact', 'App\Controllers\ContactsController@create');
$router->post('/contact', 'App\Controllers\ContactsController@create');
/**************************************************************
 **********************ResponseController************************
 **************************************************************/
$router->get('/admin/responses', 'App\Controllers\Admin\ResponsesController@index');
$router->get('/admin/responses/delete/:id', 'App\Controllers\Admin\ResponsesController@destroy');
$router->get('/admin/responses/edit/:id', 'App\Controllers\Admin\ResponsesController@edit');
$router->post('/admin/responses/update/:id', 'App\Controllers\Admin\ResponsesController@update');
//$router->get('/comments/create/:id', 'App\Controllers\BlogController@create');
//$router->post('/admin/comments/create', 'App\Controllers\Admin\CommentsController@create');

$router->run();
