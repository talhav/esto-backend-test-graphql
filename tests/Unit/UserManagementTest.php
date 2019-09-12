<?php

namespace Tests\Unit;

use App\User;
use Faker\Factory;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
    /** @test */
    public function a_user_can_be_added_by_the_admin()
    {

        $this->adminLogin(1);
        $faker = Factory::create();
        $name  = "".$faker->name."";
        $email = "".$faker->email."";
        $response = $this->graphql("mutation users{UserMutation(name:\"$name\",email:\"$email\"){id,name,email}}");
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function a_user_name_should_be_unique()
    {
        $this->adminLogin(1);
        $faker = Factory::create();
        $email = $faker->email;
        $name = $this->getExistingName();
        $response = $this->graphql("mutation users{UserMutation(name:\"$name\",email:\"$email\"){id,name,email}}");
        $this->assertEquals(
            "validation",
            $response->json('errors')[0]["message"]
        );
    }

    /** @test */
    public function a_user_email_should_be_valid()
    {

        $this->adminLogin(1);
        $faker = Factory::create();
        $name = $faker->name;
        $response = $this->graphql("mutation users{UserMutation(name:\"$name\",email:\"$name\"){id,name,email}}");
        $this->assertEquals(
            "validation",
            $response->json('errors')[0]["message"]
        );
    }

    /** @test */
    public function only_admin_can_create_user()
    {

        $this->adminLogin(2);
        $faker = Factory::create();
        $name  = $faker->name;
        $email = $faker->email;
        $response = $this->graphql("mutation users{UserMutation(name:\"$name\",email:\"$email\"){id,name,email}}");
        $this->assertEquals(null, $response->json('data')['UserMutation']);
    }

    private function adminLogin($id)
    {
        Auth::loginUsingId($id);
    }

    private function getExistingName()
    {
        return User::where('is_admin',0)->first()->name;
    }

    public function graphql(string $query)
    {
        return $this->post('/graphql', [
            'query' => $query
        ]);
    }

}
