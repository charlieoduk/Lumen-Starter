<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = 3;
        
            $user_ids = [1, 2, 3];

            $names = ["Charles Oduk", "Dominic Bett", "Madge Kinyanjui"];
    
            $emails = ["oduk@andela.com", "bett@andela.com",
                "kinyanjui@andela.com"];

            $passwords = ["password", "password", "password"];
    
            for ($i = 0; $i < $limit; $i++) {
                DB::table('users')->insert([
                    'id' => $user_ids[$i],
                    'name' => $names[$i],
                    'email' => $emails[$i],
                    'password' => Hash::make($passwords[$i])
                ]);
            }
    }
}
