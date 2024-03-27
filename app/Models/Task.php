<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_user_id',
        'attachment_path', // Agrega el campo attachment_path al array fillable
        'status',
        
    ];

    // Relación con el usuario asignado
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // Relación con los comentarios de la tarea
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public $timestamps = false;
    
}
