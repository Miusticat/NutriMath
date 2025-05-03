<?php
session_start();
require_once 'conexion.php';
$conn = conectarBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($nombre && $correo && $contrasena) {
        // Verificar si ya existe ese correo
        $check = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $check->bind_param("s", $correo);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Ya existe una cuenta con ese correo.";
        } else {
            $hash = password_hash($contrasena, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $correo, $hash);

            if ($stmt->execute()) {
                $_SESSION['id_usuario'] = $stmt->insert_id;
                $_SESSION['nombre'] = $nombre;
                header("Location: index.php");
                exit;
            } else {
                $error = "Error al registrar. Intenta más tarde.";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro | NutriMath</title>
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(120deg, #f9e0ff, #e6ccff);
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
      flex-direction: column;
      position: relative;
      overflow-x: hidden;
    }

    .registro-container {
      background: #fff0ff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 12px 40px rgba(174, 117, 255, 0.3);
      max-width: 400px;
      width: 100%;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    h2 {
      color: #b37de2;
      margin-bottom: 1rem;
    }

    label {
      display: block;
      text-align: left;
      margin-top: 1rem;
      color: #5e3a7d;
      font-weight: 600;
    }

    input[type="text"],
    
    input[type="password"],
    input[type="email"] {
      width: 100%;
      padding: 12px;
      border-radius: 12px;
      border: 2px solid #dabaff;
      background: #fefaff;
      font-size: 1rem;
    }

    .btn {
      width: 100%;
      margin-top: 1.5rem;
      background-color: #c083f5;
      color: white;
      padding: 12px;
      font-size: 1rem;
      font-weight: bold;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #a565da;
    }

    .error {
      background: #ffe8f4;
      color: #a20044;
      padding: 10px;
      border: 1px solid #f7b7d3;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .mensaje-final {
      margin-top: 2rem;
      font-size: 0.9rem;
      color: #6a468a;
      max-width: 600px;
      text-align: center;
      padding: 0 20px;
    }

    .gatito-fondo {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 350px;
      max-width: 60vw;
      z-index: 1;
    }
  </style>
</head>
<body>
  <img src="./img/gatito_panzon.png" alt="gatito saludando" class="gatito-fondo">
  <div class="registro-container">
  <img src="./img/logo.png" alt="Logo NutriMath" style="max-width: 150px; margin-bottom: 10px;">
    <h2>🐾 Crea tu cuenta en NutriMath</h2>
    <?php if (isset($error)): ?>
      <p class="error">⚠️ <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">

    
      
      <label for="nombre">👤 Nombre de usuario:</label>
      <input type="text" name="nombre" id="nombre" required>

      <label for="correo">📧 Correo electrónico:</label>
      <input type="email" name="correo" id="correo" required>


      <label for="contrasena">🔒 Crea una contraseña:</label>
      <input type="password" name="contrasena" id="contrasena" required>

      <button type="submit" class="btn">Registrarme</button>
    </form>
  </div>
  <p class="mensaje-final">
    🌟 Esta web fue creada con mucho amor para mi novia hermosa. Si estás leyendo esto y no eres ella... Significa que te esta tratando de estafar con mi web personalizada para ella. 💜
  </p>
</body>
</html>