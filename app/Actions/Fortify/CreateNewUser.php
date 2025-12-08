<?php

namespace App\Actions\Fortify;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'business_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // Create tenant for the business
        $tenant = Tenant::create([
            'name' => $input['business_name'],
            'email' => $input['email'],
            'is_active' => true,
        ]);

        // Create admin user for the business
        return User::create([
            'tenant_id' => $tenant->id,
            'name' => $input['business_name'], // Use business name as user name
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => 'admin', // First user is always admin
        ]);
    }
}
