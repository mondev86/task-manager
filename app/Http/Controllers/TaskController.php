<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        // Filtro por estado (pending, in_progress, o completed)
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            if (in_array($status, ['pending', 'in_progress', 'completed'])) {
                $query->where('status', $status);
            }
        }

        // Filtro por fecha de inicio (start_time)
        if ($request->has('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('start_time', '<=', $request->end_date);
        }

        // Filtro por fecha específica (igual a)
        if ($request->has('date')) {
            $query->whereDate('start_time', $request->date);
        }

        // Paginación
        $perPage = $request->input('per_page', 10); // Default 10 por página
        $perPage = min($perPage, 100); // Máximo 100

        $tasks = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'whatsapp_number' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        // Default status a pending si no se especifica
        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }
        
        // Default priority a low si no se especifica
        if (!isset($validated['priority'])) {
            $validated['priority'] = 'low';
        }

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'description' => 'nullable',
            'completed' => 'boolean',
            'status' => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
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
     * WhatsApp: abre WhatsApp Web con mensaje prellenado
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
            'message' => 'Abre WhatsApp para enviar el recordatorio'
        ]);
    }

    /**
     * Enviar correo a cualquier dirección
     */
    public function sendEmailReminder(Request $request, Task $task)
    {
        // Validar que venga un email
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $user = $request->user();

            // Enviar al email proporcionado
            Mail::to($validated['email'])->send(new TaskReminderMail($task));

            // Registrar quién envió el email
            Log::info('Email enviado', [
                'usuario_id' => $user->id,
                'usuario_nombre' => $user->name,
                'usuario_email' => $user->email,
                'email_destino' => $validated['email'],
                'tarea_id' => $task->id,
                'tarea_titulo' => $task->title,
                'fecha' => now()->toDateTimeString(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recordatorio enviado a ' . $validated['email']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar correo: ' . $e->getMessage()
            ], 500);
        }
    }
}
