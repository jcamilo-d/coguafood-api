<?php

use Tests\TestCase;
use Faker\Factory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class StoresControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function can_an_admin_create_a_store()
    {
        $faker = Factory::create();

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/admins/stores', [
            'name' => $name = $faker->name,
            'location' => $location = $faker->address,
            'description' => $description = $faker->text,
            'cellphone' => $cellphone = $faker->phoneNumber,
            'email' => $email = $faker->safeEmail,
            'category_id' => $category_id = $faker->numberBetween(1, 3)
        ]);

        $response->seeStatusCode(201)
            ->seeJsonStructure([
                'name',
                'location',
                'description',
                'cellphone',
                'email',
                'category_id',
                'created_at'
            ])->seeJson([
                'name' => $name,
                'location' => $location,
                'description' => $description,
                'cellphone' => $cellphone,
                'email' => $email,
                'category_id' => $category_id
            ]);

        $this->seeInDatabase('stores', [
            'name' => $name,
            'location' => $location,
            'description' => $description,
            'cellphone' => $cellphone,
            'email' => $email,
            'category_id' => $category_id,
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_store_is_not_found_with_user_authenticated()
    {
        $response = $this->actingAs($this->create('User', [], false), 'users')->json('GET', 'api/stores/-1');
        $response->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_store_is_not_found_with_admin_authenticated()
    {
        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', 'api/stores/-1');
        $response->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function can_return_all_stores_paginated()
    {
        $store1 = $this->create('Store');
        $store2 = $this->create('Store');
        $store3 = $this->create('Store');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', 'api/stores');

        $response->seeStatusCode(200)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'location',
                        'description',
                        'cellphone',
                        'email',
                        'category_id',
                        'created_at'
                    ],
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next'
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'per_page',
                        'to',
                        'total',
                        'path'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function can_return_all_stores_without_paginate()
    {
        $store1 = $this->create('Store');
        $store2 = $this->create('Store');
        $store3 = $this->create('Store');

        $response = $this->json('GET', 'api/stores');

        $response->seeStatusCode(200)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'location',
                        'description',
                        'cellphone',
                        'email',
                        'category_id',
                        'created_at'
                    ]
                ]
            ]);
    }

}
