<?php

declare(strict_types=1);

use Pecee\SimpleRouter\SimpleRouter;

try{
SimpleRouter::setDefaultNamespace('TechBlog\Controller');

SimpleRouter::group(['prefix' => SLUG], function(){
    SimpleRouter::get('/', 'VisitorController@index');
    SimpleRouter::get('/ViewArticle/{id}', 'VisitorController@viewArticle'); 
    SimpleRouter::get('/ViewArticlesByTitle', 'VisitorController@viewArticlesByTitle'); 
    SimpleRouter::get('/ViewArticlesByCategory/{category}', 'VisitorController@viewArticlesByCategory')->where(['category' => '.*']);
});

SimpleRouter::group(['prefix' => SLUGADM], function(){
    SimpleRouter::match(['get', 'post'], '/Login', 'LoginAdmController@login');
    SimpleRouter::match(['get', 'post'], '/Logout', 'LoginAdmController@logout');
    SimpleRouter::match(['get', 'post'], '/EditArticle/{id}', 'AdministratorController@editArticle');
    SimpleRouter::match(['get', 'post'], '/CreateArticle', 'AdministratorController@createArticle');
    SimpleRouter::get('/DeleteArticle/{id}', 'AdministratorController@deleteArticle');
});

SimpleRouter::start();
}catch(Pecee\SimpleRouter\Exceptions\NotFoundHttpException $e){
    echo "Erro: {$e->getMessage()}";
}