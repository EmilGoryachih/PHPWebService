<?php
// App/Core/routes.php

use CarCatalog\App\Core\Router;
use CarCatalog\App\Controllers\LandingController;
use CarCatalog\App\Controllers\CatalogController;
use CarCatalog\App\Controllers\AuthController;
use CarCatalog\App\Controllers\Api\CarApiController;
use CarCatalog\App\Controllers\AdminController;


Router::add('/login',  AuthController::class . '@login');
Router::add('/logout', AuthController::class . '@logout');


Router::add('/admin', CarCatalog\App\Controllers\AdminController::class . '@index');

Router::add('/admin/cars/create',  CatalogController::class . '@create');

Router::add('/admin/requests',            AdminController::class . '@requests');
Router::add('/admin/requests/delete/(\d+)', AdminController::class . '@deleteRequest');


Router::add('/admin/cars/create',  CatalogController::class . '@create');
Router::add('/admin/cars/store',   CatalogController::class . '@store');
Router::add('/admin/cars/edit/(\d+)',   CatalogController::class . '@edit');
Router::add('/admin/cars/update/(\d+)', CatalogController::class . '@update');
Router::add('/admin/cars/delete/(\d+)', CatalogController::class . '@delete');
Router::add('/admin/cars/image/delete/(\d+)', CatalogController::class . '@deleteImage');

Router::add('/request', LandingController::class . '@submitRequest');

Router::add('/',         LandingController::class . '@index');
Router::add('/catalog',  CatalogController::class  . '@index');
Router::add('/catalog/view/(\d+)', CatalogController::class  . '@view');


Router::add('/api/cars',          CarApiController::class . '@index');
Router::add('/api/cars/(\d+)',    CarApiController::class . '@show');
