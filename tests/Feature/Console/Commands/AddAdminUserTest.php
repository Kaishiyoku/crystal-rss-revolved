<?php

use App\Console\Commands\AddAdminUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);

test('admin user creation', function () {
    Event::fake();

    $expectedName = 'Test';
    $expectedEmail = 'test@test.dev';

    artisan(AddAdminUser::class)
        ->expectsQuestion('Name', $expectedName)
        ->expectsQuestion('Email', $expectedEmail)
        ->expectsQuestion('Password', 'test1234')
        ->expectsQuestion('Password confirmation', 'test1234')
        ->assertExitCode(Command::SUCCESS);

    Event::assertDispatched(Registered::class, 1);

    $users = User::all();

    expect($users)->toHaveCount(1);

    $firstUser = $users->first();

    expect($firstUser->name)->toBe($expectedName)
        ->and($firstUser->email)->toBe($expectedEmail)
        ->and($firstUser->email_verified_at)->not->toBeNull()
        ->and($firstUser->password)->not->toBeNull()
        ->and($firstUser->remember_token)->toBeNull()
        ->and($firstUser->is_admin)->toBeTrue();
});

test('fails due to name validation error', function () {
    artisan(AddAdminUser::class)
        ->expectsQuestion('Name', '')
        ->expectsQuestion('Email', '')
        ->expectsQuestion('Password', 'test1234')
        ->expectsQuestion('Password confirmation', 'test1234')
        ->assertExitCode(Command::INVALID);
})->throws(ValidationException::class);

test('fails due to email validation error', function () {
    artisan(AddAdminUser::class)
        ->expectsQuestion('Name', 'Test')
        ->expectsQuestion('Email', 'test')
        ->expectsQuestion('Password', 'test1234')
        ->expectsQuestion('Password confirmation', 'test1234')
        ->assertExitCode(Command::INVALID);
})->throws(ValidationException::class);

test('fails due to password validation error', function () {
    artisan(AddAdminUser::class)
        ->expectsQuestion('Name', 'Test')
        ->expectsQuestion('Email', 'test')
        ->expectsQuestion('Password', 'test')
        ->expectsQuestion('Password confirmation', 'test')
        ->assertExitCode(Command::INVALID);
})->throws(ValidationException::class);

test('fails due to password confirmation error', function () {
    artisan(AddAdminUser::class)
        ->expectsQuestion('Name', 'Test')
        ->expectsQuestion('Email', 'test')
        ->expectsQuestion('Password', 'test1234')
        ->expectsQuestion('Password confirmation', 'test12345')
        ->assertExitCode(Command::INVALID);
})->throws(ValidationException::class);
