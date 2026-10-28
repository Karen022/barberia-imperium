<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de contraseña</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color: #f4f4f4; padding: 30px 15px;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width: 600px; background-color: #ffffff;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #18181b; padding: 30px 20px;">
                            <h1 style="margin: 0; color: #facc15; font-size: 25px;">
                                BARBERÍA IMPERIUM
                            </h1>

                            <p style="margin: 8px 0 0; color: #ffffff; font-size: 13px;">
                                Recuperación de contraseña
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 35px 30px; color: #333333;">

                            <h2 style="margin-top: 0; font-size: 21px;">
                                ¡Hola!
                            </h2>

                            <p style="font-size: 15px; line-height: 1.7;">
                                Recibimos una solicitud para restablecer la contraseña
                                de tu cuenta en Barbería Imperium.
                            </p>

                            <p style="font-size: 15px; line-height: 1.7;">
                                Para crear una nueva contraseña, haz clic en el siguiente botón:
                            </p>

                            <!-- Button -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 30px auto;">
                                <tr>
                                    <td align="center" style="background-color: #facc15; border-radius: 5px;">
                                        <a href="{{ $url }}" style="display: inline-block; padding: 14px 28px;
                                            color: #18181b; text-decoration: none;
                                            font-weight: bold; font-size: 15px;">
                                            Restablecer contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; line-height: 1.6; color: #666666;">
                                Este enlace estará disponible durante {{ $count }} minutos.
                                Si no solicitaste restablecer tu contraseña, puedes ignorar
                                este mensaje. Tu contraseña actual seguirá siendo la misma.
                            </p>

                            <p style="font-size: 13px; line-height: 1.6; color: #666666;">
                                Si el botón no funciona, copia y pega este enlace en tu navegador:
                            </p>

                            <p style="font-size: 12px; line-height: 1.6; word-break: break-all;">
                                <a href="{{ $url }}" style="color: #a16207;">
                                    {{ $url }}
                                </a>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f4f4f4; padding: 20px;
                            color: #777777; font-size: 12px; line-height: 1.6;">
                            <p style="margin: 0;">
                                © {{ date('Y') }} Barbería Imperium.
                            </p>
                            <p style="margin: 5px 0 0;">
                                Este es un correo automático, por favor no respondas.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>