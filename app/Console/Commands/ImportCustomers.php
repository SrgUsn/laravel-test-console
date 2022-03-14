<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import customers from .csv file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFilePath = storage_path('app/public/customers.csv');

        if (!file_exists($csvFilePath) || ($handle = fopen($csvFilePath, "r")) == false) {
            $this->error('csv file not found');
            return;
        }

        $customers = \App\Models\Customer::pluck('email')->toArray();
        $customerArray = [];
       
        while (($row = fgetcsv($handle)) !== false) {
            if (!in_array($row[2], $customers)) {
                $customerArray[] = [
                    'name' => $row[0],
                    'surname' => $row[1],
                    'email' => $row[2],
                    'age' => $row[3],
                    'location' => $row[4],
                    'country_code' => $row[5],
                    // 'created_at' => now()->toDateTimeString(),
                    // 'updated_at' => now()->toDateTimeString(),
                ];
            }
        }
        fclose($handle);

        //remove header
        $customerArray = array_slice($customerArray, 1);        
        
        $parts = array_chunk($customerArray, 20);
        $countParts = count($parts);

        foreach ($parts as $index=>$part) {
            \App\Jobs\ProcessCustomerImport::dispatch($part, $countParts, ++$index);
        }
    }
}
