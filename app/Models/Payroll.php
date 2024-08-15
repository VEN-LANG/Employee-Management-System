<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
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

    /**
     * Resource form fields for payroll forms
     * @return array
     */
    public static function resourceForm(): array
    {
        return[
            Forms\Components\Select::make('employee_id')
                ->label('Employee')
                ->relationship('employee', 'user.name') // Assuming 'name' is the attribute you want to display
                ->required()
                ->options(function (callable $get) {
                    return Employee::all()->pluck('user.name', 'id');
                })
                ->searchable()
                ->reactive(),

                Forms\Components\TextInput::make('basic_salary')
                    ->label('Basic Salary')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('allowances')
                    ->label('Allowances')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('deductions')
                    ->label('Deductions')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('net_salary')
                    ->label('Net Salary')
                    ->numeric()
                    ->disabled() // Disabled as it's auto-calculated
                    ->required(),

                Forms\Components\DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->required(),
            ];
    }
}
