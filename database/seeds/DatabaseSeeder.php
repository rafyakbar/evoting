<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call('UserSeeder');
        $this->call('JurusanProdiSeeder');
        $this->call('PengaturanSeeder');
        $this->call('MahasiswaSeeder');
        $this->call('CalonHmjSeeder');
        $this->call('CalonDpmSeeder');
        $this->call('CalonBemSeeder');
        $this->call('PemilihanBemSeeder');
        $this->call('PemilihanDpmSeeder');
        $this->call('PemilihanHmjSeeder');
    }
}
