<?php

namespace Database\Seeders;

use App\Models\IAModel;
use Illuminate\Database\Seeder;

class IAModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IAModel::factory()->count(5)->create();
    }
}
