<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    /**
     * Genera URL de WhatsApp Web con mensaje prellenado
     *
     * @param string $number Número con código de país (ejemplo: 34612345678)
     * @param string $message Mensaje a enviar
     * @return string URL de WhatsApp Web
     */
    public static function generateWhatsAppUrl($number, $message)
    {
        // Limpia el número (quita espacios, guiones, etc)
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);

        // Codifica el mensaje para URL
        $encodedMessage = urlencode($message);

        // Retorna URL de WhatsApp Web
        return "https://wa.me/{$cleanNumber}?text={$encodedMessage}";
    }

    /**
     * Genera el mensaje de recordatorio
     */
    public static function generateReminderMessage($task)
    {
        $startTime = $task->start_time ? $task->start_time->format('H:i') : 'No definida';
        $endTime = $task->end_time ? $task->end_time->format('H:i') : 'No definida';

        $message = "🔔 *Recordatorio de Tarea*\n\n";
        $message .= "📝 *Tarea:* {$task->title}\n";

        if ($task->description) {
            $message .= "📄 *Descripción:* {$task->description}\n";
        }

        $message .= "⏰ *Hora inicio:* {$startTime}\n";
        $message .= "⏱️ *Hora fin:* {$endTime}\n";
        $message .= "\n¡No olvides completarla! 💪";

        return $message;
    }

    /**
     * Envía el mensaje automáticamente usando CallMeBot
     *
     * @param string $message Mensaje a enviar
     * @return string Respuesta de la API
     */
    public static function sendWhatsAppMessage($message)
    {
        $phone  = env('CALLMEBOT_PHONE');   // tu número en formato internacional (ej: 34612345678)
        $apikey = env('CALLMEBOT_API_KEY'); // tu API Key de CallMeBot

        $url = "https://api.callmebot.com/whatsapp.php";

        $response = Http::get($url, [
            'phone'  => $phone,
            'text'   => $message,
            'apikey' => $apikey
        ]);

        return $response->body();
    }
}
