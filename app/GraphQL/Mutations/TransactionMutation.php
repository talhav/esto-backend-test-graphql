<?php

namespace App\GraphQL\Mutations;

use App\Transaction;
use CLosure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class TransactionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'TransactionMutation'
    ];

    public function type(): Type
    {
        return GraphQL::type('transactions');
    }

    public function args(): array
    {
        return [
            //'id' => ['name' => 'id', 'type' => Type::string()],
            'amount' => ['name' => 'amount', 'type' => Type::float()],
            'type' => ['name' => 'type', 'type' => Type::string()],
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /*
         Middleware for check role having problems for different endpoints
        so making the admin / user check here
        */

        if (!Auth::user()->is_admin) {
            $transaction = Transaction::Create($args);
            if (!$transaction) {
                return null;
            }
            return $transaction;
        }

    }

    /*
     * Validation Rules
     * */

    protected function rules(array $args = []): array
    {
        return [
            'type' => 'required|in:DEBIT,CREDIT',
            'amount' => 'required|numeric|min:0|not_in:0',
        ];
    }
}
