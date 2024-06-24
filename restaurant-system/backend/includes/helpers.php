<?php

function loadView($namespace, $view, $data = [])
{
    $viewPath = '';
    $layoutPath = __DIR__ . '/../views/layouts/master.php';

    if ($namespace === 'backend') {
        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
    } elseif ($namespace === 'frontend') {
        $viewPath = __DIR__ . '/../../frontend/views/' . str_replace('.', '/', $view) . '.php';
    }

    if (file_exists($viewPath)) {
        // Extraer datos para hacerlos disponibles en la vista
        extract($data);

        // Iniciar el buffer de salida
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Incluir la vista maestra
        require $layoutPath;
    } else {
        throw new Exception("View not found: " . $view);
    }
}
?>
