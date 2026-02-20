<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index()
    {
        return Task::orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'whatsapp_number' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $task = Task::create($validated);

        // Si tiene WhatsApp y horario, generar URL de notificación
        if ($task->whatsapp_number && $task->start_time) {
            $message = WhatsAppService::generateReminderMessage($task);
            $whatsappUrl = WhatsAppService::generateWhatsAppUrl(
                $task->whatsapp_number,
                $message
            );

            $task->whatsapp_url = $whatsappUrl;
        }

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'description' => 'nullable',
            'completed' => 'boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'whatsapp_number' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $task->update($validated);
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    /**
     * Obtener tareas pendientes con recordatorios
     */
    public function getPendingReminders()
    {
        $now = now();

        // Tareas que empiezan en los próximos 30 minutos
        $tasks = Task::where('completed', false)
            ->where('notification_sent', false)
            ->whereNotNull('start_time')
            ->whereNotNull('whatsapp_number')
            ->whereBetween('start_time', [$now, $now->copy()->addMinutes(30)])
            ->get();

        return response()->json($tasks);
    }

    /**
     * ✅ MODIFICADO: WhatsApp estándar (abre WhatsApp Web con mensaje prellenado)
     * Ya no usa CallMeBot - el usuario debe dar click en enviar manualmente
     */
    public function sendReminder(Task $task)
    {
        // Verificar que tenga número de WhatsApp
        if (!$task->whatsapp_number) {
            return response()->json([
                'success' => false,
                'message' => 'No hay número de WhatsApp configurado'
            ], 400);
        }

        // Generar URL de WhatsApp Web
        $message = WhatsAppService::generateReminderMessage($task);
        $whatsappUrl = WhatsAppService::generateWhatsAppUrl(
            $task->whatsapp_number,
            $message
        );

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'message' => '✅ Abre WhatsApp para enviar el recordatorio'
        ]);
    }

    /**
     * ✅ MODIFICADO: Enviar correo a cualquier dirección
     * Ahora acepta email en el request, no solo usa el de la tarea
     */
    public function sendEmailReminder(Request $request, Task $task)
    {
        // Validar que venga un email (puede ser diferente al de la tarea)
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // Enviar al email proporcionado
            Mail::to($validated['email'])->send(new TaskReminderMail($task));

            return response()->json([
                'success' => true,
                'message' => '📧 Recordatorio enviado a ' . $validated['email']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar correo: ' . $e->getMessage()
            ], 500);
        }
    }
}
