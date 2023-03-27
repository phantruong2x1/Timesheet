<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollCostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payroll_cost')->insert(
            [
                'type_cost'     => 'Last Checkin',
                'salary_type'   => 'Reward',
                'cost'          => 0,
            ],
            [
                'type_cost'     => 'Early Checkout',
                'salary_type'   => 'Reward',
                'cost'          => 0,
            ],
            [
                'type_cost'     => 'Unauthorized Absences',
                'salary_type'   => 'Reward',
                'cost'          => 0,
            ],
            [
                'type_cost'     => 'Petrol Support',
                'salary_type'   => 'Penalty',
                'cost'          => 0,
            ],
            [
                'type_cost'     => 'Other Penalty',
                'salary_type'   => 'Penalty',
                'cost'          => 0,
            ],
        );
    }
}
