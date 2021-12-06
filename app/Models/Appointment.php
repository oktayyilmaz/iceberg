<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        'office_check_out_at' => 'datetime',
        'office_check_in_at' => 'datetime',
        'datetime' => 'datetime',
    ];

    public function contact()
    {
        return $this->hasMany(Contact::class, 'id', 'contact_id');
    }
}
