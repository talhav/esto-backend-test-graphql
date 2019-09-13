# ESTO-backend-test-graphql
ESTO Backend Test with GraphQL

# Step By Step

create DB                                     // (esto-backend-test.sql on root)

Setup ENV

php artisan migrate                            // (Migrate Tables)

php artisan make:seeder AdminUserSeeder        // (Creates Admin User)

vendor/bin/phpunit                             // (Run Test Cases)

