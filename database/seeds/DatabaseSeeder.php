<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TicketCategorySeeder::class);
        $this->call(TicketStatusesSeeder::class);
        $this->call(PaymentGatewaySeed::class);
        $this->call(GoldPackagesSeeder::class);
    }
}
