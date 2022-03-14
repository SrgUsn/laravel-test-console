<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateCustommers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:generate {count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate customers in csv file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = $this->argument('count');
        $customers = \App\Models\Customer::factory()->count($count ?? 1)->make();
        
        $csvFile = storage_path('app/public/customers.csv');
        $handle = fopen($csvFile, 'w');
        fputcsv($handle, [
            'name',
            'surname',
            'email',
            'age',
            'location',
            'country_code'
        ]);

        foreach ($customers as $item) {
            fputcsv($handle, [
                $item->name,
                $item->surname,
                $item->email,
                $item->age,
                $item->location,
                $item->country_code
            ]);
        }

        fclose($handle);
    }
}
