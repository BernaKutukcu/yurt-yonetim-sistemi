<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Kullanıcının öğrenci profili
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // Kullanıcının personel profili
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    // Kullanıcının yayınladığı duyurular
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    // Admin mi kontrol et
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Öğrenci mi kontrol et
    public function isStudent()
    {
        return $this->role === 'student';
    }

    // Personel mi kontrol et
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    // Veli mi kontrol et
    public function isParent()
    {
        return $this->role === 'parent';
    }
}
