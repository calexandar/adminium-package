<?php

declare(strict_types=1);

namespace Calexandar\Adminium\UserManagment;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Calexandar\Adminium\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kra8\Snowflake\HasShortflakePrimary;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Calexandar\Adminium\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, HasShortflakePrimary, InteractsWithMedia, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'title',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
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

    protected static function newFactory(): UserFactory
    {
        return new UserFactory;
    }

    /**
     * Get the attributes that should be cast.
     *
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
