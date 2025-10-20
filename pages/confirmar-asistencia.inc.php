<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar asistencia</title>
    <script src="https://kit.fontawesome.com/4b8b7b9b7a.js" crossorigin="anonymous"></script>
</head>
<body style="background:#f4f6f9; font-family:Arial,sans-serif;">
    <table align="center" width="900" cellpadding="0" cellspacing="0" style="margin-top:60px; background:#fff; border-radius:32px; box-shadow:0 8px 40px #0002;">
        <tr>
            <td style="background:linear-gradient(90deg,#007bff 0%,#00c6ff 100%); color:#fff; border-radius:32px 32px 0 0; text-align:center; padding:72px 40px 32px 40px;">
                <span style="font-size:2.5rem; color:#fff;"><i class="fas fa-calendar-check"></i></span>
                <h2 style="margin:0; font-weight:700; letter-spacing:0.5px; font-size:2rem;">Confirmar asistencia</h2>
            </td>
        </tr>
        <tr>
            <td style="padding:72px 40px 48px 40px; text-align:center;">
                <?php if (isset($error) && $error): ?>
                    <div style="background:#ff4d4f; color:#fff; font-weight:bold; border-radius:8px; padding:12px 0; margin-bottom:24px;">Error: <?= htmlspecialchars($error) ?></div>
                <?php else: ?>
                    <div style="font-size:1.15em; color:#333; margin-bottom:24px;">¿Asistirás a la cita?</div>
                    <table align="center" cellpadding="0" cellspacing="0" style="margin:auto;">
                        <tr>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="asistira" value="1" />
                                    <input type="hidden" name="id_cita" value="<?= htmlspecialchars($id_cita) ?>" />
                                    <input type="hidden" name="id_contacto" value="<?= htmlspecialchars($id_contacto) ?>" />
                                    <button type="submit" style="background:#28a745; color:#fff; font-size:1.1em; font-weight:bold; border:none; border-radius:2rem; padding:14px 32px; margin:0 10px; box-shadow:0 2px 8px #28a74544; cursor:pointer; transition:background 0.2s;">
                                        <i class="fas fa-check" style="margin-right:8px;"></i> Sí asistiré
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="asistira" value="2" />
                                    <input type="hidden" name="id_cita" value="<?= htmlspecialchars($id_cita) ?>" />
                                    <input type="hidden" name="id_contacto" value="<?= htmlspecialchars($id_contacto) ?>" />
                                    <button type="submit" style="background:#dc3545; color:#fff; font-size:1.1em; font-weight:bold; border:none; border-radius:2rem; padding:14px 32px; margin:0 10px; box-shadow:0 2px 8px #dc354544; cursor:pointer; transition:background 0.2s;">
                                        <i class="fas fa-times" style="margin-right:8px;"></i> No asistiré
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</body>
</html>