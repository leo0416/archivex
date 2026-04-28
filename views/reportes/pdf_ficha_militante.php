<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 20px; }
        .border-box { border: 2px solid #2c3e50; padding: 20px; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .qr-placeholder { float: right; width: 100px; height: 100px; border: 1px solid #ccc; text-align: center; font-size: 10px; }
        
        .section-title { background: #2c3e50; color: white; padding: 5px 10px; font-weight: bold; margin-top: 20px; }
        .data-row { margin: 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .label { font-weight: bold; color: #7f8c8d; font-size: 12px; }
        .value { font-size: 14px; color: #2c3e50; }
        
        .ubicacion-critica { 
            margin-top: 30px; background: #f8f9fa; border: 1px dashed #2c3e50; padding: 15px; text-align: center;
        }
        .cod-ubicacion { font-size: 20px; font-weight: bold; letter-spacing: 1px; color: #27ae60; }
    </style>
</head>
<body>
    <div class="border-box">
        <div class="header">
            <div class="qr-placeholder"><br>Espacio para<br>Sello u Holograma</div>
            <h1 style="margin:0;">FICHA DE EXPEDIENTE</h1>
            <p style="color: #7f8c8d;">Sistema de Gestión de Archivo Central - Archivex</p>
        </div>

        <div class="section-title">DATOS PERSONALES</div>
        <div class="data-row">
            <span class="label">NOMBRE COMPLETO:</span><br>
            <span class="value"><?php echo htmlspecialchars($militante['nombre_completo'] ?? 'No indicado'); ?></span>
        </div>
        <div class="data-row">
            <span class="label">DOCUMENTO DE IDENTIDAD (CI):</span><br>
            <span class="value"><?php echo htmlspecialchars($militante['ci'] ?? 'No registrado'); ?></span>
        </div>

        <div class="section-title">UBICACIÓN FÍSICA EN ARCHIVO</div>
        <div class="ubicacion-critica">
            <p style="margin:0; font-size: 12px; color: #7f8c8d;">CÓDIGO DE LOCALIZACIÓN</p>
            <div class="cod-ubicacion">
                ESTANTE: <?php echo str_pad($militante['num_estante'] ?? '0', 2, '0', STR_PAD_LEFT); ?> 
                / CAJUELA: <?php echo $militante['cajuela'] ?? 'N/A'; ?> 
                / POSICIÓN: <?php echo $militante['posicion_global'] ?? 'N/A'; ?>
            </div>
        </div>

        <div style="margin-top: 50px; font-size: 10px; color: #95a5a6; text-align: center;">
            Este documento es una representación digital del expediente físico.<br>
            Fecha de impresión: <?php echo date('d/m/Y H:i'); ?>
        </div>
    </div>
</body>
</html>