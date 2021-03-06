<?php

namespace App\GraphQL\Mutations;

use App\User;
use CLosure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UserMutation'
    ];

    public function type(): Type
    {
        return GraphQL::type('users');
    }

    public function args(): array
    {
        return [
            'name' => ['name' => 'name', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /*
          Middleware for check role having problems for different endpoints
         so making the admin / user check here
         */

        if (Auth::user()->is_admin) {
            $user = User::Create($args);
            if (!$user) {
                return null;
            }
            return $user;
        }
        return null;

    }

    /*
    * Validation Rules
    * */

    protected function rules(array $args = []): array
    {
        return [
            'name' => ['required', 'unique:users,name'],
            'email' => ['required', 'email']
        ];
    }
}
