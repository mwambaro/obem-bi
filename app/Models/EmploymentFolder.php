<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentFolder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'phone_number',
        'highest_degree',
        'cv_mime_type',
        'cv_unique_file_path',
        'cover_letter_mime_type',
        'cover_letter_unique_file_path'
    ];
}
