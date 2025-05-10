<?php
// App/Core/routes.php

use CarCatalog\App\Core\Router;
use CarCatalog\App\Controllers\LandingController;
use CarCatalog\App\Controllers\CatalogController;
use CarCatalog\App\Controllers\AuthController;
use CarCatalog\App\Controllers\Api\CarApiController;
use CarCatalog\App\Controllers\AdminController;


// страница логина и её обработка
Router::add('/login',  AuthController::class . '@login');
Router::add('/logout', AuthController::class . '@logout');

// App/Core/routes.php (фрагмент)

// ... после определения маршрутов /login и /logout ...
// Маршрут главной страницы админ-панели
Router::add('/admin', CarCatalog\App\Controllers\AdminController::class . '@index');

// Маршруты для админки машин (как были)
Router::add('/admin/cars/create',  CatalogController::class . '@create');
// ... остальные маршруты без изменений ...

Router::add('/admin/requests',            AdminController::class . '@requests');
Router::add('/admin/requests/delete/(\d+)', AdminController::class . '@deleteRequest');


// CRUD для админки машин
Router::add('/admin/cars/create',  CatalogController::class . '@create');
Router::add('/admin/cars/store',   CatalogController::class . '@store');
Router::add('/admin/cars/edit/(\d+)',   CatalogController::class . '@edit');
Router::add('/admin/cars/update/(\d+)', CatalogController::class . '@update');
Router::add('/admin/cars/delete/(\d+)', CatalogController::class . '@delete');
Router::add('/admin/cars/image/delete/(\d+)', CatalogController::class . '@deleteImage');

// *** Новый маршрут для обработки заявки с главной страницы ***
Router::add('/request', LandingController::class . '@submitRequest');

// Маршрут главной страницы и каталога
Router::add('/',         LandingController::class . '@index');
Router::add('/catalog',  CatalogController::class  . '@index');
Router::add('/catalog/view/(\d+)', CatalogController::class  . '@view');


// API: машины
Router::add('/api/cars',          CarApiController::class . '@index');
Router::add('/api/cars/(\d+)',    CarApiController::class . '@show');
