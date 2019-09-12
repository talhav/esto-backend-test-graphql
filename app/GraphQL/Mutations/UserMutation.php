<?php

namespace App\GraphQL\Mutations;

use CLosure;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
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
            //'id' => ['name' => 'id', 'type' => Type::string()],
            'name' => ['name' => 'name', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'name' => ['required','unique:users,name'],
            'email' => ['required', 'email']
        ];
    }


    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (Auth::user()->is_admin) {
            $user = User::Create($args);
            if(!$user) {
                return null;
            }
            return $user;
        }
        return null;

    }
}
