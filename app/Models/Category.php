<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relasi ke model Thread
     * Satu Category dapat memiliki banyak Thread
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Getter untuk ID kategori
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Getter untuk nama kategori
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Getter untuk slug kategori
     */
    public function slug(): string
    {
        return $this->slug;
    }
}
