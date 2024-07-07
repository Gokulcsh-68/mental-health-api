<?php

namespace App\Jobs;

use App\Services\RemidioApis\FundusCameraApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class RemidioFundusImportJob
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $remedio = new FundusCameraApiService();
        return $remedio->getElementFromDownloadQueue();
    }
}
