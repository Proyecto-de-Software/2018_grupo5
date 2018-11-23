-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 23-11-2018 a las 12:31:49
-- Versión del servidor: 10.1.34-MariaDB-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.7-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `grupo5`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acompanamiento`
--

CREATE TABLE `acompanamiento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `acompanamiento`
--

INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES
(1, 'Familiar Cercano'),
(2, 'Hermanos e hijos'),
(3, 'Pareja'),
(4, 'Referentes vinculares'),
(5, 'Policía'),
(6, 'SAME'),
(7, 'Por sus propios medios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `variable` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `variable`, `valor`) VALUES
(1, 'titulo', 'Hospital Dr. Alejandro Korn'),
(2, 'sitio_activo', 'true'),
(3, 'descripcion', ''),
(4, 'email_de_contacto', ''),
(5, 'paginacion', '5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

CREATE TABLE `consulta` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo_id` int(11) NOT NULL,
  `derivacion_id` int(11) DEFAULT NULL,
  `articulacion_con_instituciones` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internacion` tinyint(1) NOT NULL DEFAULT '0',
  `diagnostico` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tratamiento_farmacologico_id` int(11) DEFAULT NULL,
  `acompanamiento_id` int(11) DEFAULT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `nombre`) VALUES
(1, 'Masculino'),
(2, 'Femenino'),
(3, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coordenadas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_sanitaria_id` int(11) NOT NULL,
  `tipo_institucion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `institucion`
--

INSERT INTO `institucion` (`id`, `nombre`, `director`, `direccion`, `coordenadas`, `telefono`, `region_sanitaria_id`, `tipo_institucion_id`) VALUES
(1, 'Institucion Bahia Blanca', 'Director B.B.', 'Calle 1 1000', '-38.72,-62.39', '2995456589', 1, 2),
(2, 'Institucion Pehuajo', 'Director Pehuajo.', 'Calle 2 2000', '-35.82,-61.92', '353965456', 2, 2),
(3, 'Institucion Junin', 'Director Junin.', 'Calle 3 3000', '-34.59,-60.99', '457865895', 3, 1),
(4, 'Institucion Pergamino', 'Director Pergamino.', 'Calle 4 4000', '-33.89,-60.60', '2356456545', 4, 1),
(5, 'Institucion Gral. S. Martin', 'Director Gral. S. Martin', 'Calle 5 5000', '-33.23,-60.32', '2356456545', 5, 2),
(6, 'Institucion Lomas', 'Director Lomas', 'Calle 6 6000', '-34.76,-58.45', '12356598', 6, 1),
(7, 'Institucion Gral. Rodriguez', 'Director Gral. Rodriguez', 'Calle 7 7000', '-34.83,-58.45', '32654565', 7, 2),
(8, 'Institucion Mardel', 'Director Mardel', 'Calle 8 8000', '-38.01,-57.74', '453265654', 8, 1),
(13, 'Institucion Azul', 'Director Azul', 'Calle 9 9000', '-36.77,-59.88', '654545154', 9, 2),
(14, 'Institucion Chivilcoy', 'Director Chivilcoy', 'Calle 10 1000', '-34.89,-60.06', '12554546', 10, 1),
(15, 'Institucion Ensenada', 'Director Ensenada', 'Calle 11 2000', '-34.84,-58.05', '2216555426', 11, 2),
(16, 'Institucion La Matanza', 'Director La Matanza', 'Calle 12 3000', '-34.77,-58.75', '11655511426', 12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE `localidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `localidad`
--

INSERT INTO `localidad` (`id`, `nombre`, `partido_id`) VALUES
(1, 'BahÃ­a Blanca', 1),
(2, 'Nueve de Julio', 2),
(3, 'Junin', 3),
(4, 'Pergamino', 4),
(5, 'General San MartÃ­n', 5),
(6, 'Lomas de Zamora', 6),
(7, 'General RodrÃ­guez', 7),
(8, 'Mar del Plata', 8),
(9, 'Azul', 9),
(10, 'Lobos', 10),
(11, 'Ensenada', 11),
(12, 'La Matanza', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo_consulta`
--

CREATE TABLE `motivo_consulta` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `motivo_consulta`
--

INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES
(1, 'Receta Médica'),
(2, 'Control por Guardia'),
(3, 'Consulta'),
(4, 'Intento de Suicidio'),
(5, 'Interconsulta'),
(6, 'Otras');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra_social`
--

CREATE TABLE `obra_social` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `obra_social`
--

INSERT INTO `obra_social` (`id`, `nombre`) VALUES
(9, 'AcaSalud'),
(6, 'Accord Salud'),
(10, 'Bristol Medicine'),
(5, 'Galeno'),
(17, 'IOMA'),
(15, 'Luis Pasteur'),
(3, 'Medicus'),
(4, 'MedifÃ©'),
(7, 'OMINT'),
(1, 'OSDE'),
(14, 'OSDEPYM'),
(11, 'OSECAC'),
(16, 'OSMEDICA'),
(13, 'OSPACP'),
(21, 'OSPE'),
(20, 'OSPEPBA'),
(18, 'OSPJN'),
(19, 'OSSSB'),
(2, 'Sancor Salud'),
(8, 'Swiss Medical'),
(12, 'UniÃ³n Personal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id` int(11) NOT NULL,
  `apellido` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `lugar_nac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localidad_id` int(11) DEFAULT NULL,
  `domicilio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `genero_id` int(11) DEFAULT NULL,
  `tiene_documento` tinyint(1) DEFAULT '0',
  `tipo_doc_id` int(11) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nro_historia_clinica` int(11) DEFAULT NULL,
  `nro_carpeta` int(11) DEFAULT NULL,
  `obra_social_id` int(11) DEFAULT NULL,
  `eliminado` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id`, `apellido`, `nombre`, `fecha_nac`, `lugar_nac`, `localidad_id`, `domicilio`, `genero_id`, `tiene_documento`, `tipo_doc_id`, `numero`, `tel`, `nro_historia_clinica`, `nro_carpeta`, `obra_social_id`, `eliminado`) VALUES
(28, 'Gomez', 'Marcos', '1978-08-17', 'La Pampa', 8, '12 655', 1, 1, 1, 23547875, '+542216599988', 154577, 12154, NULL, 0),
(30, 'NN', 'NN', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 357542, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_sanitaria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `nombre`, `region_sanitaria_id`) VALUES
(1, 'BahÃ­a Blanca', 1),
(2, 'PehuajÃ³', 2),
(3, 'JunÃ­n', 3),
(4, 'Pergamino', 4),
(5, 'General San MartÃ­n', 5),
(6, 'Lomas de Zamora', 6),
(7, 'General RodrÃ­guez', 7),
(8, 'Mar de Plata', 8),
(9, 'Azul', 9),
(10, 'Chivilcoy', 10),
(11, 'Ensenada', 11),
(12, 'La Matanza', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `nombre`) VALUES
(75, 'authentication_login'),
(76, 'authentication_logout'),
(77, 'configuracion_index_view'),
(79, 'configuracion_set_mantenimiento'),
(78, 'configuracion_update'),
(158, 'consulta_create'),
(157, 'consulta_create_view'),
(160, 'consulta_destroy'),
(161, 'consulta_get_json_for_map'),
(154, 'consulta_index'),
(159, 'consulta_update'),
(156, 'consulta_update_view'),
(155, 'consulta_view'),
(120, 'index_contacto'),
(80, 'index_index'),
(165, 'institucion_get_institucion_as_j_s_o_n'),
(164, 'institucion_get_instituciones_as_j_s_o_n'),
(166, 'institucion_get_instituciones_by_region_as_j_s_o_n'),
(81, 'localidad_obtener_por_partido'),
(82, 'login_render'),
(89, 'paciente_create'),
(90, 'paciente_create_n_n'),
(93, 'paciente_delete'),
(83, 'paciente_index'),
(88, 'paciente_new_n_n_view'),
(87, 'paciente_new_view'),
(169, 'paciente_pacientes_j_s_o_n'),
(84, 'paciente_read_view'),
(86, 'paciente_search'),
(85, 'paciente_search_view'),
(92, 'paciente_update'),
(91, 'paciente_update_view'),
(127, 'paciente_validar_fecha'),
(181, 'partido_ver_todos_los_partidos'),
(94, 'permiso_index_view'),
(95, 'region_sanitaria_obtener_por_partido'),
(186, 'reportes_get_json_by_gender'),
(187, 'reportes_get_json_by_location'),
(185, 'reportes_get_json_by_reason'),
(184, 'reportes_index'),
(140, 'rol_get_permissions_for_role'),
(96, 'rol_index_view'),
(97, 'rol_show'),
(98, 'rol_update'),
(101, 'setup_db_data_create_default_configs'),
(100, 'setup_db_data_generate_permission_data'),
(99, 'setup_db_data_load_data'),
(102, 'setup_db_data_show_warnings'),
(112, 'usuario_change_own_password'),
(113, 'usuario_change_password'),
(111, 'usuario_configuracion_view'),
(105, 'usuario_create'),
(104, 'usuario_create_view'),
(106, 'usuario_delete'),
(148, 'usuario_destroy'),
(103, 'usuario_index'),
(110, 'usuario_update'),
(108, 'usuario_update_permisos'),
(109, 'usuario_update_roles'),
(107, 'usuario_update_view');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `region_sanitaria`
--

CREATE TABLE `region_sanitaria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `region_sanitaria`
--

INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES
(1, 'RegiÃ³n I'),
(2, 'RegiÃ³n II'),
(3, 'RegiÃ³n III'),
(4, 'RegiÃ³n IV'),
(5, 'RegiÃ³n V'),
(6, 'RegiÃ³n VI'),
(7, 'RegiÃ³n VII'),
(8, 'RegiÃ³n VIII'),
(9, 'RegiÃ³n IX'),
(10, 'RegiÃ³n X'),
(11, 'RegiÃ³n XI'),
(12, 'RegiÃ³n XII');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`) VALUES
(4, 'administrador'),
(3, 'equipo de guardia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_tiene_permiso`
--

CREATE TABLE `rol_tiene_permiso` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rol_tiene_permiso`
--

INSERT INTO `rol_tiene_permiso` (`rol_id`, `permiso_id`) VALUES
(3, 81),
(3, 83),
(3, 84),
(3, 85),
(3, 86),
(3, 87),
(3, 88),
(3, 89),
(3, 90),
(3, 91),
(3, 92),
(3, 95),
(3, 127),
(3, 154),
(3, 155),
(3, 156),
(3, 157),
(3, 158),
(3, 159),
(3, 161),
(4, 75),
(4, 76),
(4, 77),
(4, 78),
(4, 79),
(4, 80),
(4, 81),
(4, 82),
(4, 83),
(4, 84),
(4, 85),
(4, 86),
(4, 93),
(4, 94),
(4, 95),
(4, 96),
(4, 97),
(4, 98),
(4, 99),
(4, 100),
(4, 101),
(4, 102),
(4, 103),
(4, 104),
(4, 105),
(4, 106),
(4, 107),
(4, 108),
(4, 109),
(4, 110),
(4, 111),
(4, 112),
(4, 113),
(4, 120),
(4, 140),
(4, 148),
(4, 154),
(4, 160);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES
(3, 'CI'),
(1, 'DNI'),
(2, 'LC'),
(4, 'LE'),
(5, 'Pasaporte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_institucion`
--

CREATE TABLE `tipo_institucion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_institucion`
--

INSERT INTO `tipo_institucion` (`id`, `nombre`) VALUES
(2, 'Comisaría'),
(1, 'Hospital');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamiento_farmacologico`
--

CREATE TABLE `tratamiento_farmacologico` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tratamiento_farmacologico`
--

INSERT INTO `tratamiento_farmacologico` (`id`, `nombre`) VALUES
(1, 'Mañana'),
(2, 'Tarde'),
(3, 'Noche');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_superuser` tinyint(1) NOT NULL DEFAULT '0',
  `eliminado` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `username`, `password`, `activo`, `updated_at`, `created_at`, `first_name`, `last_name`, `is_superuser`, `eliminado`) VALUES
(1, 'admin@admin.com', 'admin', '123456*', 1, '2018-10-25 18:58:39', NULL, 'admin', 'admin', 0, 0),
(7, 'usera@gmail.com', 'usera', '654321', 1, '2018-10-08 21:11:01', '2018-10-08 21:11:01', 'User', 'A', 0, 0),
(8, 'userb@hotmail.com', 'userb', '456789', 1, '2018-10-08 21:11:31', '2018-10-08 21:11:31', 'User', 'B', 0, 0),
(9, 'userc@yahoo.com', 'userc', '01234567', 1, '2018-10-08 21:24:29', '2018-10-08 21:12:07', 'User', 'C', 0, 0),
(10, 'root@root.com', 'root', 'root*', 1, '2018-10-25 18:56:21', '2018-10-25 18:56:21', 'root', 'root', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permisos`
--

CREATE TABLE `usuario_permisos` (
  `usuario_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario_permisos`
--

INSERT INTO `usuario_permisos` (`usuario_id`, `permiso_id`) VALUES
(9, 83),
(9, 84),
(9, 85),
(9, 86);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_tiene_rol`
--

CREATE TABLE `usuario_tiene_rol` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_tiene_rol`
--

INSERT INTO `usuario_tiene_rol` (`usuario_id`, `rol_id`) VALUES
(1, 4),
(7, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acompanamiento`
--
ALTER TABLE `acompanamiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_paciente_id` (`paciente_id`),
  ADD KEY `FK_motivo_id` (`motivo_id`),
  ADD KEY `FK_derivacion_id` (`derivacion_id`),
  ADD KEY `FK_tratamiento_farmacologico_id` (`tratamiento_farmacologico_id`),
  ADD KEY `FK_acompanamiento_id` (`acompanamiento_id`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_institucion_region_sanitaria_id` (`region_sanitaria_id`),
  ADD KEY `FK_tipo_institucion_id` (`tipo_institucion_id`);

--
-- Indices de la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_partido_id` (`partido_id`);

--
-- Indices de la tabla `motivo_consulta`
--
ALTER TABLE `motivo_consulta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `obra_social`
--
ALTER TABLE `obra_social`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `obra_social_nombre_uindex` (`nombre`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_obra_social_id` (`obra_social_id`),
  ADD KEY `FK_tipo_doc_id` (`tipo_doc_id`),
  ADD KEY `FK_localidad_id` (`localidad_id`),
  ADD KEY `FK_genero_id` (`genero_id`);

--
-- Indices de la tabla `partido`
--
ALTER TABLE `partido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_partido_region_sanitaria_id` (`region_sanitaria_id`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permiso_nombre_uindex` (`nombre`);

--
-- Indices de la tabla `region_sanitaria`
--
ALTER TABLE `region_sanitaria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rol_nombre_uindex` (`nombre`);

--
-- Indices de la tabla `rol_tiene_permiso`
--
ALTER TABLE `rol_tiene_permiso`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `FK_permiso_id` (`permiso_id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_documento_nombre_uindex` (`nombre`);

--
-- Indices de la tabla `tipo_institucion`
--
ALTER TABLE `tipo_institucion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_institucion_nombre_uindex` (`nombre`);

--
-- Indices de la tabla `tratamiento_farmacologico`
--
ALTER TABLE `tratamiento_farmacologico`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_username_uindex` (`username`),
  ADD UNIQUE KEY `usuario_email_uindex` (`email`);

--
-- Indices de la tabla `usuario_permisos`
--
ALTER TABLE `usuario_permisos`
  ADD PRIMARY KEY (`usuario_id`,`permiso_id`),
  ADD KEY `usuario_permisos_usuario_id_permiso_id_index` (`usuario_id`,`permiso_id`),
  ADD KEY `usuario_permisos_permiso_id_fk` (`permiso_id`);

--
-- Indices de la tabla `usuario_tiene_rol`
--
ALTER TABLE `usuario_tiene_rol`
  ADD PRIMARY KEY (`usuario_id`,`rol_id`),
  ADD KEY `FK_rol_utp_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acompanamiento`
--
ALTER TABLE `acompanamiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `institucion`
--
ALTER TABLE `institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `localidad`
--
ALTER TABLE `localidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `motivo_consulta`
--
ALTER TABLE `motivo_consulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `obra_social`
--
ALTER TABLE `obra_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;
--
-- AUTO_INCREMENT de la tabla `region_sanitaria`
--
ALTER TABLE `region_sanitaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipo_institucion`
--
ALTER TABLE `tipo_institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tratamiento_farmacologico`
--
ALTER TABLE `tratamiento_farmacologico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `FK_acompanamiento_id` FOREIGN KEY (`acompanamiento_id`) REFERENCES `acompanamiento` (`id`),
  ADD CONSTRAINT `FK_derivacion_id` FOREIGN KEY (`derivacion_id`) REFERENCES `institucion` (`id`),
  ADD CONSTRAINT `FK_motivo_id` FOREIGN KEY (`motivo_id`) REFERENCES `motivo_consulta` (`id`),
  ADD CONSTRAINT `FK_paciente_id` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`),
  ADD CONSTRAINT `FK_tratamiento_farmacologico_id` FOREIGN KEY (`tratamiento_farmacologico_id`) REFERENCES `tratamiento_farmacologico` (`id`);

--
-- Filtros para la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD CONSTRAINT `FK_institucion_region_sanitaria_id` FOREIGN KEY (`region_sanitaria_id`) REFERENCES `region_sanitaria` (`id`),
  ADD CONSTRAINT `FK_tipo_institucion_id` FOREIGN KEY (`tipo_institucion_id`) REFERENCES `tipo_institucion` (`id`);

--
-- Filtros para la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD CONSTRAINT `FK_partido_id` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`id`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `FK_genero_id` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`),
  ADD CONSTRAINT `FK_localidad_id` FOREIGN KEY (`localidad_id`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `FK_obra_social_id` FOREIGN KEY (`obra_social_id`) REFERENCES `obra_social` (`id`),
  ADD CONSTRAINT `FK_tipo_doc_id` FOREIGN KEY (`tipo_doc_id`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `FK_partido_region_sanitaria_id` FOREIGN KEY (`region_sanitaria_id`) REFERENCES `region_sanitaria` (`id`);

--
-- Filtros para la tabla `rol_tiene_permiso`
--
ALTER TABLE `rol_tiene_permiso`
  ADD CONSTRAINT `FK_permiso_id` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`),
  ADD CONSTRAINT `FK_rol_id` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `usuario_permisos`
--
ALTER TABLE `usuario_permisos`
  ADD CONSTRAINT `usuario_permisos_permiso_id_fk` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`),
  ADD CONSTRAINT `usuario_permisos_usuario_id_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
