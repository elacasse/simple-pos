<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use LaravelJsonApi\Laravel\Routing\Relationships;

JsonApiRoute::server('v1')
    ->prefix('v1')
    ->middleware('bearer.token')
    ->resources(function (ResourceRegistrar $server) {
        $server->resource('bills', JsonApiController::class)
            ->relationships(function (Relationships $relations) {
                $relations->hasMany('articles');
            });

        $server->resource('articles', JsonApiController::class)
            ->relationships(function (Relationships $relations) {
                $relations->hasOne('bill');
            });
    });
