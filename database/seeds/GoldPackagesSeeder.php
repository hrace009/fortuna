<?php

use App\Models\GoldPackage;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class GoldPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->getPackages()->each(function ($packs) {
            GoldPackage::create($packs);
        });
    }

    public function getPackages(): Collection
    {
        return collect([
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote Starter',
                'package_price' => 10,
                'package_gold_amount' => 5000,
            ],
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote Fit',
                'package_price' => 20,
                'package_gold_amount' => 10000,
            ],
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote Mega',
                'package_price' => 40,
                'package_gold_amount' => 20000,
            ],
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote Generoso',
                'package_price' => 75,
                'package_gold_amount' => 37500,
            ],
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote Elite',
                'package_price' => 100,
                'package_gold_amount' => 50000,
            ],
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote Power',
                'package_price' => 250,
                'package_gold_amount' => 125000,
            ],
            [
                'package_id' => Str::orderedUuid(),
                'package_name' => 'Pacote GG',
                'package_price' => 500,
                'package_gold_amount' => 250000,
            ],
        ]);
    }
}
