<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

final class CustomAdminUser extends Authenticatable
{
    use HasRoles;

    protected $table = 'users';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'title',
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function guardName(): string
    {
        return adminium_permission_guard();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
