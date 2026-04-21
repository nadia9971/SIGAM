-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-04-2026 a las 19:28:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_citas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `Nombres` varchar(45) NOT NULL,
  `Apellidos` varchar(45) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','medico','recepcion') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `Nombres`, `Apellidos`, `usuario`, `password`, `rol`) VALUES
(19, 'Macarena', 'Arteaga', 'Art@gmail.com', '$2y$10$QcRuUygjUwyYzqH1cjlBHeHqX2Ebrnu937rMdPs73kL86lRf4iGEy', 'medico'),
(20, 'Josue', 'Cabrera', 'Josue@gmail.com', '$2y$10$1UMPX78D0MDDwMo5DkSO1.MuDFDXQw1QJvwMflr0hU///kL0b03dq', 'recepcion'),
(21, 'Ivan', 'Solano', 'ivan@gmail.com', '$2y$10$x4PvVYGLepC.5l0o5WQhhOPDKbZOOQKTGGOXVpgqFw7AEUJ2r7F.a', 'admin'),
(22, 'nadia', 'bailon', 'nadia@gmail.com', '$2y$10$o1cXY.50gsvQs9oPrCbBTOjd/0tKEW26q.H85yL3JSbQwWQjP2AnC', 'medico'),
(26, 'sandra', 'garcia', 'sandra@gmail.com', '$2y$10$aqLYi0OItJtsZozELM8M.ubBheCunn7tNnncz8LV.xrrDW3G5wVC6', 'admin'),
(45, 'Molly', 'Barrera', 'Molly@gmail.com', '$2y$10$G05NcSLDgWFXBzm.uUHX6.2/gQxh7monmzkdcno0KLAZWE4S51/oK', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
