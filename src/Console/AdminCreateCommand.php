<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Throwable;

final class AdminCreateCommand extends Command
{
    protected $signature = 'adminium:admin:create
        {--name= : The administrator name}
        {--email= : The administrator email address}
        {--password= : The administrator password (minimum 8 characters)}
        {--role=super_admin : The role assigned to the administrator}';

    protected $description = 'Create an Adminium administrator account';

    public function handle(): int
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');
        $role = $this->option('role');

        if (! $this->input->isInteractive()) {
            $missing = [];

            foreach (['name' => $name, 'email' => $email, 'password' => $password] as $option => $value) {
                if (! is_string($value) || $value === '') {
                    $missing[] = '--'.$option;
                }
            }

            if ($missing !== []) {
                $this->error('The following options are required in non-interactive mode: '.implode(', ', $missing));

                return self::FAILURE;
            }
        }

        if (! is_string($name) || $name === '') {
            $name = $this->ask('Name');
        }

        if (! is_string($email) || $email === '') {
            $email = $this->ask('Email address');
        }

        if (! is_string($role) || $role === '') {
            $role = $this->ask('Role', config('adminium.super_admin_role', 'super_admin'));
        }

        if (! is_string($password) || $password === '') {
            $password = $this->secret('Password');
            $confirmation = $this->secret('Confirm password');

            if ($password !== $confirmation) {
                $this->error('The password confirmation does not match.');

                return self::FAILURE;
            }
        }

        $name = mb_trim((string) $name);
        $email = Str::lower(mb_trim((string) $email));
        $role = mb_trim((string) $role);

        $model = adminium_user_model();

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique((new $model)->getTable(), 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        if (! Role::query()->where('name', $role)->where('guard_name', adminium_permission_guard())->exists()) {
            $this->error("The role [{$role}] does not exist. Run: php artisan adminium:install --seed");

            return self::FAILURE;
        }

        try {
            $user = DB::transaction(function () use ($model, $name, $email, $password, $role) {
                $user = $model::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => Hash::make($password),
                ]);

                if (! method_exists($user, 'assignRole')) {
                    throw new RuntimeException('The configured Adminium user model must use the Spatie HasRoles trait.');
                }

                call_user_func([$user, 'assignRole'], $role);

                return $user;
            });
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Administrator [{$user->getAttribute('email')}] created with role [{$role}].");

        return self::SUCCESS;
    }
}
