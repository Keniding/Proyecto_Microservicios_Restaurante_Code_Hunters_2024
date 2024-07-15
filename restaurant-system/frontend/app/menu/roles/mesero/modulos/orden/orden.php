<?php include 'logica/logica.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Venta</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <div class="container-colum">
        <h1>Agregar Orden de Venta</h1>
        <form id="orderForm">
            <div class="form-group">
                <label for="facturaId">ID de la Factura:</label>
                <input type="text" id="facturaId" name="facturaId" required>
            </div>

            <div class="form-group">
                <label for="customerDni">DNI del Cliente:</label>
                <input type="text" id="customerDni" name="customerDni" required>
            </div>

            <div class="form-group">
                <label for="foodId">ID de la Comida:</label>
                <input type="text" id="foodId" name="foodId" value="<?php echo getId() ?>" required>
            </div>

            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="unitPrice">Precio Unitario:</label>
                <input type="number" id="unitPrice" name="unitPrice" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="totalPrice">Precio Total:</label>
                <input type="number" id="totalPrice" name="totalPrice" step="0.01" readonly>
            </div>

            <div class="form-group">
                <label for="description">Descripción de la Orden:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <button type="submit">Agregar Orden</button>
        </form>
    </div>
</div>

<script src="/assets/dist/orden.bundle.js"></script>
</body>
</html>
