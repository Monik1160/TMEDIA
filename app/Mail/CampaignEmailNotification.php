<?php

namespace App\Mail;

use App\Models\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $asunto;
    public $message;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($campaign,$asunto, $message)
    {
        $this->campaign = $campaign;
        $this->asunto = $asunto;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $campaign = $this->campaign;
        $asunto = $this->asunto;
        $description = $this->message;
        $details = $campaign->campaign_details()->get();
        $current_user = backpack_user();


        return $this->from($current_user->email)
            ->subject($asunto)
            ->view('email.campaign_email', compact("campaign", 'asunto', 'details', 'description', 'current_user'));
    }
}
