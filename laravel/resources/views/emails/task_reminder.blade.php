
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Tarea</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .email-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .task-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .task-info p {
            margin: 12px 0;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .task-info strong {
            color: #667eea;
            font-weight: 600;
        }
        .task-title {
            font-size: 22px;
            color: #2d3748;
            margin: 0 0 15px 0;
            font-weight: 700;
        }
        .footer-message {
            text-align: center;
            padding: 25px;
            background-color: #f8f9fa;
            font-size: 18px;
            color: #4a5568;
            font-weight: 500;
        }
        .emoji {
            font-size: 24px;
        }
        .time-badge {
            display: inline-block;
            background-color: #edf2f7;
            padding: 6px 12px;
            border-radius: 6px;
            color: #2d3748;
            font-weight: 500;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h2><span class="emoji">🔔</span> Recordatorio de Tarea</h2>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="task-title">📝 {{ $task->title }}</p>

            <div class="task-info">
                @if($task->description)
                    <p><strong>📄 Descripción:</strong><br>{{ $task->description }}</p>
                @endif

                <p>
                    <strong>⏰ Inicio:</strong>
                    <span class="time-badge">{{ $task->start_time ? $task->start_time->format('H:i d/m/Y') : 'No definida' }}</span>
                </p>

                <p>
                    <strong>⏱️ Fin:</strong>
                    <span class="time-badge">{{ $task->end_time ? $task->end_time->format('H:i d/m/Y') : 'No definida' }}</span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-message">
            ¡No olvides completarla! <span class="emoji">💪</span>
        </div>
    </div>
</body>
</html>
