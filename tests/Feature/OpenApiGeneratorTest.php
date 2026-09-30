<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Xolvio\OpenApiGenerator\Test\Controller;
use Xolvio\OpenApiGenerator\Test\ControllerWithUnresolvableDependency;

beforeAll(function () {
    if (File::exists(config('openapi-generator.path'))) {
        File::delete(config('openapi-generator.path'));
    }

    Route::prefix('api')->group(function () {
        Route::post('/{parameter_1}/{parameter_2}/{parameter_3}', [Controller::class, 'allCombined'])
            ->name('allCombined');
        Route::post('/contentType', [Controller::class, 'contentType'])
            ->name('contentType');
        Route::get('/auth', [Controller::class, 'basic'])
            ->can('permission1')
            ->middleware('can:permission2')
            ->middleware('auth:sanctum')
            ->name('auth');
        Route::post('/unresolvableDependency', [ControllerWithUnresolvableDependency::class, 'basic'])
            ->name('unresolvableDependency');
    });
});

it('can generate json', function () {
    Artisan::call('openapi:generate');

    expect(File::exists(config('openapi-generator.path')))->toBe(true);
    expect(File::get(config('openapi-generator.path')))->toBeJson();
});

it('documents routes whose controller cannot be built outside a request', function () {
    Artisan::call('openapi:generate');

    $paths = json_decode(File::get(config('openapi-generator.path')), true)['paths'];

    expect($paths)->toHaveKey('/api/unresolvableDependency');
    expect($paths['/api/unresolvableDependency'])->toHaveKey('post');
});

afterAll(function () {
    if (File::exists(config('openapi-generator.path'))) {
        File::delete(config('openapi-generator.path'));
    }
});
