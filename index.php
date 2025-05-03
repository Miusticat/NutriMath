<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
  header("Location: login.php");
  exit;
}

$usuario = $_SESSION['nombre'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NutriMath</title>
  <link rel="stylesheet" href="./css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet" />
</head>
<body>
  <main class="container" role="main">
    <header>
      <img src="./img/gatito_corazon.png" alt="gatito tierno" class="michi-superior" />
      <img src="./img/gatito_panzon.png" alt="gatito comiendo" class="michi-lateral" />
      <h1>🐾 ¡Bienvenida a Michi... digo, NutriMath<?= $usuario ? ', doctora ' . htmlspecialchars($usuario) : ' princesa' ?>! 💜</h1>

      <img src="./img/logo.png" alt="Logo NutriMath" style="max-width: 250px; margin-bottom: 10px; display: block; margin-left: auto; margin-right: auto;">

      <p class="subtitulo">Una aplicación diseñada especialmente por tu amada novia, para ti... para hacerte la vida más fácil, princesa mía ✨</p>
    </header>

    <section class="section" aria-labelledby="analisis-alimento">
      <div class="row">
        <label for="nombre">🍽️ ¿Qué alimento vamos a analizar hoy?</label>
        <input type="text" id="nombre" placeholder="ej: Huevo cocido" />
      </div>
      <div class="row">
        <div class="col">
          <label for="pesoBase">📏 ¿Cuántos gramos tienes como referencia?</label>
          <input type="number" id="pesoBase" value="100" />
        </div>
        <div class="col">
          <label for="pesoNuevo">🧪 ¿Cuántos gramos quieres analizar?</label>
          <input type="number" id="pesoNuevo" value="100" />
        </div>
      </div>
    </section>

    <section class="scroll-table" aria-labelledby="tabla-nutrientes">
      <table id="tablaValores">
        <thead>
          <tr>
            <th>Nutriente</th>
            <th>Valor por el peso de referencia</th>
            <th>Valor para el peso que analizas</th>
          </tr>
        </thead>
        <tbody id="cuerpoTabla"></tbody>
      </table>
    </section>
<br><br>
    <div class="row center">
      <button class="btn" onclick="calcularValores()">✨ Calcular valores</button>
      <button class="btn" onclick="guardarAlimento()">💾 Guardar alimento</button>
      <a href="logout.php" class="cerrar-sesion">🚪 Cerrar sesión</a>

    </div>

    <div class="mensaje-bonito" id="mensajeBonito" aria-live="polite"></div>

    <section class="scroll-table" aria-labelledby="coleccion-alimentos">
      <h3 class="titulo-tabla">📚 Tu colección de alimentos analizados</h3>
      <table id="tablaAlimentos">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Peso base</th>
            <th>Peso analizado</th>
            <th>Nutrientes (recalculados)</th>
          </tr>
        </thead>
        <tbody id="listaAlimentos"></tbody>
      </table>
    </section>
  </main>

  <script>
    const nutrientes = [
      "Kilocalorías", "Proteína (g)", "Grasa Total (g)", "Grasa Saturada (g)", "Grasa Monoinsaturada (g)",
      "Grasa Poliinsaturada (g)", "Colesterol (mg)", "Carbohidratos (g)", "Fibra Dietética (g)",
      "Calcio (mg)", "Fósforo (mg)", "Hierro (mg)", "Zinc (mg)", "Vitamina A (ER)",
      "Vitamina B1 (mg)", "Vitamina B2 (mg)", "Niacina (mg)", "Vitamina B6 (mg)",
      "Ácido Fólico (ug)", "Vitamina B12 (ug)", "Vitamina C (mg)"
    ];

    const cuerpoTabla = document.getElementById("cuerpoTabla");
    const listaAlimentos = document.getElementById("listaAlimentos");
    const mensajeBonito = document.getElementById("mensajeBonito");

    nutrientes.forEach((nutriente, index) => {
      const fila = document.createElement("tr");
      fila.innerHTML = `
        <td>${nutriente}</td>
        <td><input type="number" id="nutriente-${index}" aria-label="Valor de ${nutriente}" /></td>
        <td id="resultado-${index}">-</td>
      `;
      cuerpoTabla.appendChild(fila);
    });

    function calcularValores() {
      const pesoBase = parseFloat(document.getElementById("pesoBase").value);
      const pesoNuevo = parseFloat(document.getElementById("pesoNuevo").value);
      const nombre = document.getElementById("nombre").value.trim();
      let caloriasFinal = "-";

      nutrientes.forEach((nutriente, index) => {
        const valor = parseFloat(document.getElementById(`nutriente-${index}`).value);
        const celdaResultado = document.getElementById(`resultado-${index}`);
        if (!isNaN(valor) && pesoBase > 0) {
          const calculado = (valor * pesoNuevo) / pesoBase;
          celdaResultado.innerText = calculado.toFixed(2);
          if (nutriente.includes("Kilocalorías")) caloriasFinal = calculado.toFixed(2);
        } else {
          celdaResultado.innerText = "-";
        }
      });

      mensajeBonito.innerText = nombre && caloriasFinal !== "-"
        ? `💖 ${pesoNuevo}g de "${nombre}" contienen aproximadamente ${caloriasFinal} kilocalorías.`
        : "✨ Ingresa el alimento y su información para ver la magia nutricional 💫";
    }

    function guardarAlimento() {
  const nombre = document.getElementById("nombre").value.trim();
  const pesoBase = parseFloat(document.getElementById("pesoBase").value);
  const pesoNuevo = parseFloat(document.getElementById("pesoNuevo").value);

  if (!nombre || isNaN(pesoBase) || isNaN(pesoNuevo)) {
    alert("Por favor, completa todos los campos.");
    return;
  }

  const nutrientesCalculados = {};
  nutrientes.forEach((nutriente, index) => {
    const resultado = parseFloat(document.getElementById(`resultado-${index}`).innerText);
    if (!isNaN(resultado)) {
      nutrientesCalculados[nutriente] = resultado;
    }
  });

  fetch("guardar_alimento.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      nombre: nombre,
      pesoBase: pesoBase,
      pesoNuevo: pesoNuevo,
      nutrientes: nutrientesCalculados
    })
  })
    .then(response => response.text())
    .then(msg => {
      alert(msg);
    })
    .catch(err => {
      console.error("Error al guardar:", err);
      alert("Hubo un error al guardar.");
    });
}


/* Mostrar historial */
function cargarHistorial() {
  fetch("obtener_alimentos.php")
    .then(res => res.json())
    .then(alimentos => {
      const tbody = document.getElementById("listaAlimentos");
      tbody.innerHTML = "";

      alimentos.forEach(alimento => {
        const tr = document.createElement("tr");

        const tdNombre = document.createElement("td");
        tdNombre.textContent = alimento.nombre;
        tr.appendChild(tdNombre);

        const tdBase = document.createElement("td");
        tdBase.textContent = alimento.peso_base + " g";
        tr.appendChild(tdBase);

        const tdAnalizado = document.createElement("td");
        tdAnalizado.textContent = alimento.peso_analizado + " g";
        tr.appendChild(tdAnalizado);

        const tdNutrientes = document.createElement("td");
        tdNutrientes.innerHTML = Object.entries(alimento.nutrientes)
          .map(([nombre, valor]) => `${nombre}: ${valor}g`)
          .join("<br>");
        tr.appendChild(tdNutrientes);

        tbody.appendChild(tr);
      });
    })
    .catch(err => {
      console.error("Error al cargar historial:", err);
    });
}

  </script>
  <script>
  window.onload = function() {
    cargarHistorial();
  };
</script>

</body>
</html>
