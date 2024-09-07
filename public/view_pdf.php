<?php
// Definir la ruta de la carpeta 'pdfs'
$pdfsDir = __DIR__ . '/pdfs/';

// Listar todos los archivos en la carpeta 'pdfs'
echo '<h2>Contenido de la carpeta pdfs:</h2>';
$files = scandir($pdfsDir);
echo '<ul>';
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo '<li>' . htmlspecialchars($file) . '</li>';
    }
}
echo '</ul>';
?>
