<?php

use Tests\TestCase;
use Faker\Factory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AdminsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function non_authenticated_admin_cannot_access_the_following_endpoints()
    {
        $adminsStore = $this->json('POST', 'api/admins');
        $adminsStore->seeStatusCode(401);

        $adminsLogout = $this->json('POST', 'api/admins/logout');
        $adminsLogout->seeStatusCode(401);

        $adminsRefresh = $this->json('POST', 'api/admins/refresh');
        $adminsRefresh->seeStatusCode(401);

        $adminsIndex = $this->json('GET', 'api/admins');
        $adminsIndex->seeStatusCode(401);

        $adminsShow = $this->json('GET', 'api/admins/-1');
        $adminsShow->seeStatusCode(401);

        $adminsMe = $this->json('GET', 'api/admins/auth/me');
        $adminsMe->seeStatusCode(401);

        $adminsUpdate = $this->json('PUT', 'api/admins/-1');
        $adminsUpdate->seeStatusCode(401);

        $adminsDestroy = $this->json('DELETE', 'api/admins/-1');
        $adminsDestroy->seeStatusCode(401);
    }

    /**
     * @test
     */
    public function will_fail_with_a_409_if_admin_we_want_to_insert_alredy_exist()
    {
        $faker = Factory::create();

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/admins', [
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
                'email' => $email
            ]);

        $this->seeInDatabase('admins', [
            'name' => $name,
            'email' => $email,
        ]);

        $response2 = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/admins', [
            'name' => $name,
            'email' => $email,
            'password' => 'secret'
        ]);

        $response2->seeStatusCode(409)
            ->seeJsonStructure(['error'])
            ->seeJson([
                'error' => "El registro ya existe"
            ]);
    }

    /**
     * @test
     */
    public function can_create_an_admin()
    {
        $faker = Factory::create();

        // $response = $this->json('POST', 'api/admins', [
        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('POST', 'api/admins', [
            'name' => $name = $faker->name,
            'email' => $email = $faker->safeEmail,
            'password' => 'secret'
        ]);

        $response->seeStatusCode(201)
            ->seeJsonStructure([
                'id',
                'name',
                'email',
                'created_at'
            ])->seeJson([
                'name' => $name,
                'email' => $email
            ]);

        $this->seeInDatabase('admins', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_admin_is_not_found()
    {
        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', 'api/admins/-1');
        $response->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function can_return_an_admin()
    {
        $admin = $this->create("Admin");

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', "api/admins/$admin->id");

        $response->seeStatusCode(200)
            ->seeJsonStructure([
                'id',
                'name',
                'email',
                'created_at'
            ])
            ->seeJsonEquals([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'created_at' => (string) $admin->created_at
            ]);
    }

    /**
     * @test
     */
    public function can_return_all_admins()
    {
        $admin1 = $this->create("Admin");
        $admin2 = $this->create("Admin");
        $admin3 = $this->create("Admin");

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('GET', 'api/admins');

        $response->seeStatusCode(200)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at'
                    ]
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    /**
     * @test
     */
    public function will_fail_with_a_404_if_admin_we_want_to_update_is_not_found()
    {
        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('PUT', 'api/admins/-1');

        $response->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function can_update_an_admin_without_password_attribute()
    {
        $admin = $this->create('Admin');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('PUT', "api/admins/$admin->id", [
                'name' => $name = $admin->name .'_updated',
                'email' => $email = $admin->email .'_updated',
        ]);

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $admin->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $admin->created_at
            ]);

        $this->seeInDatabase('admins', [
                'id' => $admin->id,
                'name' => $admin->name .'_updated',
                'email' => $admin->email .'_updated',
                'password' => $admin->password,
                'created_at' => (string) $admin->created_at,
                'updated_at' => (string) $admin->updated_at
            ]);

        $adminInDatabase = App\Models\Admin::find($admin->id);
        $this->assertTrue(app('hash')->check('secret', $adminInDatabase->password));
    }

    /**
     * @test
     */
    public function can_update_an_admin_with_password_attribute()
    {
        $admin = $this->create('Admin');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('PUT', "api/admins/$admin->id", [
            'name' => $name = $admin->name .'_updated',
            'email' => $email = $admin->email .'_updated',
            'password' => $newPassword = 'secret_updated'
        ]);

        $response->seeStatusCode(200)
            ->seeJsonEquals([
                'id' => $admin->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $admin->created_at
            ]);

        $this->seeInDatabase('admins', [
                'id' => $admin->id,
                'name' => $name,
                'email' => $email,
                'created_at' => (string) $admin->created_at
            ]);

        $adminInDatabase = App\Models\Admin::find($admin->id);
        $this->assertTrue(app('hash')->check($newPassword, $adminInDatabase->password));
    }

    /**
     * @test
     */
    public function can_deactive_an_admin()
    {
        $admin = $this->create('Admin');

        $response = $this->actingAs($this->create('Admin', [], false), 'admins')->json('DELETE', "api/admins/$admin->id");

        $response->seeStatusCode(204);

        $this->seeInDatabase('admins', [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
        ]);
    }
}
