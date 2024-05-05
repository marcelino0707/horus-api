<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Voucher::create([
            'id' => '2e67ac38-4fb0-4042-8968-5baf5b62c8ee',
            'nama' => 'Merdeka 25%',
            'kategori' => 'Cashback',
        ]);

        Voucher::create([
            'id' => 'd2deb5d2-f2e9-4948-beb9-258b7a470435',
            'nama' => 'Electronic Sales',
            'kategori' => 'Flash Sale',
        ]);

        Voucher::create([
            'id' => '4ad7575a-0979-46d4-a02e-de16fc62f601',
            'nama' => 'Member baru 50%',
            'kategori' => 'Diskon',
        ]);

        Voucher::create([
            'id' => '5a9efbfb-394c-4f6c-804b-4335cf92a80c',
            'nama' => 'Pet Shopy',
            'kategori' => 'Gratis Ongkir',
        ]);

        Voucher::create([
            'id' => '9113a345-85b0-413e-9ac9-59a131827039',
            'nama' => 'Beauty Day',
            'kategori' => 'Cashback',
        ]);

        Voucher::create([
            'id' => '151cc811-d362-47f9-8a2f-0a9cc062de70',
            'nama' => 'TokoSebelah 100K',
            'kategori' => 'Diskon',
        ]);

        Voucher::create([
            'id' => '5706d7dd-a57a-42e0-82da-4815ea0f1667',
            'nama' => 'COD JOGJA',
            'kategori' => 'Gratis Ongkir',
        ]);
    }
}
