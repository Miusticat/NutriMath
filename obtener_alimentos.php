<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
  http_response_code(401);
  echo "No autorizado";
  exit;
}

require_once "conexion.php";
$conn = conectarBD(); 

$id_usuario = $_SESSION['id_usuario'];

$query = "SELECT a.id_alimento, a.nombre_alimento, a.peso_base, a.peso_analizado,
                 n.nombre_nutriente, n.valor_calculado
          FROM alimentos a
          LEFT JOIN nutrientes n ON a.id_alimento = n.id_alimento
          WHERE a.id_usuario = ?
          ORDER BY a.fecha_registro DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$alimentos = [];

while ($fila = $resultado->fetch_assoc()) {
  $id = $fila['id_alimento'];
  if (!isset($alimentos[$id])) {
    $alimentos[$id] = [
      'nombre' => $fila['nombre_alimento'],
      'peso_base' => $fila['peso_base'],
      'peso_analizado' => $fila['peso_analizado'],
      'nutrientes' => []
    ];
  }
  if ($fila['nombre_nutriente']) {
    $alimentos[$id]['nutrientes'][$fila['nombre_nutriente']] = $fila['valor_calculado'];
  }
}

echo json_encode(array_values($alimentos));
$stmt->close();
$conn->close();
?>
