<?php
session_start();
require_once 'conexion.php';
$conn = conectarBD();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($nombre && $contrasena) {
        $stmt = $conn->prepare("SELECT id_usuario, nombre, contrasena FROM usuarios WHERE nombre = ? LIMIT 1");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Nombre de usuario no encontrado.";
        }

        $stmt->close();
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@3.3.0/tsparticles.bundle.min.js"></script>
<script>
  window.onload = () => {
    tsParticles.load("estrellitas", {
      background: { color: { value: "transparent" } },
      fpsLimit: 60,
      interactivity: {
        events: { onHover: { enable: true, mode: "repulse" }, resize: true },
        modes: { repulse: { distance: 100, duration: 0.4 } }
      },
      particles: {
        color: { value: "#ffffff" },
        links: { color: "#ffffff", distance: 150, enable: true, opacity: 0.3, width: 1 },
        collisions: { enable: false },
        move: { direction: "none", enable: true, outModes: { default: "bounce" }, random: false, speed: 1, straight: false },
        number: { density: { enable: true, area: 800 }, value: 80 },
        opacity: { value: 0.5 },
        shape: { type: "circle" },
        size: { value: { min: 1, max: 3 } }
      },
      detectRetina: true
    });
  }
</script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión | NutriMath</title>
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(to right, #f3e8ff, #e6ccff);
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
      flex-direction: column;
      padding: 20px;
      box-sizing: border-box;
    }

    .login-container {
      background: #fff0ff;
      padding: 40px 30px 30px;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(174, 117, 255, 0.25);
      max-width: 400px;
      width: 100%;
      text-align: center;
      z-index: 2;
    }

    h2 {
      color: #b37de2;
      margin-top: 10px;
      margin-bottom: 1rem;
    }

    label {
      display: block;
      margin-top: 1rem;
      margin-bottom: 0.5rem;
      color: #5e3a7d;
      font-weight: 600;
      text-align: left;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border-radius: 12px;
      border: 2px solid #dabaff;
      background: #fefaff;
      font-size: 1rem;
      box-sizing: border-box;
    }

    .btn {
      display: block;
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

    .info {
      margin-top: 1rem;
      text-align: center;
      font-size: 0.9rem;
      color: #6d4c86;
    }

    .info a {
      color: #b37de2;
      text-decoration: none;
    }

    .info a:hover {
      text-decoration: underline;
    }

    .error {
      background: #ffe8f4;
      color: #a20044;
      padding: 10px;
      border: 1px solid #f7b7d3;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 1rem;
    }

    .gatito-esquina {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 400px;
      max-width: 80vw;
      z-index: 1;
      animation: infinito 20s linear infinite;
    }

    @keyframes infinito {
      0% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
      100% { transform: translateY(0); }
    }

    .mensaje-amor {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.9rem;
      color: #5e3a7d;
      max-width: 600px;
      padding: 0 20px;
      z-index: 2;
    }

    @media (max-width: 500px) {
      .login-container {
        padding: 30px 20px;
      }
      h2 {
        font-size: 1.2rem;
      }
      .btn {
        font-size: 0.95rem;
        padding: 10px;
      }
      .gatito-esquina {
        width: 250px;
      }
    }
  </style>
</head>
<body>
  <img src="./img/gatito_saludando.gif" alt="gatito saludando" class="gatito-esquina">
  <div class="login-container">
  <img src="./img/logo.png" alt="Logo NutriMath" style="max-width: 150px; margin-bottom: 10px;">
    <h2>✨ Inicia sesión en NutriMath</h2>
    <?php if (isset($error)): ?>
      <p class="error">⚠️ <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="" method="post">
      <label for="nombre">👤 Nombre de usuario:</label>
      <input type="text" name="nombre" id="nombre" required>

      <label for="contrasena">🔒 Contraseña:</label>
      <input type="password" name="contrasena" id="contrasena" required>

      <button type="submit" class="btn">Iniciar sesión</button>
    </form>
    <p class="info">¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
  </div>
  <p class="mensaje-amor">
    💌 Si mi novia te dio acceso a esta web, déjame decirte que es gratis, no le creas, soy buena persona y la creé especialmente para ella porque estoy enamorada 😽 y además, solo quiero que sepan que será la futura esposa de la Ingeniera Lady Johana Torres. Gracias. 💜
  </p>
</body>
</html>