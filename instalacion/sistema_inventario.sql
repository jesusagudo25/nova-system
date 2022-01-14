-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 13-01-2022 a las 20:47:30
-- Versión del servidor: 8.0.27
-- Versión de PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int UNSIGNED NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `estado`) VALUES
(1, 'Audifonos', 1),
(2, 'Disco Duros', 1),
(3, 'Fuentes de poder', 1),
(4, 'Memorias RAM', 1),
(5, 'Procesadores', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `telefono`, `email`, `provincia`, `distrito`, `estado`) VALUES
(1, 'Jesús Agudo', '507-6477-8052', 'jagudo2514@gmail.com', 'Veraguas', 'Santiago', 1),
(2, 'Eufemia Murillo', '507-6122-4222', 'emurillo.d.leon@gmail.com', 'Panamá', 'Ciudad de Panamá', 1),
(3, 'Carlos Andres', '507-6891-9911', 'andres@gmail.com', 'Colón', 'Portobelo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `num_detalle` int UNSIGNED NOT NULL,
  `id_factura` int UNSIGNED NOT NULL,
  `id_producto` int UNSIGNED NOT NULL,
  `cantidad` int UNSIGNED NOT NULL,
  `precio` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`num_detalle`, `id_factura`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 1, 1, 1, '9.90'),
(1, 2, 1, 1, '9.90'),
(1, 3, 6, 1, '118.90'),
(1, 4, 6, 2, '118.90'),
(2, 2, 9, 1, '265.90');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int UNSIGNED NOT NULL,
  `id_cliente` int UNSIGNED NOT NULL,
  `id_usuario` int UNSIGNED NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `id_cliente`, `id_usuario`, `fecha`) VALUES
(1, 1, 1, '2021-11-05 16:21:22'),
(2, 1, 1, '2021-11-05 16:22:20'),
(3, 1, 1, '2021-11-12 17:37:44'),
(4, 1, 1, '2021-11-12 17:49:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int UNSIGNED NOT NULL,
  `id_categoria` int UNSIGNED NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(5,2) NOT NULL,
  `stock` int UNSIGNED NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `id_categoria`, `codigo`, `descripcion`, `precio`, `stock`, `estado`) VALUES
(1, 1, 'AYM-GER-040', 'Mediacom M-HS40908 - Audífonos, Compatible con PC, Omnidireccional, Conector 3.5mm, Azul', '9.90', 23, 1),
(2, 1, 'AYM-MSI-005', 'MSI DS501 Gaming Headset - Audífono con Micrófono para Gamer, Controles en Línea, Reducción de ruido, Omnidireccional', '21.90', 2, 1),
(3, 2, 'SSD-CRU-025', 'Crucial MX500 500GB - 2.5 Unidad de estado sólido (SSD), 560MB/s de lectura, 510MB/s de escritura, SATA 6Gb/s', '70.90', 100, 1),
(4, 2, 'SSD-KIN-024', 'Kingston SSDNow A400 240GB - 2.5 Unidad de estado sólido (SSD), 500MB/s de lectura, 350MB/s de escritura, SATA3 Rev.3 6Gbit/s, 7mm', '45.90', 200, 1),
(5, 3, 'FUP-COR-048', 'Corsair CV Series CV450 - 450W ATX, Abanico 120mm, Conexiones 2 x PCI-Express 6+2Pin & 7x SATA, Certificado 80 PLUS Bronce', '50.90', 50, 1),
(6, 3, 'FUP-EVG-042', 'EVGA 850 BQ - 850W, 24 Pines ATX, Conectores 2x 4+4 pin, 3x 6+2 pin PCI-Express, 10x SATA, 80 Plus Bronce', '118.90', 2, 1),
(7, 4, 'D40-HEW-006', 'HP V2 Series - 16GB, DDR4, PC4-21300 (2666MHz), U-DIMM, 288 Pin, CL-19, 1.35V', '85.90', 4, 1),
(8, 4, 'D40-KVR-096', 'Kingston HyperX Fury Black - 16GB DDR4 DIMM, PC4-21300 (2666MHz), RGB, No ECC, CL16, 1.2V, 288 Pines', '93.90', 1, 1),
(9, 5, 'AA4-AMD-006', 'AMD Ryzen 5 3600 a 3.6GHz - Seis Núcleos, Socket AM4, 7nm, 65W, 3MB L2 Coché, 4.2GHz Turbo, Soporta DDR4', '265.90', 5, 1),
(10, 5, 'AA4-AMD-021', 'AMD Ryzen 5 3400G - Procesador de Cuatro Núcleos a 3.7GHz, Socket AM4, 12nm FinFET, Gráfico Radeon RX Vega 11, 4MB Caché, 4.2GHz Turbo', '195.90', 8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int UNSIGNED NOT NULL,
  `id_rol` int UNSIGNED NOT NULL DEFAULT '1',
  `nombre` varchar(35) NOT NULL,
  `apellido_paterno` varchar(35) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(85) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(70) NOT NULL DEFAULT 'No definido',
  `fecha_token` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_rol`, `nombre`, `apellido_paterno`, `email`, `password`, `estado`, `token`, `fecha_token`) VALUES
(1, 2, 'super', 'admin', 'admin@novasystems.com', '$2y$10$pL7eKtEJWkaQAd9nn.ribOaX3QPsR7AJR6dMekng88iBadi6F8sf6', 1, 'No definido', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_rol` int UNSIGNED NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id_rol`, `nombre`) VALUES
(1, 'User'),
(2, 'Admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `telefono` (`telefono`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`num_detalle`,`id_factura`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id_rol` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD CONSTRAINT `detalle_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `usuario_rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
