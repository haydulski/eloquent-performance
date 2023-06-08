<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         Company::factory(10000)->create()->each(function($company){
             $company->users()->createMany(User::factory(10)->make()->toArray());
         });

        $user = User::find(10000);
        $user->update([
            'first_name' => 'Elon',
            'last_name' => 'Musk',
            'email' => 'elon.musk@twitter.com',
        ]);
        $user->company->update([
            'name' => 'Twitter',
        ]);
    }
}
