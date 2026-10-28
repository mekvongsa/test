<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    /** All permission names across assigned roles. */
    public function permissionNames(): array
    {
        return $this->roles()->with('permissions')->get()
            ->pluck('permissions')->flatten()->pluck('name')->unique()->all();
    }

    public function hasPermission(string $perm): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->roles->contains('name', 'super-admin')) {
            return true;
        }
        return in_array($perm, $this->permissionNames(), true);
    }

    public function hasRole(string $name): bool
    {
        return $this->roles->contains('name', $name);
    }
}
