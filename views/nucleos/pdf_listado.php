<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de Militantes - <?= htmlspecialchars($nucleo['nombre']) ?></title>
        <link rel="icon" type="image/png" href="public/img/favicon.png">
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 22px;
        }
        .header p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 12px;
        }
        .nucleo-info {
            background: #f8fafc;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #2c3e50;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 11px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Archivex - Listado de Militantes por Núcleo</h1>
        <p>Generado el <?= date('d/m/Y H:i:s') ?> por <?= $_SESSION['usuario_nombre'] ?? 'Sistema' ?></p>
    </div>

    <div class="nucleo-info">
        <strong>Núcleo seleccionado:</strong> <?= htmlspecialchars($nucleo['numero_nucleo'] . ' - ' . $nucleo['nombre']) ?>
    </div>

    <?php if (empty($militantes)): ?>
        <p>No hay militantes activos en este núcleo.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Ubicación (Estante / Cajuela / Posición)</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($militantes as $m): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($m['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($m['ci']) ?></td>
                    <td>
                        <?= $m['num_estante'] ?? 'N/A' ?> / 
                        <?= $m['cajuela'] ?? 'N/A' ?> / 
                        <?= $m['posicion_global'] ?? 'N/A' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top: 15px; font-size: 10px;">Total de militantes: <?= count($militantes) ?></p>
    <?php endif; ?>

    <div class="footer">
        Archivex - Sistema de Gestión de Expedientes | Página 1
    </div>
</body>
</html>