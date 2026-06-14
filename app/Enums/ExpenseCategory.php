<?php

namespace App\Enums;


enum ExpenseCategory: string
{
    case Fuel        = 'Fuel';
    case Salary      = 'Salary';
    case OfficeRent  = 'Office Rent';
    case Transport   = 'Transport';
    case Maintenance = 'Maintenance';
    case Internet    = 'Internet';
    case Electricity = 'Electricity';
    case Other       = 'Other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
