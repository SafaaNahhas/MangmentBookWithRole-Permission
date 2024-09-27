<?php

namespace App\Models;

use App\Models\User;
use App\Models\Permissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roles extends Model
{
    use HasFactory;
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];



    /**
     * Define a many-to-many relationship between the Role and User models.
     *
     * This method returns the users associated with a role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

     /**
     * Define a many-to-many relationship between the Role and Permissions models.
     *
     * This method returns the permissions associated with a role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permissions::class);
    }
     /**
     * Boot function for the model.
     *
     * This method is automatically called when the model is initialized. It adds a deleting event
     * listener that detaches the relationships between roles, permissions, and users when a role
     * is deleted. This ensures that the pivot table records are removed.
     *
     * @return void
     */
    protected static function boot()
  {
    parent::boot();

    static::deleting(function ($role) {
        $role->permissions()->detach();

        $role->users()->detach();
      });
  }

}
