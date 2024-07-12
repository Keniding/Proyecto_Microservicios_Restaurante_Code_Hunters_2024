<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
<div class="container">
    <div class="image-container">
        <img src="../../assets/icons/tomato.png" alt="Tomato Mascot" class="mascot">
    </div>
    <div class="login-container">
        <div class="avatar">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/user-male-circle.png" alt="Avatar">
        </div>
        <form id="loginForm">
            <label for="dni"></label><input id="dni" name="dni" type="text" placeholder="DNI" required>
            <label for="password"></label><input id="password" name="password" type="password" placeholder="Password" required>
            <div class="remember-me">
                <label>
                    <input type="checkbox"> Remember me
                </label>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
            <a href="#" class="forgot-password">Forgot Password?</a>
        </form>
        <a class="register-btn" href="../register/registro.php">Regístrate</a>
    </div>
</div>

<script type="module" src="login.js"></script>
</body>
</html>
