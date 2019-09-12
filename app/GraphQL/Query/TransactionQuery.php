<?php
namespace App\GraphQL\Query;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class TransactionQuery extends Query
{
    protected $attributes = [
        'name' => 'Transaction query'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('transactions'));
    }

    public function args(): array
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
            'name' => ['name' => 'name', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'type' => ['name' => 'name', 'type' => Type::string()],
            'amount' => ['name' => 'email', 'type' => Type::float()],
            'total_debit_amount' => ['name' => 'total_debit_amount', 'type' => Type::float()],
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if(Auth::user()->is_admin){
            $users = User::with(['transactions' => function ($query) {
                $query->sum('amount');
            }])->orderByDesc('id')
                ->limit(10)->get();

            $data = [];

            foreach ($users as $key=>$user){
                $data[$key]['id']= $user->id;
                $data[$key]['name']= $user->name;
                $data[$key]['email']= $user->email;
                $data[$key]['total_debit_amount']= $user->transactions->where('type','DEBIT')->sum('amount');
            }

            return $data;
        }


    }
}
