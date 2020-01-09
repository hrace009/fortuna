<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_categories')->insert([
            'name' => 'Suporte Geral',
        ]);

        DB::table('ticket_categories')->insert([
            'name' => 'Doações',
        ]);
    }
}
