<?php

declare(strict_types=1);

use Calexandar\Adminium\Settings\SettingStore;

if (! function_exists('adminium_settings')) {
    /**
     * Resolve the Adminium settings store or read a single setting.
     *
     * @param  string|array<string, mixed>|null  $key
     */
    function adminium_settings(string|array|null $key = null, mixed $default = null): mixed
    {
        $store = app(SettingStore::class);

        if (is_null($key)) {
            return $store;
        }

        if (is_array($key)) {
            return $store->set($key);
        }

        return $store->get($key, $default);
    }
}

if (! function_exists('adminium_locales')) {
    /**
     * Resolve the locales offered by the Adminium translatable fields.
     *
     * @return array<int, string>
     */
    function adminium_locales(): array
    {
        $locales = config('app.available_locales') ?? config('adminium.available_locales', ['en']);

        if (! is_array($locales)) {
            return ['en'];
        }

        $locales = array_values(array_filter(
            $locales,
            static fn (mixed $locale): bool => is_string($locale) && $locale !== '',
        ));

        return $locales === [] ? ['en'] : $locales;
    }
}

if (! function_exists('adminium_logo')) {
    /**
     * Resolve the public URL for the configured Adminium logo.
     */
    function adminium_logo(): ?string
    {
        $logo = config('adminium.logo');

        if (! is_string($logo) || $logo === '') {
            return null;
        }

        return file_exists(public_path($logo)) ? asset($logo) : null;
    }
}

if (! function_exists('adminium_user_model')) {
    /**
     * Resolve and validate the configured Adminium authenticatable model.
     *
     * @return class-string<Illuminate\Database\Eloquent\Model&Illuminate\Contracts\Auth\Authenticatable>
     */
    function adminium_user_model(): string
    {
        $model = config('adminium.user_model');

        if (! is_string($model) || $model === '' || ! class_exists($model)) {
            throw new InvalidArgumentException('The configured Adminium user model does not exist.');
        }

        if (! is_subclass_of($model, Illuminate\Database\Eloquent\Model::class)) {
            throw new InvalidArgumentException('The configured Adminium user model must be an Eloquent model.');
        }

        if (! is_subclass_of($model, Illuminate\Contracts\Auth\Authenticatable::class)) {
            throw new InvalidArgumentException('The configured Adminium user model must be authenticatable.');
        }

        foreach (['hasRole', 'assignRole', 'hasPermissionTo', 'syncPermissions'] as $method) {
            if (! method_exists($model, $method)) {
                throw new InvalidArgumentException('The configured Adminium user model must use the Spatie HasRoles trait.');
            }
        }

        /** @var class-string<Illuminate\Database\Eloquent\Model&Illuminate\Contracts\Auth\Authenticatable> $model */
        return $model;
    }
}

if (! function_exists('adminium_guard')) {
    /**
     * Resolve the authentication guard used by Adminium.
     */
    function adminium_guard(): string
    {
        $guard = config('adminium.guard', 'adminium');

        return is_string($guard) && $guard !== '' ? $guard : 'adminium';
    }
}

if (! function_exists('adminium_auth_provider')) {
    /**
     * Resolve the authentication provider registered for Adminium.
     */
    function adminium_auth_provider(): string
    {
        $provider = config('adminium.auth_provider', 'adminium_users');

        return is_string($provider) && $provider !== '' ? $provider : 'adminium_users';
    }
}

if (! function_exists('adminium_permission_guard')) {
    /**
     * Resolve the guard used by Adminium roles and permissions.
     */
    function adminium_permission_guard(): string
    {
        $guard = config('adminium.permission_guard', 'web');

        return is_string($guard) && $guard !== '' ? $guard : 'web';
    }
}
