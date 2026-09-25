<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuevo mensaje - Barbería Imperium</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif; color: #222;">

    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden;">

        {{-- Encabezado --}}
        <div style="background-color: #18181b; padding: 30px; text-align: center;">
            <h1 style="margin: 0; color: #facc15; font-size: 24px;">
                Barbería Imperium
            </h1>

            <p style="margin: 8px 0 0; color: #d4d4d8; font-size: 14px;">
                Nuevo mensaje de contacto
            </p>
        </div>

        {{-- Contenido --}}
        <div style="padding: 30px;">

            <h2 style="margin-top: 0; font-size: 20px; color: #18181b;">
                Datos del cliente
            </h2>

            <div style="margin-bottom: 20px;">
                <p style="margin: 0 0 6px; font-size: 13px; color: #71717a;">
                    NOMBRE
                </p>

                <p style="margin: 0; font-size: 16px; font-weight: bold;">
                    {{ $name }}
                </p>
            </div>

            <div style="margin-bottom: 25px;">
                <p style="margin: 0 0 6px; font-size: 13px; color: #71717a;">
                    EMAIL
                </p>

                <p style="margin: 0; font-size: 16px;">
                    {{ $email }}
                </p>
            </div>

            {{-- Mensaje --}}
            <div style="border-top: 1px solid #e4e4e7; padding-top: 25px;">

                <p style="margin: 0 0 10px; font-size: 13px; color: #71717a;">
                    MENSAJE
                </p>

                <div style="background-color: #fafafa; border-left: 4px solid #facc15; padding: 18px; border-radius: 6px;">
                    <p style="margin: 0; font-size: 15px; line-height: 1.6; white-space: pre-line;">
                        {{ $contactMessage }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Pie --}}
        <div style="background-color: #f4f4f5; padding: 20px; text-align: center;">
            <p style="margin: 0; font-size: 12px; color: #71717a;">
                Este mensaje fue enviado desde el formulario de contacto
                de Barbería Imperium.
            </p>
        </div>

    </div>

</body>

</html>