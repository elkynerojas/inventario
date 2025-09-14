<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
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

    /**
     * Relación con el rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return $this->rol && $this->rol->nombre === 'admin';
    }

    /**
     * Verificar si el usuario tiene solo lectura
     */
    public function soloLectura(): bool
    {
        return $this->rol && in_array($this->rol->nombre, ['estudiante', 'profesor']);
    }
}
