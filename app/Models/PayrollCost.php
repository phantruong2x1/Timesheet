<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollCost extends Model
{
    use HasFactory;
    protected $table = 'payroll_costs';

    protected $fillable = ['type_cost','cost','salary_type'];
}
