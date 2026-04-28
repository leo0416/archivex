<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #2c3e50; text-transform: uppercase; }
        .info { font-size: 10px; color: #7f8c8d; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #2c3e50; color: white; padding: 10px; font-size: 12px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 10px; font-size: 11px; }
        .text-center { text-align: center; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; }
        .bg-ok { background-color: #e6f6ec; color: #27ae60; }
        .bg-full { background-color: #fff5f5; color: #e74c3c; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #95a5a6; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Archivex - Reporte de Inventario</div>
        <div class="info">Generado el: <?php echo date('d/m/Y H:i A'); ?> | Por: <?php echo $_SESSION['usuario_nombre']; ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nro. Estante</th>
                <th class="text-center">Capacidad Total</th>
                <th class="text-center">Espacios Ocupados</th>
                <th class="text-center">Disponibles</th>
                <th class="text-center">Uso (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($estantes as $e): 
                $disponibles = $e['capacidad'] - $e['ocupados'];
                $porcentaje = ($e['ocupados'] / $e['capacidad']) * 100;
                $clase = ($porcentaje > 90) ? 'bg-full' : 'bg-ok';
            ?>
            <tr>
                <td>Estante #<?php echo $e['numero_consecutivo']; ?></td>
                <td class="text-center"><?php echo $e['capacidad']; ?></td>
                <td class="text-center"><?php echo $e['ocupados']; ?></td>
                <td class="text-center"><?php echo $disponibles; ?></td>
                <td class="text-center">
                    <span class="badge <?php echo $clase; ?>">
                        <?php echo round($porcentaje, 1); ?>%
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Archivex - Sistema de Gestión de Expedientes | Hoja 1 de 1
    </div>
</body>
</html>