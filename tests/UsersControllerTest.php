<?php

use Tests\TestCase;
use Faker\Factory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function non_authenticated_user_cannot_access_the_following_endpoints()
    {
        $usersLogout = $this->json('POST', 'api/users/logout');
        $usersLogout->seeStatusCode(401);

        $usersRefresh = $this->json('POST', 'api/users/refresh');
        $usersRefresh->seeStatusCode(401);

        $usersMe = $this->json('GET', 'api/users/auth/me');
        $usersMe->seeStatusCode(401);

        $usersUpdate = $this->json('PUT', 'api/users/-1');
        $usersUpdate->seeStatusCode(401);
    }

    /**
     * @test
     */
    public function will_fail_with_a_409_if_user_we_want_to_insert_alredy_exist()
    {
        $faker = Factory::create();

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/users', [
            'name' => $name = $faker->name,
            'email' => $email = $faker->email,
            'password' => 'secret'
        ]);

        $response->seeStatusCode(201)
            ->seeJsonStructure([
                'name',
                'email',
                'created_at'
            ])->seeJson([
                'name' => $name,
                'email' => $email,
                'wounds' => $wounds
            ]);

        $this->seeInDatabase('users', [
            'name' => $name,
            'email' => $email,
        ]);

        $response2 = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/users', [
            'name' => $name,
            'email' => $email,
            'password' => 'secret'
        ]);

        $response2->seeStatusCode(409)
            ->seeJsonStructure(['error'])
            ->seeJson([
                'error' => "El usuario ya existe"
            ]);
    }

    /**
     * @test
     */
    public function can_create_an_user()
    {
        $faker = Factory::create();

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/users', [
            'name' => $name = $faker->name,
            'email' => $email = $faker->safeEmail,
            'password' => 'secret'
        ]);

        $response->seeStatusCode(201)
            ->seeJsonStructure([
                'name',
                'email',
                'created_at'
            ])->seeJson([
                'name' => $name,
                'email' => $email,
            ]);

        $this->seeInDatabase('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_user_is_not_found()
    {
        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', 'api/users/-1');
        $response->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function can_return_an_user()
    {
        $user = $this->create("User");

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', "api/users/$user->id");

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => (string) $user->created_at
            ]);
    }

    /**
     * @test
     */
    public function can_return_all_users()
    {
        $user1 = $this->create("User");
        $user2 = $this->create("User");
        $user3 = $this->create("User");

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', 'api/users');

        $response->seeStatusCode(200)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_user_we_want_to_update_is_not_found()
    {
        $response = $this->actingAs($this->create('User', [], false), 'users')->actingAs($this->create('Admin', [], false), 'admins')->json('PUT', 'api/users/-1');

        $response->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function can_user_update_his_own_data_without_password_attribute()
    {
        $user = $this->create('User', [], false);

        $response = $this->actingAs($user, 'users')->json('PUT', 'api/users/auth/me', [
            'name' => $name = $user->name . '_updated',
            'email' => $email = $user->email . '_updated',
        ]);

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $user->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $user->created_at
            ]);

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => $name,
            'email' => $email,
            'password' => $user->password,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at
        ]);

        $userInDatabase = App\Models\User::find($user->id);
        $this->assertTrue(app('hash')->check('secret', $userInDatabase->password));
    }

    /**
     * @test
     */
    public function can_user_update_his_own_data_with_password_attribute()
    {
        $user = $this->create('User', [], false);

        $response = $this->actingAs($user, 'users')->json('PUT', 'api/users/auth/me', [
            'name' => $name = $user->name . '_updated',
            'email' => $email = $user->email . '_updated',
            'password' => $newPassword = 'secret_updated',
        ]);

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $user->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $user->created_at
            ])
            ->seeInDatabase('users', [
                'id' => $user->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $user->created_at,
                'updated_at' => (string) $user->updated_at
            ]);

        $userInDatabase = App\Models\User::find($user->id);
        $this->assertTrue(app('hash')->check($newPassword, $userInDatabase->password));
    }

    /**
     * @test
     */
    public function can_admin_update_an_user_without_password_attribute()
    {
        $user = $this->create('User');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('PUT', "api/users/$user->id", [
            'name' => $name = $user->name . '_updated',
            'email' => $email = $user->email . '_updated',
        ]);

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $user->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $user->created_at
            ]);

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => $name,
            'email' => $email,
            'password' => $user->password,
            'created_at' => (string) $user->created_at,
        ]);

        $userInDatabase = App\Models\User::find($user->id);
        $this->assertTrue(app('hash')->check('secret', $userInDatabase->password));
    }

    /**
     * @test
     */
    public function can_admin_update_an_user_with_password_attribute()
    {
        $user = $this->create('User');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('PUT', "api/users/$user->id", [
            'name' => $name = $user->name . '_updated',
            'email' => $email = $user->email . '_updated',
            'password' => $newPassword = 'secret_updated'
        ]);

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $user->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $user->created_at
            ]);

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => $name,
            'email' => $email,
            'created_at' => (string) $user->created_at,
        ]);

        $userInDatabase = App\Models\User::find($user->id);
        $this->assertTrue(app('hash')->check($newPassword, $userInDatabase->password));
    }

    /**
     * @test
     */
    public function can_deactive_an_user()
    {
        $user = $this->create('User');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('DELETE', "api/users/$user->id");

        $response->seeStatusCode(204);

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

}
