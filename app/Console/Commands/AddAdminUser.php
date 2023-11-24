<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class AddAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an admin user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = text(
            label: 'What is your name?',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 3 => 'The name must be at least 3 characters.',
                strlen($value) > 255 => 'The name must not exceed 255 characters.',
                default => null,
            },
            hint: 'This will be displayed on your profile.',
        );

        $email = text(
            label: 'What is your email address?',
            required: true,
            validate: function (string $value) {
                $validator = validator(['email' => $value], ['email' => ['email', 'max:255', 'unique:'.User::class]]);

                if ($validator->fails()) {
                    return $validator->errors()->first();
                }

                return null;
            },
            hint: 'This will be used for logging in to your account.',
        );

        $password = password(
            label: 'What is your password?',
            required: true,
            validate: function (string $value) {
                $validator = validator(['password' => $value], ['password' => [Rules\Password::defaults()]]);

                if ($validator->fails()) {
                    return $validator->errors()->first();
                }

                return null;
            },
            hint: 'Minimum 8 characters.',
        );

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->is_admin = true;
        $user->save();

        $user->markEmailAsVerified();

        event(new Registered($user));

        $this->line('Administrative user added.');
    }
}
