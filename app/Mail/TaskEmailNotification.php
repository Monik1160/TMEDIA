<?php

namespace App\Mail;

use App\Models\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $subject;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task, $subject, $message)
    {
        $this->task = $task;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $task = $this->task;
        $subject = $this->subject;
        $message_body = $this->message;
        $bus = Bus::find($task->bus_id);
        $zona_publicitaria = json_decode($task->zonapublicitaria_id);


        return $this->from('jpvillaplana@bamboo.cr')
            ->subject($subject)
            ->view('email.task_email', compact("task", 'subject', 'message_body','bus','zona_publicitaria'));
    }
}
