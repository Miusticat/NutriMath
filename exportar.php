<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Exportar Alimentos</title>
  <link rel="stylesheet" href="./css/exportar.css">
  <script src="https://cdn.jsdelivr.net/npm/docx@7.1.0/build/index.umd.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body>



  <main class="container">
    <h1>Tabla Nutricional</h1>
    <button id="exportarWord">Exportar a Word</button>


    <div class="tabla-container">
      <table id="tabla-alimentos">
        <thead>
          <tr>
            <th>Código RINAs</th>
            <th>Nombre del alimento</th>
            <th>Peso (g o cc)</th>
            <th>Kcal</th>
            <th>Proteína (g)</th>
            <th>Grasa total (g)</th>
            <th>Grasa saturada (g)</th>
            <th>Monoinsaturada (g)</th>
            <th>Poliinsaturada (g)</th>
            <th>Colesterol (mg)</th>
            <th>Carbohidratos (g)</th>
            <th>Fibra dietética (g)</th>
            <th>Calcio (mg)</th>
            <th>Fósforo (mg)</th>
            <th>Hierro (mg)</th>
            <th>Cinc (mg)</th>
            <th>Vitamina A (ER)</th>
            <th>Vitamina B1 (mg)</th>
            <th>Vitamina B2 (mg)</th>
            <th>Niacina (mg)</th>
            <th>B6 (mg)</th>
            <th>Ácido fólico (µg)</th>
            <th>Vitamina B12 (ug)</th>
            <th>Vitamina C (mg)</th>
          </tr>
        </thead>
        <tbody id="cuerpo-tabla">
          <!-- Se llenará con JS -->
        </tbody>
      </table>
    </div>
  </main>

  <script src="./js/exportar.js"></script>
</body>
</html>
