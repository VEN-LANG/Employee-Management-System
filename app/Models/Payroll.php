<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'payment_date',
    ];

    /**
     * Get the employee associated with the payroll.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Calculate the net salary based on basic salary, allowances, and deductions.
     *
     * @return void
     */
    public function calculateNetSalary()
    {
        $this->net_salary = $this->basic_salary + $this->allowances - $this->deductions;
    }

    /**
     * Boot method for the model.
     * This ensures that the net salary is calculated whenever a payroll record is created or updated.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($payroll) {
            $payroll->calculateNetSalary();
        });
    }
}
