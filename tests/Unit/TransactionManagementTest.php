<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TransactionManagementTest extends TestCase
{

    /** @test */
    public function a_debit_transaction_can_be_added_by_the_user()
    {
        $this->userLogin();

        for ($i = 0; $i < 5; $i++) {
            $amount = rand(1, 99);
            $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:" . $amount . ",type:\"DEBIT\"){id,amount,type,user_id}}");
            $this->assertEquals(200, $response->getStatusCode());

        }

    }

    private function userLogin()
    {
        $user_id = User::where('is_admin', 0)->orderBy('id', 'desc')->first()->id;
        Auth::loginUsingId($user_id);
    }

    public function graphql(string $query)
    {
        return $this->post('/graphql', [
            'query' => $query
        ]);
    }

    /** @test */
    public function a_credit_transaction_can_be_added_by_the_user()
    {
        $this->userLogin();
        for ($i = 0; $i < 5; $i++) {
            $amount = rand(1, 99);
            $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:" . $amount . ",type:\"CREDIT\"){id,amount,type,user_id}}");
            $this->assertEquals(200, $response->getStatusCode());

        }
    }

    /** @test */
    public function amount_should_be_required()
    {
        $this->userLogin();
        $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:null,type:\"DEBIT\"){id,type,user_id,amount}}");

        $this->assertEquals(
            "validation",
            $response->json('errors')[0]["message"]
        );

    }

    /** @test */
    public function amount_should_be_greater_than_zero()
    {
        $this->userLogin();
        $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:-23.3,type:\"DEBIT\"){id,amount,type,user_id}}");

        $this->assertEquals(
            "validation",
            $response->json('errors')[0]["message"]
        );

    }

    /** @test */
    public function a_type_should_be_required()
    {
        $this->userLogin();
        $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:23.3){id,amount,type,user_id}}");

        $this->assertEquals(
            "validation",
            $response->json('errors')[0]["message"]
        );

    }

    /** @test */
    public function a_type_should_be_debit_or_credit()
    {
        $this->userLogin();
        $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:23.3,type:\"DEBIsT\"){id,amount,type,user_id}}");

        $this->assertEquals(
            "validation",
            $response->json('errors')[0]["message"]
        );
    }

    /** @test */
    public function transaction_only_added_by_logged_in_user()
    {

        $response = $this->graphql("mutation Transaction{TransactionMutation(user_id:3,amount:121,type:\"DEBIT\"){id,amount,type,user_id}}");

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function get_user_transactions_only_by_admin()
    {
        $this->userLogin();

        $response = $this->graphql("query Transaction{transactions{id,name,email,total_debit_amount}}");

        $this->assertEquals(null, $response->json('data')['transactions']);
    }

    /** @test */
    public function get_user_debit_transactions()
    {
        $this->adminLogin();

        $response = $this->graphql("query Transaction{transactions{id,name,email,total_debit_amount}}");

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function adminLogin()
    {
        $user_id = User::where('is_admin', 1)->orderBy('id', 'desc')->first()->id;
        Auth::loginUsingId($user_id);
    }


}
