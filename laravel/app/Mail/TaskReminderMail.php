<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Crear una nueva instancia del mensaje.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Construir el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🔔 Recordatorio de Tarea')
                    ->view('emails.task_reminder');
    }
}
