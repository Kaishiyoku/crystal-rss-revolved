<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\AddAdminUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddAdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_creation(): void
    {
        Event::fake();

        $expectedName = 'Test';
        $expectedEmail = 'test@test.dev';

        $this->artisan(AddAdminUser::class)
            ->expectsQuestion('Name', $expectedName)
            ->expectsQuestion('Email', $expectedEmail)
            ->expectsQuestion('Password', 'test1234')
            ->expectsQuestion('Password confirmation', 'test1234')
            ->assertExitCode(Command::SUCCESS);

        Event::assertDispatched(Registered::class, 1);

        $users = User::all();

        static::assertCount(1, $users);

        $firstUser = $users->first();

        static::assertSame($expectedName, $firstUser->name);
        static::assertSame($expectedEmail, $firstUser->email);
        static::assertNotNull($firstUser->email_verified_at);
        static::assertNotNull($firstUser->password);
        static::assertNull($firstUser->remember_token);
        static::assertTrue($firstUser->is_admin);
    }

    public function test_fails_due_to_name_validation_error(): void
    {
        $this->expectException(ValidationException::class);

        $this->artisan(AddAdminUser::class)
            ->expectsQuestion('Name', '')
            ->expectsQuestion('Email', '')
            ->expectsQuestion('Password', 'test1234')
            ->expectsQuestion('Password confirmation', 'test1234')
            ->assertExitCode(Command::INVALID);
    }

    public function test_fails_due_to_email_validation_error(): void
    {
        $this->expectException(ValidationException::class);

        $this->artisan(AddAdminUser::class)
            ->expectsQuestion('Name', 'Test')
            ->expectsQuestion('Email', 'test')
            ->expectsQuestion('Password', 'test1234')
            ->expectsQuestion('Password confirmation', 'test1234')
            ->assertExitCode(Command::INVALID);
    }

    public function test_fails_due_to_password_validation_error(): void
    {
        $this->expectException(ValidationException::class);

        $this->artisan(AddAdminUser::class)
            ->expectsQuestion('Name', 'Test')
            ->expectsQuestion('Email', 'test')
            ->expectsQuestion('Password', 'test')
            ->expectsQuestion('Password confirmation', 'test')
            ->assertExitCode(Command::INVALID);
    }

    public function test_fails_due_to_password_confirmation_error(): void
    {
        $this->expectException(ValidationException::class);

        $this->artisan(AddAdminUser::class)
            ->expectsQuestion('Name', 'Test')
            ->expectsQuestion('Email', 'test')
            ->expectsQuestion('Password', 'test1234')
            ->expectsQuestion('Password confirmation', 'test12345')
            ->assertExitCode(Command::INVALID);
    }
}
