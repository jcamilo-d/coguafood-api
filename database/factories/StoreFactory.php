<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'name' => $this->faker->name,
        'location' => $this->faker->address,
        'description' => $this->faker->text,
        'cellphone' => $this->faker->phoneNumber,
        'email' => $this->faker->unique()->safeEmail,
        'category_id' => 1
    ];
});
