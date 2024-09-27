<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Roles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable  implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

   /**
 * Define a many-to-many relationship between the User and Roles models.
 *
 * This method returns the roles associated with a user.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */
    public function roles()
    {
        return $this->belongsToMany(Roles::class);
    }

/**
 * Retrieve all unique permissions assigned to the user through roles.
 *
 * This method fetches all the permissions from the roles associated with the user,
 * flattens the collection, and returns only the unique permissions based on the 'id'.
 *
 * @return \Illuminate\Support\Collection
 */
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    /**
 * Check if the user has a specific permission.
 *
 * This method checks if any of the user's roles contain the specified permission.
 * It first loads the roles and permissions if they are not already loaded.
 *
 * @param string $permission The name of the permission to check.
 * @return bool True if the user has the specified permission, otherwise false.
 */
    public function hasPermission($permission)
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles.permissions');
        }

        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }

        return false;
    }

}
