<?php include 'logica/logica.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Orden de Venta</title>
</head>
<body>
<div class="container main-container no-select">

    <div class="container-colum">
        <h1>Agregar Orden de Venta</h1>
        <form id="orderForm">
            <div class="form-group">
                <div id="foodName"></div>
                <label for="foodId" class="no-select">ID de la Comida:</label>
                <input type="text" id="foodId" name="foodId" value="<?php echo getId() ?>" required readonly>
            </div>

            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="unitPrice" class="no-select">Precio Unitario:</label>
                <input type="number" id="unitPrice" name="unitPrice" step="0.01" required readonly>
            </div>

            <div class="form-group">
                <label for="totalPrice" class="no-select">Precio Total:</label>
                <input type="number" id="totalPrice" name="totalPrice" step="0.01" required readonly>
            </div>

            <div class="form-group ajuste">
                <div class="description">Descripción de la Orden:</div>
                <div class="tags-container" id="tags-container"></div>
                <div class="selected-tags-container" id="selected-tags-container"></div>
            </div>

            <button id="AgregarOrden" type="submit">Agregar Orden</button>
        </form>
    </div>

    <div class="form-group no-select">
        <h1>Datos extra</h1>
        <label for="facturaId" class="no-select">ID de la Factura:</label>
        <input type="text" id="facturaId" name="facturaId" required readonly>

        <label for="customerDni">DNI del Cliente:</label>
        <input type="text" id="customerDni" name="customerDni" required>
        <button id="verifyButton">Verificar</button>
        <div id="result"></div>


        <div class="modal-container">
            <div id="mesaNumber"></div>
            <button id="openModalBtn">Ver Sistema de Mesas</button>
        </div>
    </div>
</div>

<script>
    window.foodId = '<?php echo getId() ?>';
</script>

<script src="js/mesas.js"></script>
<script src="/assets/dist/orden.bundle.js"></script>
<script src="/assets/dist/etiquetas.bundle.js"></script>
<script src="/assets/dist/dni.bundle.js"></script>
<script src="/assets/dist/order.bundle.js"></script>
</body>
</html>
