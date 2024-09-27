<?php

namespace App\Models;

use App\Models\Roles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permissions extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

      /**
     * Get the roles that have this permission.
     *
     * This defines a many-to-many relationship between the Permissions and Roles models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class);
    }
}
