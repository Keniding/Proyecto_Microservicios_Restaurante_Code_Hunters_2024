<link rel="stylesheet" href="../../../../css/styles.css">
<link rel="stylesheet" href="modulos/carta/carta.css">
<link rel="stylesheet" href="../../../../css/message.css">
<link rel="stylesheet" href="../../../../css/datos.css">

<?php
include('../../../../includes/header.php');

include('modulos/carta/carta.php');
?>
<div class="container_chat">
    <?php

    include('../../../../includes/datos.php');
    include('../../../message/fulsocket/index.html');
    ?>
</div>


<?php
include('../../../../includes/footer.php');
?>

<!--<script type="module" src="modulos/carta/carta.js"></script>-->

<script src="/assets/dist/carta.bundle.js"></script>

<script src="http://localhost:8100/assets/dist/chat.bundle.js"></script>

<script src="/assets/dist/title.bundle.js"></script>