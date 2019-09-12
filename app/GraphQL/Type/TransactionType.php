<?php
namespace App\GraphQL\Type;
use App\Transaction;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TransactionType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Transaction',
        'description'   => 'Transaction of Users',
        'model'         => Transaction::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the transaction',
                // Use 'alias', if the database column is different from the type name.
                // This is supported for discrete values as well as relations.
                // - you can also use `DB::raw()` to solve more complex issues
                // - or a callback returning the value (string or `DB::raw()` result)
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of transaction',
            ],
            'amount' => [
                'type' => Type::float(),
                'description' => 'The amount of transaction',
            ],
            'user_id' => [
                'type' => Type::int(),
                'description' => 'The user id of users transactions',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of user',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user',
            ],
            'total_debit_amount' => [
                'type' => Type::float(),
                'description' => 'The sum of debit transactions of user',
            ],

        ];
    }


}
