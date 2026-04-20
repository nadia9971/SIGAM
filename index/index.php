<?php
include 'conexion.php'; 

$mensaje = "";
$tipo_alerta = "";

//LÓGICA DE PROCESAR FORMULARIOS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // REGISTRO
    if (isset($_POST['registro'])) {
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $pass_raw = $_POST['password_reg']; 
        $pass_encriptada = password_hash($pass_raw, PASSWORD_BCRYPT); 
        

        $prefijo = strtoupper(substr($pass_raw, 0, 1));
        $rol = "";
        
        if ($prefijo === 'M') { $rol = 'medico'; }
        else if ($prefijo === 'R') { $rol = 'recepcion'; }
        else if ($prefijo === 'A') { $rol = 'admin'; }

        if ($rol === "") {
            $mensaje = "Error: La contraseña debe iniciar con M, R o A.";
            $tipo_alerta = "danger";
        } else {
            $sql = "INSERT INTO usuarios (nombres, apellidos, usuario, password, rol) 
                    VALUES ('$nombres', '$apellidos', '$correo', '$pass_encriptada', '$rol')";
            $ejecutar = @mysqli_query($conexion, $sql);

            if ($ejecutar) {
                $mensaje = "Cuenta creada con éxito para $nombres ($rol). Ya puedes iniciar sesión.";
                $tipo_alerta = "success";
            } else {
                //error 1062 (Duplicado)
                if (mysqli_errno($conexion) == 1062) {
                    $mensaje = "El correo <strong>$correo</strong> ya está registrado.";
                } else {
                    $mensaje = "Error inesperado: " . mysqli_error($conexion);
                }
                $tipo_alerta = "danger";
            }
        }
    }


    // LOGIN
    if (isset($_POST['login'])) {
        $user = $_POST['Usuario'];
        $pass_escrita = $_POST['password'];

        $query = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $resultado = mysqli_query($conexion, $query);

        if ($row = mysqli_fetch_assoc($resultado)) {
            if (password_verify($pass_escrita, $row['password'])) {
                $login_exitoso = true;
                $user = $row['usuario']; 
                $pass = $pass_escrita;   
            } else {
                $mensaje = "Contraseña incorrecta.";
                $tipo_alerta = "danger";
                $login_exitoso = false;
            }
        } else {
            $mensaje = "El usuario no existe.";
            $tipo_alerta = "danger";
            $login_exitoso = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.G.A.M. - Acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
             background: linear-gradient(150deg, #BBFCC6, #BBFCF9); 
             height: 100vh; 
             display: flex; 
             align-items: center; 
             justify-content: center; 
             font-family: 'Courier New', Courier, monospace; 
            }
        .login-card { 
            background: white;
             border-radius: 15px; 
             box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
             width: 100%; 
             max-width: 450px; 
             padding: 30px; 
            }
        .btn-primary { 
            background-color: #8a91f3; 
            border: none; 
            width: 100%; 
            font-weight: bold;
         }
        .btn-link { 
            color: #fe0505; 
            text-decoration: none;
             font-size: 0.9rem; 
             font-weight: bold;
            }
        .input-group-text { 
            background: none; 
            color: #8a91f3; 
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1 class="text-center mb-4 border-bottom pb-2">S.I.G.A.M.</h1>

    <?php if($mensaje != ""): ?>
        <div class="alert alert-<?php echo $tipo_alerta; ?> p-2 text-center small"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <div id="login-view" <?php if(isset($_POST['registro'])) echo 'style="display:none;"'; ?>>
        <form method="post" action="">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" id="Usuario" name="Usuario" class="form-control" placeholder="Correo" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary mb-3">Iniciar Sesión</button>
            <p class="text-center small">¿No tienes cuenta? 
            <a href="javascript:void(0)" onclick="toggleView()" class="btn-link">Regístrate aquí</a></p>
        </form>
    </div>

    <div id="register-view" <?php if(isset($_POST['registro'])) echo 'style="display:block;"'; else echo 'style="display:none;"'; ?>>
        <form method="post" action="">
            <div class="row">
                <div class="col-md-6 mb-3"><input type="text" name="nombres" class="form-control" placeholder="Nombres" required></div>
                <div class="col-md-6 mb-3"><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" required></div>
            </div>
            <div class="mb-3"><input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required></div>
            <div class="mb-3">
                <input type="password" name="password_reg" class="form-control" placeholder="Contraseña (M, R o A)" required>
            </div>
            <button type="submit" name="registro" class="btn btn-primary mb-3">Crear Cuenta</button>
            <p class="text-center small"><a href="javascript:void(0)" onclick="toggleView()" class="btn-link">Volver al Login</a></p>
        </form>
    </div>
</div>

<script>
    function toggleView() {
        const login = document.getElementById('login-view');
        const reg = document.getElementById('register-view');
        if(login.style.display === 'none') {
            login.style.display = 'block';
            reg.style.display = 'none';
        } else {
            login.style.display = 'none';
            reg.style.display = 'block';
        }
    }

    const loginExitoso = <?php echo isset($login_exitoso) && $login_exitoso ? 'true' : 'false'; ?>;
    const usuarioLogueado = "<?php echo isset($user) ? $user : ''; ?>";
    const passLogueada = "<?php echo isset($pass) ? $pass : ''; ?>";

    if (loginExitoso) {
        const prefijo = passLogueada.charAt(0).toUpperCase();
        if (prefijo === 'M') { alert("Bienvenido Dr. " + usuarioLogueado); window.location.href = "medico.php"; } 
        else if (prefijo === 'R') { alert("Acceso: Recepción"); window.location.href = "recepcion.php"; } 
        else if (prefijo === 'A') { alert("Acceso: Admin"); window.location.href = "admin.php"; } 
        else { alert("❌ Error: Prefijo no válido."); }
    }
</script>
</body>
</html>