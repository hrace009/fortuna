<?php

use Illuminate\Database\Seeder;

class TicketStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_statuses')->insert([
            'name' => 'Pendente',
            'color' => 'yellow',
        ]);

        DB::table('ticket_statuses')->insert([
            'name' => 'Aguardando Staff',
            'color' => 'red',
        ]);

        DB::table('ticket_statuses')->insert([
            'name' => 'Aguardando Jogador',
            'color' => 'brand',
        ]);

        DB::table('ticket_statuses')->insert([
            'name' => 'Resolvido',
            'color' => 'green',
        ]);
    }
}
