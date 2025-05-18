<?php
namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Notifications\OtpVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendOtpNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function handle()
    {
        try {
            $this->user->notify(new OtpVerification($this->user->otp));
            event(new Registered($this->user));
            Log::info("Envoie de l'otp");
        } catch (\Exception $e) {
            Log::error("OTP sending failed: ".$e->getMessage());
            // Option: Réessayer plus tard ou notifier l'admin
        }
    }
}