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
        'company',
        'role',
        'divisi',
        'plant',
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        if ($this->isAdmin()) return true;
        if (!$this->role) return false;

        return $this->role->permissions()
            ->where('name', $permission)
            ->exists();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isOrangSales()
    {
        $role = in_array($this->role,['staff','manager','customer']);
        $div = in_array($this->divisi,['sales']);
        return ($role && $div);
    }

    public function requestProjects()
    {
        return $this->hasMany(RequestProject::class,'customer_id');
    }

    public function activityHistory()
    {
        return $this->hasMany(HistoryActivity::class,'user_id');
    }

    // Relasi ke account profile
    public function account()
    {
        return $this->hasOne(\App\Models\Account::class, 'user_id');
}

}
