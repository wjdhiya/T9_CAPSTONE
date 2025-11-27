<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
    
    public function up()
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->dropColumn('tahun_akademik');
        });
    }

    public function down()
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->year('tahun_akademik')->nullable();
        });
    }
}
