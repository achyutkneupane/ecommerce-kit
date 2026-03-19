<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Models\Scopes\LowerRoleOnly;
use Awcodes\Gravatar\Gravatar;
use Database\Factories\UserFactory;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[ScopedBy(LowerRoleOnly::class)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

    public function canAccessPanel(Panel $panel): bool
    {
        // Update this method to control access to the Filament panel.
        // Here, we allow access only to users with the Developer or Admin role.
        return in_array($this->role, [
            UserRole::Developer,
            UserRole::Admin,
            UserRole::Writer,
        ]);
    }

    /** @returns array<int, UserRole> */
    public function lowerRoles(): array
    {
        return match (auth()->user()->role) {
            UserRole::Developer => [UserRole::Developer, UserRole::Admin, UserRole::Writer, UserRole::User],
            UserRole::Admin => [UserRole::Admin, UserRole::Writer, UserRole::User],
            UserRole::Writer => [UserRole::Writer, UserRole::User],
            UserRole::User => [UserRole::User],
        };
    }

    /** @returns bool */
    public function isLowerInRole(): bool
    {
        if (auth()->user()->role === UserRole::Developer) {
            return true;
        }

        return in_array($this->role, auth()->user()->lowerRoles());
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
            'role' => UserRole::class,
        ];
    }

    protected function avatar(): Attribute
    {
        $gravatar = Gravatar::get(
            email: $this->email,
            size: 200,
            default: 'initials'
        );

        return Attribute::make(
            get: fn (): string => $gravatar,
        );
    }
}
