<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'phone', 
        'position', 
        'hire_date', 
        'status'
    ];
    //

    protected $dates = ['hire_date'];

    public function getFullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
