<?php

use Illuminate\Database\Seeder;
use App\Channel;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::create([
            'name' => 'Laravel 6.17',
            'slug' => str_slug('Laravel 6.17')
        ]);

        Channel::create([
            'name' => 'Vue.js 3',
            'slug' => str_slug('Vue.js 3')
        ]);

        Channel::create([
            'name' => 'CakePHP 3.8',
            'slug' => str_slug('CakePHP 3.8')
        ]);

        Channel::create([
            'name' => 'Symfony 1.4',
            'slug' => str_slug('Symfony 1.4')
        ]);
    }
}
