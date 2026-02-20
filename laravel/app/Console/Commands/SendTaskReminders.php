<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Envía recordatorios de tareas por WhatsApp';

    public function handle()
    {
        $now = now();

        $tasks = Task::where('completed', false)
            ->where('notification_sent', false)
            ->whereNotNull('start_time')
            ->whereNotNull('whatsapp_number')
            ->whereBetween('start_time', [$now, $now->copy()->addMinutes(15)])
            ->get();

        foreach ($tasks as $task) {
            $this->sendWhatsAppMessage($task);

            // Marcar como enviada
            $task->notification_sent = true;
            $task->save();

            $this->info("✅ Recordatorio enviado: {$task->title}");
        }

        $this->info("Total: " . $tasks->count() . " recordatorios");

        return 0;
    }

    private function sendWhatsAppMessage($task)
    {
        // TU API KEY de CallMeBot (la que te dieron)
        $apiKey = 'TU_API_KEY_AQUI';

        // Tu número con código de país (sin +)
        $phone = $task->whatsapp_number;

        // Mensaje
        $startTime = $task->start_time->format('H:i');
        $endTime = $task->end_time ? $task->end_time->format('H:i') : 'No definida';

        $message = "🔔 *Recordatorio de Tarea*\n\n";
        $message .= "📝 Tarea: {$task->title}\n";

        if ($task->description) {
            $message .= "📄 {$task->description}\n";
        }

        $message .= "⏰ Inicio: {$startTime}\n";
        $message .= "⏱️ Fin: {$endTime}\n";
        $message .= "\n¡No olvides completarla! 💪";

        // Enviar a CallMeBot
        try {
            $response = Http::get('https://api.callmebot.com/whatsapp.php', [
                'phone' => $phone,
                'text' => $message,
                'apikey' => $apiKey
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            $this->error("Error al enviar: " . $e->getMessage());
            return false;
        }
    }
}
