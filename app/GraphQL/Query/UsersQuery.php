<?php

namespace App\GraphQL\Query;

use App\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'Users query'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('users'));
    }

    public function args(): array
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::string()],
            'name' => ['name' => 'name', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (Auth::user()->is_admin) {
            if (isset($args['id'])) {
                return User::where('id', $args['id'])->get();
            }

            if (isset($args['email'])) {
                return User::where('email', $args['email'])->get();
            }

            return User::all();
        }
        return null;
    }
}
