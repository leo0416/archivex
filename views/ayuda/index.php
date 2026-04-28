<div class="usuarios-container fade-in">
    <div class="card-ficha help-header" style="padding: 30px; margin-bottom: 25px; border-bottom: 4px solid var(--primary-color);">
        <div class="header-content">
            <div class="icon-box">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="text-box">
                <h1 class="title">Centro de Asistencia ArchiveX</h1>
                <p class="subtitle">Toda la información necesaria para gestionar el sistema en un solo lugar.</p>
            </div>
        </div>
    </div>

    <div class="help-main-grid">
        
        <div class="help-left-col">
            
            <div class="card-ficha">
                <div class="card-title"><i class="fas fa-book"></i> Guía Rápida de Operación</div>
                <div style="padding: 10px;">
                    <div class="guides-grid">
                        <div class="guide-item blue">
                            <h4>1. Registro</h4>
                            <p>Crea expedientes desde el menú 'Nuevo'. El sistema asigna estante automáticamente.</p>
                        </div>
                        <div class="guide-item green">
                            <h4>2. Búsqueda</h4>
                            <p>Usa filtros por CI o Nombre para localizar la ubicación física exacta.</p>
                        </div>
                        <div class="guide-item red">
                            <h4>3. Bajas</h4>
                            <p>Al eliminar, el espacio queda libre y el registro va a la papelera.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-ficha" style="margin-top: 25px;">
                <div class="card-title"><i class="fas fa-comments"></i> Preguntas Frecuentes</div>
                <div style="padding: 10px;">
                    <?php
                    $faqs = [
                        "¿Cómo sé en qué estante quedó un militante?" => "Al finalizar el registro o buscarlo en el listado, el sistema muestra una columna llamada 'Ubicación' con el Estante, Fila y Celda correspondiente.",
                        "¿Puedo recuperar un expediente borrado?" => "Sí, siempre que no haya sido eliminado definitivamente de la Papelera. Al restaurarlo, se le asignará una nueva ubicación si la anterior ya fue ocupada.",
                        "¿Quién puede crear nuevos usuarios?" => "Solo el personal con rol de Administrador tiene acceso al módulo de gestión de usuarios y backups."
                    ];
                    foreach($faqs as $q => $a): ?>
                        <details class="faq-details">
                            <summary>
                                <?php echo $q; ?> <i class="fas fa-chevron-down"></i>
                            </summary>
                            <div class="faq-answer">
                                <?php echo $a; ?>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="help-sidebar">
            <div class="card-ficha sticky-sidebar">
                <div class="card-title" style="background: #2d3748; color: white;"><i class="fas fa-headset"></i> Soporte Técnico</div>
                <div style="padding: 20px;">
                    <p style="font-size: 0.9rem; color: #4a5568; margin-bottom: 20px;">Si experimentas errores técnicos o fallos en la base de datos:</p>
                    
                    <div class="contact-item">
                        <label>Correo de Asistencia</label>
                        <div class="contact-val">
                            <i class="fas fa-envelope" style="color: var(--primary-color);"></i> soporte@archivex.local
                        </div>
                    </div>

                    <div class="contact-item">
                        <label>Horario de Atención</label>
                        <div class="contact-val">
                            <i class="fas fa-clock" style="color: #38a169;"></i> Lun - Vie (8:00 - 16:00)
                        </div>
                    </div>

                    <div class="alert-box">
                        <p><i class="fas fa-exclamation-triangle"></i> <strong>Importante:</strong> Limpie el historial antes de reportar.</p>
                    </div>

                    <button class="print-btn" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir página
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Layout Base */
    .header-content { display: flex; align-items: center; gap: 20px; }
    .icon-box { background: var(--primary-color); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
    .icon-box i { font-size: 2rem; }
    .title { margin: 0; font-size: 1.8rem; color: #2d3748; }
    .subtitle { margin: 0; color: #718096; }

    .help-main-grid { display: grid; grid-template-columns: 1fr 350px; gap: 25px; align-items: start; }
    .guides-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
    
    .guide-item { padding: 15px; border-radius: 10px; border-left: 4px solid; background: #f8fafc; }
    .guide-item h4 { margin: 0 0 5px 0; }
    .guide-item.blue { border-left-color: #3182ce; } .guide-item.blue h4 { color: #2b6cb0; }
    .guide-item.green { border-left-color: #38a169; } .guide-item.green h4 { color: #2f855a; }
    .guide-item.red { border-left-color: #e53e3e; } .guide-item.red h4 { color: #c53030; }

    /* FAQ Styles */
    .faq-details { margin-bottom: 10px; border: 1px solid #edf2f7; border-radius: 8px; }
    .faq-details summary { padding: 12px; font-weight: 600; cursor: pointer; color: #2d3748; outline: none; display: flex; justify-content: space-between; align-items: center; list-style: none; }
    .faq-details summary i { font-size: 0.7rem; opacity: 0.5; transition: 0.3s; }
    .faq-answer { padding: 0 15px 15px; color: #718096; font-size: 0.9rem; line-height: 1.5; }
    details[open] summary i { transform: rotate(180deg); }

    /* Sidebar & Extras */
    .contact-item { margin-bottom: 20px; }
    .contact-item label { display: block; font-size: 0.75rem; font-weight: 700; color: #a0aec0; text-transform: uppercase; }
    .contact-val { display: flex; align-items: center; gap: 10px; color: #2d3748; font-weight: 600; }
    .alert-box { background: #fff5f5; border: 1px solid #feb2b2; padding: 15px; border-radius: 8px; }
    .alert-box p { margin: 0; color: #c53030; font-size: 0.8rem; }
    .print-btn { width: 100%; margin-top: 20px; padding: 10px; border: 1px solid #cbd5e0; border-radius: 8px; background: white; cursor: pointer; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; transition: 0.3s; }
    .print-btn:hover { background: #f7fafc; }

    /* --- RESPONSIVE QUERIES --- */
    @media (max-width: 1024px) {
        .help-main-grid { grid-template-columns: 1fr; }
        .help-sidebar { order: -1; } /* El soporte sube en tablets para visibilidad */
        .sticky-sidebar { position: static; }
    }

    @media (max-width: 600px) {
        .help-header { padding: 20px; }
        .header-content { flex-direction: column; text-align: center; }
        .title { font-size: 1.4rem; }
        .guides-grid { grid-template-columns: 1fr; }
    }
</style>