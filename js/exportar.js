
function formatearValor(nutriente, valor) {
    const enteros = ["Kilocalorías", "Calcio (mg)", "Sodio (mg)", "Potasio (mg)", "Magnesio (mg)", "Vitamina A (ER)", "Ácido Fólico (ug)", "Vitamina C (mg)"];
    const unDecimal = ["Proteína (g)", "Grasa Total (g)", "Carbohidratos (g)", "Fibra Dietética (g)", "Hierro (mg)"];
    const dosDecimales = [
      "Grasa Saturada (g)", "Grasa Monoinsaturada (g)", "Grasa Poliinsaturada (g)", "Zinc (mg)",
      "Vitamina B1 (mg)", "Vitamina B2 (mg)", "Vitamina B3 (mg)", "Vitamina B6 (mg)", "Niacina (mg)", "Vitamina B12 (ug)"
    ];
  
    const num = parseFloat(valor);
    if (isNaN(num)) return valor || ""; // deja el texto original si no es número
  
    if (enteros.includes(nutriente)) return Math.round(num);
    if (unDecimal.includes(nutriente)) return num.toFixed(1);
    if (dosDecimales.includes(nutriente)) return num.toFixed(2);
  
    return valor; // fallback
  }

  

document.addEventListener("DOMContentLoaded", async () => {
    const cuerpo = document.getElementById("cuerpo-tabla");
  
    try {
      const res = await fetch("obtener_alimentos.php");
      const alimentos = await res.json();
  
      alimentos.forEach((a) => {
        const n = a.nutrientes || {};
  
        const fila = document.createElement("tr");
  
        fila.innerHTML = `
          <td>--</td>
          <td>${a.nombre || ""}</td>
          <td>${a.peso_analizado || "Sin dato"}</td>
         <td>${formatearValor("Kilocalorías", n["Kilocalorías"])}</td>
<td>${formatearValor("Proteína (g)", n["Proteína (g)"])}</td>
<td>${formatearValor("Grasa Total (g)", n["Grasa Total (g)"])}</td>
<td>${formatearValor("Grasa Saturada (g)", n["Grasa Saturada (g)"])}</td>
<td>${formatearValor("Grasa Monoinsaturada (g)", n["Grasa Monoinsaturada (g)"])}</td>
<td>${formatearValor("Grasa Poliinsaturada (g)", n["Grasa Poliinsaturada (g)"])}</td>
<td>${formatearValor("Colesterol (mg)", n["Colesterol (mg)"])}</td>
<td>${formatearValor("Carbohidratos (g)", n["Carbohidratos (g)"])}</td>
<td>${formatearValor("Fibra Dietética (g)", n["Fibra Dietética (g)"])}</td>
<td>${formatearValor("Calcio (mg)", n["Calcio (mg)"])}</td>
<td>${formatearValor("Fósforo (mg)", n["Fósforo (mg)"])}</td>
<td>${formatearValor("Hierro (mg)", n["Hierro (mg)"])}</td>
<td>${formatearValor("Zinc (mg)", n["Zinc (mg)"])}</td>
<td>${formatearValor("Vitamina A (ER)", n["Vitamina A (ER)"])}</td>
<td>${formatearValor("Vitamina B1 (mg)", n["Vitamina B1 (mg)"])}</td>
<td>${formatearValor("Vitamina B2 (mg)", n["Vitamina B2 (mg)"])}</td>
<td>${formatearValor("Vitamina B3 (mg)", n["Vitamina B3 (mg)"])}</td>
<td>${formatearValor("Vitamina B6 (mg)", n["Vitamina B6 (mg)"])}</td>
<td>${formatearValor("Ácido Fólico (ug)", n["Ácido Fólico (ug)"])}</td>
<td>${formatearValor("Vitamina B12 (ug)", n["Vitamina B12 (ug)"])}</td>
<td>${formatearValor("Vitamina C (mg)", n["Vitamina C (mg)"])}</td>

        `;
  
        cuerpo.appendChild(fila);
      });
    } catch (error) {
      console.error("Error al cargar alimentos:", error);
      alert("Error al obtener los datos.");
    }
  });
  

/* Inicio de la export a WORD */


document.getElementById("exportarWord").addEventListener("click", function () {
    const tabla = document.getElementById("tabla-alimentos");
    if (!tabla) {
      alert("Tabla no encontrada.");
      return;
    }
  
    const estilo = `
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 1in;
      }
      h1 {
        text-align: center;
        font-size: 22px;
        margin-bottom: 20px;
      }
      table {
        border-collapse: collapse;
        width: 100%;
        table-layout: auto; /* ← Habilita edición fluida */
      }
      th, td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 11px;
        text-align: center;
        vertical-align: middle;
        white-space: normal;
        word-break: break-word;
      }
      th {
        background-color: #e0e0e0;
      }
    </style>
  `;
  
  

  
    const contenido = `
      <html>
        <head>
          <meta charset="utf-8">
          ${estilo}
        </head>
        <body>
          <h1>Tabla Nutricional</h1>
          ${tabla.outerHTML}
        </body>
      </html>
    `;
  
    const blob = new Blob(["\ufeff", contenido], {
      type: "application/msword"
    });
  
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "tabla_nutricional.doc";
    a.click();
  });
  