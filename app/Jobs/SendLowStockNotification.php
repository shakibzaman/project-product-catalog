<?php

namespace App\Jobs;

use App\Mail\LowStockMail;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $product;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        return $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        info('Mail send from here', [$this->product]);
        Mail::to('admin@example.com')->send(new LowStockMail($this->product));
    }
}
