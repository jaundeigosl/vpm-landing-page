<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

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

try {
    $mail = new PHPMailer(true);
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // O el host de tu proveedor
    $mail->SMTPAuth = true;
    $mail->Username = 'tu_correo@gmail.com'; // Tu dirección de correo
    $mail->Password = 'tu_contraseña_de_aplicación'; // Tu contraseña de aplicación (no la de tu cuenta)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usar SMTPS
    $mail->Port = 465; // O el puerto de tu proveedor

    // Destinatarios
    $mail->setFrom('no-reply@tudominio.com', 'Sistema de Aplicaciones');
    $mail->addAddress('destinatario@tudominio.com', 'Departamento de RH');
    $mail->addReplyTo($correo_electronico, $nombre_completo);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = "Nueva Aplicación para la vacante: " . $vacante_titulo;
    $mail->Body    = "
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

    // Adjuntar el archivo si se ha subido
    if (isset($_FILES['cv_adjunto']) && $_FILES['cv_adjunto']['error'] === UPLOAD_ERR_OK) {
        $file_info = $_FILES['cv_adjunto'];
        $nombre_cv = basename($file_info['name']);
        $file_tmp = $file_info['tmp_name'];
        $file_ext = strtolower(pathinfo($nombre_cv, PATHINFO_EXTENSION));

        if ($file_ext !== 'pdf') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El archivo adjunto debe ser un PDF.']);
            exit();
        }
        
        $mail->addAttachment($file_tmp, $nombre_cv);
    }

    $mail->send();
    echo json_encode(['success' => true, 'message' => '¡Tu aplicación ha sido enviada con éxito!']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Error al enviar la aplicación. Inténtalo más tarde. Mailer Error: {$mail->ErrorInfo}"]);
}

?>