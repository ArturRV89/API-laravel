<?php

namespace Database\Seeders;

use App\Models\Desk;
use App\Models\DeskList;
use App\Models\Card;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(10)->create();
         Desk::factory(20)->create();
         DeskList::factory(40)->create();
         Card::factory(20)->create();
         Task::factory(20)->create();
    }
}
