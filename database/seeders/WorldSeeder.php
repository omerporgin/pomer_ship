<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileList = glob("./database/sql/*");


        foreach($fileList as $path){
            \DB::unprepared(file_get_contents($path));
            $this->command->info($path.' file seeded!');
        }
    }
}
