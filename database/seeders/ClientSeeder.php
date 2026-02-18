<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Tech Solutions Inc.',
                'fiscal_code' => 'B12345678',
                'email' => 'contact@techsolutions.com',
                'phone' => '555-0101',
                'send_address' => '123 Innovation Drive'
            ],
            [
                'name' => 'Global Retail Corp',
                'fiscal_code' => 'A87654321',
                'email' => 'info@globalretail.net',
                'phone' => '555-0202',
                'send_address' => '456 Market St'
            ],
            [
                'name' => 'Alice Johnson',
                'fiscal_code' => '77665544X',
                'email' => 'alice.j@email.com',
                'phone' => '555-0303',
                'send_address' => '789 Pine Avenue'
            ],
            [
                'name' => 'Future Systems',
                'fiscal_code' => 'B99887766',
                'email' => 'billing@futuresystems.com',
                'phone' => '555-0404',
                'send_address' => '321 Tech Park'
            ],
            [
                'name' => 'David Miller',
                'fiscal_code' => '11223344L',
                'email' => 'david.m@freelance.org',
                'phone' => '555-0505',
                'send_address' => '654 Oak Lane'
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
