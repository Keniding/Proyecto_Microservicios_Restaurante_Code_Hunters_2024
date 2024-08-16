<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" href="../../../../css/styles.css">
    <link rel="stylesheet" href="../../../../css/message.css">
    <link rel="stylesheet" href="css/message.css">
    <link rel="stylesheet" href="../../../../css/datos.css">
</head>
<body>
<?php
include('../../../../includes/header.php');

include('../../../../includes/datos.php');

include('../../../message/fulsocket/index.html');
    ?>
</div>

<div class="container  view-container">
    <div class="card-container">
        <a href="modulos/categoria/categoria.php" class="card">Gestionar Categorias de Comida</a>
        <a href="modulos/comida/comida.php" class="card">Gestionar de Platos de Comida</a>
    </div>
</div>


<?php
include('../../../../includes/footer.php');
?>

<script src="http://localhost:8100/assets/dist/chat.bundle.js"></script>
<script src="/assets/dist/title.bundle.js"></script>
</body>
</html>



