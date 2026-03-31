<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Tarea</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; }
        .task-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .task-desc { color: #6b7280; margin-bottom: 15px; }
        .time { color: #6b7280; font-size: 14px; }
        .footer { background: #1f2937; color: white; padding: 15px; text-align: center; border-radius: 0 0 8px 8px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Recordatorio de Tarea</h1>
    </div>
    <div class="content">
        <div class="task-title">{{ $task->title }}</div>
        @if($task->description)
            <div class="task-desc">{{ $task->description }}</div>
        @endif
        
        @if($task->start_time)
            <div class="time">
                <strong>Hora de inicio:</strong> {{ $task->start_time->format('H:i') }}
            </div>
        @endif
        
        @if($task->end_time)
            <div class="time">
                <strong>Hora de fin:</strong> {{ $task->end_time->format('H:i') }}
            </div>
        @endif
    </div>
    <div class="footer">
        <p>Task Manager - No olvides completar tu tarea</p>
    </div>
</body>
</html>
