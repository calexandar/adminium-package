# Adminium

A modular Laravel admin panel and CMS built with Tailwind CSS.

## Requirements

- PHP 8.4+
- Laravel 12 or 13
- Node.js (for the frontend assets)
- A database supported by Laravel

## Install from a private GitHub repository

The standalone package is published from
[`calexandar/adminium-package`](https://github.com/calexandar/adminium-package).
Add the repository to the application that will consume the package:

```bash
composer config repositories.adminium vcs https://github.com/calexandar/adminium-package
composer require calexandar/adminium:dev-main
```

Composer needs access to the private repository:

- HTTPS: create a GitHub token and run
  `composer config --global github-oauth.github.com <token>`
- SSH: use `git@github.com:calexandar/adminium-package.git` and make sure your
  SSH key is available to Composer.

For a stable install, require a tagged release instead:

```bash
composer require calexandar/adminium:^1.0
```

## Install the panel

```bash
php artisan adminium:install
php artisan migrate
```

Or do everything in one command:

```bash
php artisan adminium:install --migrate --seed
```

The `--seed` flag creates the `super_admin` and `admin` roles plus every module
permission. It never creates a user account.

Create the first administrator after installing and migrating:

```bash
php artisan adminium:admin:create
```

The command prompts for the name, email address, password, and role. For
automation, pass the values explicitly:

```bash
php artisan adminium:admin:create \
    --name="Site Admin" \
    --email="admin@example.com" \
    --password="change-me-now" \
    --role=super_admin
```

Passwords must be at least eight characters and are never printed. The command
fails if the email already exists or the selected role has not been seeded.

`adminium:install` publishes the config and assets, publishes the Spatie
Permission and Media Library migrations when they are missing, and creates the
storage link. Re-running it does not require a new administrator account.

### Authentication

Adminium registers a dedicated `adminium` session guard and `adminium_users`
Eloquent provider. It does not replace the host application's default `web`
guard or `users` provider. If a guard or provider with the configured name
already exists, Adminium leaves it untouched.

The configured user model must be an Eloquent, authenticatable model that uses
the Spatie `HasRoles` trait. The Media Library `InteractsWithMedia` trait is
optional; without it the profile menu shows initials instead of an avatar.

To use your own model, either extend the package user or configure a compatible
model:

```php
'user_model' => App\Models\User::class,
```

The guard and provider names are configurable:

```php
'guard' => 'adminium',
'auth_provider' => 'adminium_users',
```

Set `guard` to `web` to reuse an existing application guard instead. Roles and
permissions are seeded against `permission_guard`, which defaults to `web`.

### Frontend assets

Add the package entries to `vite.config.js`:

```js
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'vendor/calexandar/adminium/resources/css/adminium.css',
    'vendor/calexandar/adminium/resources/js/adminium.js',
],
```

The package uses `@tailwindplus/elements` for the interactive components:

```bash
npm install @tailwindplus/elements
```

Point the package at those entries in `config/adminium.php`:

```php
'vite' => [
    'resources/css/app.css',
    'resources/js/app.js',
    'vendor/calexandar/adminium/resources/css/adminium.css',
    'vendor/calexandar/adminium/resources/js/adminium.js',
],
```

Then build the assets:

```bash
npm run build
```

Alternatively, keep the default `resources/css/app.css` and
`resources/js/app.js` entries and include the package sources from your own
entry files:

```css
/* resources/css/app.css */
@source '../../vendor/calexandar/adminium/src/**/*.blade.php';
```

```js
// resources/js/app.js
import '@tailwindplus/elements';
```

### Logo

Place your logo at `storage/app/public/images/adminium-logo.png` or publish the
config and change the `logo` path. When the file is missing, the panel renders
an "Adminium" wordmark instead of a broken image.

### Configuration

Publish the config file to change the route prefix, enable or disable modules,
or customize the logo and Vite entries:

```bash
php artisan vendor:publish --tag=adminium-config
```

### Sign in

Create an account with `php artisan adminium:admin:create`, then sign in at
`/admin/sign-in`. The route prefix can be changed with `ADMINIUM_ROUTE_PREFIX`
or the `route_prefix` config value.

## Local development

When developing the package from a monorepo, add a path repository instead:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/adminium",
        "options": {
            "symlink": true
        }
    }
]
```

## Testing

```bash
composer install
vendor/bin/pest
vendor/bin/phpstan analyse
vendor/bin/pint --test
```

## License

MIT. See [LICENSE](LICENSE).
