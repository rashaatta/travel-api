<?php
namespace App\Jobs;

use App\Services\FirebaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deviceToken;
    protected $title;
    protected $body;

    /**
     * Create a new job instance.
     */
    public function __construct($deviceToken, $title, $body)
    {
        $this->deviceToken = $deviceToken;
        $this->title = $title;
        $this->body = $body;
    }


    /**
     * Execute the job.
     */
    public function handle()
    {
        // Send push notification using Firebase
        $firebaseNotification = new FirebaseNotification();
        $firebaseNotification->sendNotification($this->deviceToken, $this->title, $this->body);


    }
}
