<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 26/11/2016
 * Time: 00:14.
 */
$app->get('/', 'VinylStore\\Controllers\\MainController::indexAction');
$app->get('/home', 'VinylStore\\Controllers\\MainController::indexAction');
$app->get('/login', 'VinylStore\\Controllers\\LoginController::loginAction');
$app->get('/release/{id}', 'VinylStore\\Controllers\\MainController::getReleaseByIdAction');
$app->get('/vinyl', 'VinylStore\\Controllers\\MainController::getVinylAction');
//$app->get('/vinyl/page-{page}/', 'VinylStore\\Controllers\\MainController::getVinylAction')->value('page', '1');
$app->get('/admin/dashboard', 'VinylStore\\Controllers\\LoginController::dashboardAction');
//$app->get('/admin/database', 'VinylStore\\Controllers\\ReleaseController::indexAction');
$app->get('/admin/view/releases', 'VinylStore\\Controllers\\ReleaseController::viewTableAction');
$app->match('/admin/create-release', 'VinylStore\\Controllers\\ReleaseController::createReleaseAction');
$app->match('/admin/upload-image', 'VinylStore\\Controllers\\ImageController::uploadImageAction');
$app->get('/admin/view-images', 'VinylStore\\Controllers\\ImageController::viewImagesAction');
$app->get('/admin/delete-image/{id}', 'VinylStore\\Controllers\\ImageController::deleteImageAction');
$app->post('/admin/attach-image/{imageId}/{releaseId}', 'VinylStore\\Controllers\\ImageController::attachImageAction');
$app->match('/admin/pricing', 'VinylStore\\Controllers\\PricingController::indexAction');
