CREATE DATABASE IF NOT EXISTS `vpm`;
USE `vpm`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- La contraseña 'password123'
-- Este usuario de ejemplo tiene el rol 'admin'.

INSERT INTO `usuarios` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$m6AYxXarknOUlMA8RzBibOfcoK7KcmHQQLfWllV1Oa6v7NYtp6y2S', 'admin');

CREATE TABLE IF NOT EXISTS `vacantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_puesto` varchar(255) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `resumen` text NOT NULL,
  `requisitos` text NOT NULL,
  `edad` varchar(50) NOT NULL,
  `sexo` varchar(50) NOT NULL,
  `escolaridad` text NOT NULL,
  `conocimientos` text NOT NULL,
  `funciones` text NOT NULL,
  `beneficios` text NOT NULL,
  `sueldo` decimal(10,2) NOT NULL,
  `prestaciones` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `vacantes` (
    `nombre_puesto`, 
    `ubicacion`, 
    `resumen`, 
    `requisitos`, 
    `edad`, 
    `sexo`, 
    `escolaridad`, 
    `conocimientos`, 
    `funciones`, 
    `beneficios`, 
    `sueldo`, 
    `prestaciones`
) VALUES (
    'Desarrollador Web Full Stack',
    'Ciudad de México',
    'Buscamos desarrollador web con experiencia en PHP, JavaScript y bases de datos para unirse a nuestro equipo dinámico.',
    '• 2+ años de experiencia en desarrollo web\n• Conocimiento sólido de PHP y MySQL\n• Experiencia con JavaScript, HTML5, CSS3\n• Conocimiento de frameworks como Laravel o Symfony\n• Capacidad para trabajar en equipo',
    '25-35',
    'I',
    'Licenciatura en Informática, Sistemas o carrera afín',
    '• PHP 7+\n• MySQL/MariaDB\n• JavaScript (ES6+)\n• HTML5/CSS3\n• Git control de versiones\n• APIs REST',
    '• Desarrollo y mantenimiento de aplicaciones web\n• Colaborar con el equipo de diseño para implementar interfaces\n• Optimizar aplicaciones para máxima velocidad y escalabilidad\n• Resolver problemas técnicos y bugs',
    '• Seguro de gastos médicos mayores\n• Prestaciones superiores a ley\n• Horario flexible\n• Home office 2 días por semana\n• Capacitaciones constantes',
    25000.00,
    '• Aguinaldo 30 días\n• Vacaciones superiores a ley\n• Prima vacacional 25%\n• Fondo de ahorro\n• Vale de despensa'
);


/*CREATE TABLE IF NOT EXISTS `sesiones` (
  `idusuario_id` int(11) NOT NULL,
  `token_sesion` varchar(255) NOT NULL,
  `fecha_inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`token_sesion`),
  FOREIGN KEY (`idusuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/