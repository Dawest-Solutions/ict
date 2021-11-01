<?php

namespace Database\Seeders;

use App\Models\Reward;
use App\Models\RewardInDay;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Reward::factory()
      ->has(RewardInDay::factory()->count(1))
      ->count(50)
      ->create();
  }
}
