<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];
     /**
     * Get all books for the category.
     *
     * This defines a one-to-many relationship between the Category and Book models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function books()
    {
        return $this->hasMany(Book::class);
    }


}
