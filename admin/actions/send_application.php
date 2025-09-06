<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

$nombre_completo = trim($_POST['nombre_completo'] ?? '');
$correo_electronico = trim($_POST['correo_electronico'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$vacante_titulo = trim($_POST['vacante_titulo'] ?? '');
$mensaje_adicional = trim($_POST['mensaje_adicional'] ?? '');

if (empty($nombre_completo) || empty($telefono) || empty($vacante_titulo) || empty($mensaje_adicional)) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos obligatorios.']);
    exit();
}

$cv_adjunto = null;
$nombre_cv = null;
if (isset($_FILES['cv_adjunto']) && $_FILES['cv_adjunto']['error'] === UPLOAD_ERR_OK) {
    $file_info = $_FILES['cv_adjunto'];
    $nombre_cv = $file_info['name'];
    $file_tmp = $file_info['tmp_name'];

    $file_ext = strtolower(pathinfo($nombre_cv, PATHINFO_EXTENSION));
    if ($file_ext !== 'pdf') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El archivo adjunto debe ser un PDF.']);
        exit();
    }

    $cv_adjunto = file_get_contents($file_tmp);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El CV es un campo obligatorio.']);
    exit();
}

$to = 'tu_correo_de_destino@example.com'; // <-- Reemplaza esto con tu correo
$subject = 'Nueva Aplicación para Vacante: ' . $vacante_titulo;
$from = 'no-responder@tudominio.com'; // <-- Reemplaza esto con un correo de tu dominio

$mime_boundary = '----=_Part_' . md5(time()) . rand(1000, 9999);
$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: " . ($correo_electronico ? $correo_electronico : $from) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"\r\n";

$message_body = "
--{$mime_boundary}
Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: 7bit

<p>Se ha recibido una nueva aplicación para la vacante: <strong>{$vacante_titulo}</strong></p>
<hr>
<ul>
    <li><strong>Nombre Completo:</strong> {$nombre_completo}</li>
    <li><strong>Correo Electrónico:</strong> " . ($correo_electronico ?: 'No proporcionado') . "</li>
    <li><strong>Teléfono:</strong> {$telefono}</li>
</ul>
<p><strong>Mensaje Adicional:</strong></p>
<p>{$mensaje_adicional}</p>
<p>Se adjunta el CV del aplicante.</p>

";

$attachment_body = "--{$mime_boundary}\r\n";
$attachment_body .= "Content-Type: application/pdf; name=\"{$nombre_cv}\"\r\n";
$attachment_body .= "Content-Disposition: attachment; filename=\"{$nombre_cv}\"\r\n";
$attachment_body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$attachment_body .= chunk_split(base64_encode($cv_adjunto));
$attachment_body .= "\r\n--{$mime_boundary}--\r\n";

if (mail($to, $subject, $message_body . $attachment_body, $headers)) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => '¡Tu aplicación ha sido enviada exitosamente!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al enviar la aplicación. Por favor, inténtalo más tarde.']);
}

?>