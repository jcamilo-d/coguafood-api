<?php

namespace Tests;

// use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function create(string $model, array $attributes = [], $resource = true)
    {
        $resourceModel = factory("App\\Models\\$model")->create($attributes);
        $resourceClass = "App\\Http\\Resources\\$model";

        if(!$resource) {
            return $resourceModel;
        }

        return new $resourceClass($resourceModel);
    }
}
