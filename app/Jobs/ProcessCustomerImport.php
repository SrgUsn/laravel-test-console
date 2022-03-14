<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCustomerImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customers;
    private $customersFailed = [];
    private $index;
    private $count;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $customers, int $count, int $index)
    {
        $this->customers = $customers;
        $this->count = $count;
        $this->index = $index;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "processing $this->index part of $this->count \n";

        foreach ($this->customers as $item) {
            $validator = \Illuminate\Support\Facades\Validator::make($item, [
                'name' => 'string|max:255',
                'surname' => 'string|max:255',
                'email' => 'email:rfc,dns',
                'age' => 'integer|between:18,99',
                'location' => 'string|max:255',
                'country_code' => 'string|max:3'
            ]);

            if ($validator->fails()) {
                $item['error'] = implode(',', $validator->errors()->keys());
                $this->customersFailed[] = $item;
            } else {
                \App\Models\Customer::create($item);
            }
        }

        // пишем в Excel все не созданные записи и причину их невалидности
        if (count($this->customersFailed) > 0) {            
            collect($this->customersFailed)->storeExcel(
                "failed_chunk_$this->index.xlsx",
                'public', //disk
                $writerType = null,
                $headings = true
            );
        }

        echo "done $this->index part of $this->count \n";

    }
}
