-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-10-2018 a las 23:52:55
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bdpymeasy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_abonos`
--

CREATE TABLE IF NOT EXISTS `tbl_abonos` (
  `idabono` int(11) NOT NULL AUTO_INCREMENT,
  `fkventa` int(11) NOT NULL,
  `monto` double(11,2) NOT NULL,
  `modalidad_pago` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  `nro_referencia` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_abono` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idabono`),
  KEY `fkventa` (`fkventa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `tbl_abonos`
--

INSERT INTO `tbl_abonos` (`idabono`, `fkventa`, `monto`, `modalidad_pago`, `fecha`, `nro_referencia`, `estatus_abono`) VALUES
(5, 19, 121200.00, 'CHEQUE', '2018-10-21', '565656565', 'PAGADO');

--
-- (Evento) desencadenante `tbl_abonos`
--
DROP TRIGGER IF EXISTS `trigger_upd_cuenta_cobrar`;
DELIMITER //
CREATE TRIGGER `trigger_upd_cuenta_cobrar` AFTER INSERT ON `tbl_abonos`
 FOR EACH ROW BEGIN

update tbl_cuentas_cobrar set monto_haber=monto_haber+new.monto, monto_debe=monto_debe-new.monto where fkventa=new.fkventa;

update tbl_cuentas_cobrar set estatus='PAGADO' where monto_haber>=monto_total and fkventa=new.fkventa;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_auditorias`
--

CREATE TABLE IF NOT EXISTS `tbl_auditorias` (
  `idauditoria` int(11) NOT NULL AUTO_INCREMENT,
  `actividad` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `login` varchar(6) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`idauditoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=212 ;

--
-- Volcar la base de datos para la tabla `tbl_auditorias`
--

INSERT INTO `tbl_auditorias` (`idauditoria`, `actividad`, `login`, `fecha`) VALUES
(197, 'Nuevo inicio de sesion', 'rlunar', '2018-10-20 19:16:23'),
(198, 'Registro de producto: ARROZ BLANCO', 'rlunar', '2018-10-20 19:34:47'),
(199, 'Registro de producto: PASTA LARGA', 'rlunar', '2018-10-20 19:35:15'),
(200, 'Registro de Nuevo Proveedor: HERMANOS MACANA', 'rlunar', '2018-10-20 19:36:01'),
(201, 'Registro de Pedido: 16', 'rlunar', '2018-10-20 19:37:35'),
(202, 'Actualizacion de Pedido: 16', 'rlunar', '2018-10-20 19:38:49'),
(203, 'Pago de Pedido: 16', 'rlunar', '2018-10-20 19:58:52'),
(204, 'Registro de Nuevo Vendedor: ROBERTO', 'rlunar', '2018-10-20 20:01:33'),
(205, 'Registro de Precio Producto ID: 6.- Bs.3000', 'rlunar', '2018-10-20 20:03:47'),
(206, 'Registro de Precio Producto ID: 6.- Bs.2600', 'rlunar', '2018-10-20 20:03:47'),
(207, 'Registro de Precio Producto ID: 7.- Bs.6000', 'rlunar', '2018-10-20 20:04:37'),
(208, 'Registro de Precio Producto ID: 7.- Bs.3900', 'rlunar', '2018-10-20 20:04:37'),
(209, 'Registro de Venta: 18', 'rlunar', '2018-10-20 20:06:27'),
(210, 'Registro de Venta: 19', 'rlunar', '2018-10-20 20:12:21'),
(211, 'Nuevo inicio de sesion', 'rlunar', '2018-10-21 16:07:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cierres_diarios_inv`
--

CREATE TABLE IF NOT EXISTS `tbl_cierres_diarios_inv` (
  `idcierre` int(11) NOT NULL AUTO_INCREMENT,
  `fkproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `cantidad_unidades` int(11) NOT NULL,
  `cantidad_blt` int(11) NOT NULL,
  `cant_unidades_en_blt` int(11) NOT NULL,
  `observacion` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `responsable` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `estatus_cierre` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idcierre`),
  KEY `fkproducto` (`fkproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `tbl_cierres_diarios_inv`
--


--
-- (Evento) desencadenante `tbl_cierres_diarios_inv`
--
DROP TRIGGER IF EXISTS `trigger_actualizar_producto`;
DELIMITER //
CREATE TRIGGER `trigger_actualizar_producto` AFTER INSERT ON `tbl_cierres_diarios_inv`
 FOR EACH ROW BEGIN 

update tbl_productos set 
       cantidad_blt = NEW.cantidad_blt,
       cantidad_unitaria =  NEW.cantidad_unidades,
       cant_unidades_en_blt = NEW.cant_unidades_en_blt
where idproducto = NEW.fkproducto;

update tbl_productos set estatus_prod='ACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria >0;

update tbl_productos set estatus_prod='INACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria <=0;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_clientes`
--

CREATE TABLE IF NOT EXISTS `tbl_clientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `razon_social` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `representante_legal` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_cliente` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idcliente`),
  UNIQUE KEY `rif` (`rif`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=54 ;

--
-- Volcar la base de datos para la tabla `tbl_clientes`
--

INSERT INTO `tbl_clientes` (`idcliente`, `rif`, `razon_social`, `representante_legal`, `direccion`, `fono`, `email`, `estatus_cliente`) VALUES
(2, 'V12003177', 'VICTOR SANVICENTE', 'VICTOR SANVICENTE', '', '', '', 'ACTIVO'),
(3, 'COMERCIALPER', 'COMERCIAL PEREZ', 'COMERCIAL PEREZ', '', '', '', 'ACTIVO'),
(4, 'J306364234', 'COMERCIAL EL ENCANTO', 'COMERCIAL EL ENCANTO', '', '', '', 'ACTIVO'),
(5, 'J410151927', 'INVERSIONES AMISHA EXPRESS', 'INVERSIONES AMISHA EXPRESS', '', '', '', 'ACTIVO'),
(6, 'J41108384-4', 'INVERSIONES J Y Y RONDON ', 'INVERSIONES J Y Y RONDON ', '', '', '', 'ACTIVO'),
(7, 'J40566966', 'INVERSIONES VAIVEN', 'INVERSIONES VAIVEN', '', '', '', 'ACTIVO'),
(8, 'J296684073', 'INVERSIONES PALMA REAL', 'INVERSIONES PALMA REAL', '', '', '', 'ACTIVO'),
(9, 'J402474067', 'CARNICERIA EL TORO DE ORO', 'CARNICERIA EL TORO DE ORO', NULL, NULL, NULL, 'ACTIVO'),
(10, 'J307470615', 'EXQUISITESES PANADERIA SALTO ANGEL', 'EXQUISITESES PANADERIA SALTO ANGEL', NULL, NULL, NULL, 'ACTIVO'),
(11, 'J407844016', 'INVERSIONES ALLOPY', 'INVERSIONES ALLOPY', NULL, NULL, NULL, 'ACTIVO'),
(12, 'J407530763', 'DALIDY FESTEJOS Y SUMINISTROS C.A', 'DALIDY FESTEJOS Y SUMINISTROS C.A', NULL, NULL, NULL, 'ACTIVO'),
(13, 'V113363025', 'INVERSIONES JOSE MANUEL FIGUEROA URETRA', 'INVERSIONES JOSE MANUEL FIGUEROA URETRA', NULL, NULL, NULL, 'ACTIVO'),
(14, 'J298483229', 'CHARCUTERIA GL, C.A', 'CHARCUTERIA GL, C.A', NULL, NULL, NULL, 'ACTIVO'),
(15, 'J402862768', 'INVERSIONES MARCANO PEREZ', 'INVERSIONES MARCANO PEREZ', NULL, NULL, NULL, 'ACTIVO'),
(16, 'V110081185', 'OVI, C.A', 'OVI, C.A', NULL, NULL, NULL, 'ACTIVO'),
(17, 'J403117152', 'CHARCUTERIA PIÃ‘A', 'CHARCUTERIA PIÃ‘A', '', '', '', 'ACTIVO'),
(18, 'J405873922', 'INVERSIONES Y SERVICIOS YELI', 'INVERSIONES Y SERVICIOS YELI', NULL, NULL, NULL, 'ACTIVO'),
(19, 'J409707555', 'SUPERFERIA LA TOMATINA', 'SUPERFERIA LA TOMATINA', NULL, NULL, NULL, 'ACTIVO'),
(20, 'J409238652', 'RYGYN BODEGON', 'RYGYN BODEGON', NULL, NULL, NULL, 'ACTIVO'),
(21, 'J309655507', 'PANADERIA Y PASTELERIA ANA NELLY, C.A', 'PANADERIA Y PASTELERIA ANA NELLY, C.A', NULL, NULL, NULL, 'ACTIVO'),
(38, 'J403084165', 'BODEGON SAN ANTONIO', 'BODEGON SAN ANTONIO', NULL, NULL, NULL, 'ACTIVO'),
(39, 'J407510320', 'BODEGON PICHON', 'BODEGON PICHON', '', '', '', 'INACTIVO'),
(40, 'J312148578', 'CHARCUTERIA LOS HERMANOS', 'CHARCUTERIA LOS HERMANOS', NULL, NULL, NULL, 'ACTIVO'),
(41, 'V121261908', 'CHARCUTERIA KASUPO AMADO DAVID', 'CHARCUTERIA KASUPO AMADO DAVID', NULL, NULL, NULL, 'ACTIVO'),
(42, 'J407769308', 'INVERSIONES DAVI Y WILLIAN', 'INVERSIONES DAVI Y WILLIAN', NULL, NULL, NULL, 'ACTIVO'),
(43, 'J405655933', 'INVERSIONES LA POPULAR', 'INVERSIONES LA POPULAR', NULL, NULL, NULL, 'ACTIVO'),
(44, 'J408129035', 'CHARCUTERIA EL GRAN YO SOY', 'CHARCUTERIA EL GRAN YO SOY', NULL, NULL, NULL, 'ACTIVO'),
(45, 'V216769798', 'JULIE DEBRA', 'JULIE DEBRA', NULL, NULL, NULL, 'ACTIVO'),
(46, 'J407742850', 'MULTIVARIEDADES MIS DOS ESTRELLAS', 'MULTIVARIEDADES MIS DOS ESTRELLAS', NULL, NULL, NULL, 'ACTIVO'),
(47, 'J408729652', 'INVERSIONES MI PROVISION', 'INVERSIONES MI PROVISION', NULL, NULL, NULL, 'ACTIVO'),
(48, 'J40599830', 'INVERSIONES LA BEDICION DE DIOS', 'INVERSIONES LA BEDICION DE DIOS', NULL, NULL, NULL, 'ACTIVO'),
(49, 'KA VALLENILL', 'KA VALLENILLA', 'KA VALLENILLA', NULL, NULL, NULL, 'ACTIVO'),
(50, 'OVI, C.A', 'OVI, C.A', 'OVI, C.A', NULL, NULL, NULL, 'ACTIVO'),
(51, 'V113392157', 'LOS PANES DE ARELYS', 'LOS PANES DE ARELYS', NULL, NULL, NULL, 'ACTIVO'),
(52, '123456', 'prueba 2', 'prueba 2', 'prueba 2', 'prueba 2', 'prueba2@prueba.com', 'ACTIVO'),
(53, '1234567', 'prueba 4', '', 'NULL', 'prueba 4', 'prueba 4@prueba .cpm', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cuentas_bancarias`
--

CREATE TABLE IF NOT EXISTS `tbl_cuentas_bancarias` (
  `idcuenta_bnc` int(11) NOT NULL AUTO_INCREMENT,
  `nro_cuenta` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `banco` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idcuenta_bnc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `tbl_cuentas_bancarias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cuentas_cobrar`
--

CREATE TABLE IF NOT EXISTS `tbl_cuentas_cobrar` (
  `idcuenta_cob` int(11) NOT NULL AUTO_INCREMENT,
  `fkventa` int(11) NOT NULL,
  `monto_total` double(11,2) NOT NULL,
  `monto_debe` double(11,2) NOT NULL,
  `monto_haber` double(11,2) DEFAULT '0.00',
  `estatus` varchar(10) COLLATE utf8_spanish2_ci DEFAULT 'PENDIENTE',
  PRIMARY KEY (`idcuenta_cob`),
  KEY `fkventa` (`fkventa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `tbl_cuentas_cobrar`
--

INSERT INTO `tbl_cuentas_cobrar` (`idcuenta_cob`, `fkventa`, `monto_total`, `monto_debe`, `monto_haber`, `estatus`) VALUES
(6, 18, 102000.00, 102000.00, 0.00, 'PENDIENTE'),
(7, 19, 121200.00, 0.00, 121200.00, 'PAGADO');

--
-- (Evento) desencadenante `tbl_cuentas_cobrar`
--
DROP TRIGGER IF EXISTS `trigger_validar_venta`;
DELIMITER //
CREATE TRIGGER `trigger_validar_venta` AFTER UPDATE ON `tbl_cuentas_cobrar`
 FOR EACH ROW begin
update tbl_ventas set estatus_venta='PAGADA' where total_neto<=new.monto_haber and idventa=new.fkventa and estatus_venta='PENDIENTE';
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cuentas_pagar`
--

CREATE TABLE IF NOT EXISTS `tbl_cuentas_pagar` (
  `idcuenta_pag` int(11) NOT NULL AUTO_INCREMENT,
  `fkdeuda` int(11) NOT NULL,
  `tipo_deuda` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `tabla` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `monto_total` double(11,2) NOT NULL,
  `monto_pagar` double(11,2) DEFAULT '0.00',
  `monto_debe` double(11,2) NOT NULL,
  `estatus_ctapag` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`idcuenta_pag`),
  KEY `fkdeuda` (`fkdeuda`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `tbl_cuentas_pagar`
--

INSERT INTO `tbl_cuentas_pagar` (`idcuenta_pag`, `fkdeuda`, `tipo_deuda`, `tabla`, `monto_total`, `monto_pagar`, `monto_debe`, `estatus_ctapag`, `fecha`) VALUES
(8, 16, 'PEDIDO', 'tbl_pedidos', 60000.00, 55000.00, 5000.00, 'POR PAGAR', '2018-10-20'),
(9, 18, 'VENDEDOR', 'tbl_precios_ventas_vendedores', 22000.00, 22000.00, 0.00, 'POR PAGAR', '2018-10-20'),
(10, 19, 'VENDEDOR', 'tbl_precios_ventas_vendedores', 34800.00, 34800.00, 0.00, 'POR PAGAR', '2018-10-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_detalles_pedidos`
--

CREATE TABLE IF NOT EXISTS `tbl_detalles_pedidos` (
  `iddetalle_ped` int(11) NOT NULL AUTO_INCREMENT,
  `fkpedido` int(11) NOT NULL,
  `fkproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `medida` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `precio_unitario` double(11,2) DEFAULT NULL,
  `precio_blt` double(11,2) DEFAULT NULL,
  `subtotal` double(11,2) DEFAULT NULL,
  `estatus_det_pedido` varchar(10) COLLATE utf8_spanish2_ci DEFAULT 'EN ESPERA',
  PRIMARY KEY (`iddetalle_ped`),
  KEY `fkpedido` (`fkpedido`),
  KEY `fkproducto` (`fkproducto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `tbl_detalles_pedidos`
--

INSERT INTO `tbl_detalles_pedidos` (`iddetalle_ped`, `fkpedido`, `fkproducto`, `cantidad`, `medida`, `precio_unitario`, `precio_blt`, `subtotal`, `estatus_det_pedido`) VALUES
(3, 16, 7, 12, 'BLT.', NULL, 3000.00, 36000.00, 'POR PAGAR'),
(4, 16, 6, 12, 'BLT.', NULL, 2000.00, 24000.00, 'POR PAGAR');

--
-- (Evento) desencadenante `tbl_detalles_pedidos`
--
DROP TRIGGER IF EXISTS `aumento_inventario`;
DELIMITER //
CREATE TRIGGER `aumento_inventario` AFTER UPDATE ON `tbl_detalles_pedidos`
 FOR EACH ROW BEGIN 

IF NEW.estatus_det_pedido = 'POR PAGAR' THEN
update tbl_productos set 
       cantidad_blt = cantidad_blt + NEW.cantidad,
       cantidad_unitaria = cantidad_unitaria + (cant_unidades_en_blt * NEW.cantidad)
where idproducto = NEW.fkproducto;

update tbl_productos set estatus_prod='ACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria >0;

update tbl_productos set estatus_prod='INACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria <=0;

ELSEIF  NEW.estatus_det_pedido = 'ANULADO' THEN
update tbl_productos set 
       cantidad_blt = cantidad_blt - NEW.cantidad,
       cantidad_unitaria = cantidad_unitaria - (cant_unidades_en_blt * NEW.cantidad)
where idproducto = NEW.fkproducto;

update tbl_productos set estatus_prod='ACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria >0;

update tbl_productos set estatus_prod='INACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria <=0;

END IF;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `disminuir_inventario`;
DELIMITER //
CREATE TRIGGER `disminuir_inventario` AFTER DELETE ON `tbl_detalles_pedidos`
 FOR EACH ROW BEGIN 

IF OLD.estatus_det_pedido = 'PAGADO' THEN
update tbl_productos set 
       cantidad_blt = cantidad_blt - OLD.cantidad,
       cantidad_unitaria = cantidad_unitaria - (cant_unidades_en_blt * OLD.cantidad)
where idproducto = OLD.fkproducto;

update tbl_productos set estatus_prod='ACTIVO' 
where idproducto = OLD.fkproducto and cantidad_unitaria >0;

update tbl_productos set estatus_prod='INACTIVO' 
where idproducto = OLD.fkproducto and cantidad_unitaria <=0;
END IF;

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_detalles_ventas`
--

CREATE TABLE IF NOT EXISTS `tbl_detalles_ventas` (
  `fkventa` int(11) NOT NULL,
  `fkproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `medida` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` double(11,2) NOT NULL,
  `precio_total` double(11,2) NOT NULL,
  `costo_prod` double(11,2) DEFAULT NULL,
  `precio_factura` double(11,2) DEFAULT NULL,
  KEY `fkproducto` (`fkproducto`),
  KEY `fkventa` (`fkventa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcar la base de datos para la tabla `tbl_detalles_ventas`
--

INSERT INTO `tbl_detalles_ventas` (`fkventa`, `fkproducto`, `cantidad`, `medida`, `precio`, `precio_total`, `costo_prod`, `precio_factura`) VALUES
(18, 7, 12, 'BLT.', 6000.00, 72000.00, 3000.00, 6500.00),
(18, 6, 10, 'BLT.', 3000.00, 30000.00, 2000.00, 3500.00),
(19, 7, 15, 'BLT.', 6000.00, 90000.00, 3000.00, 8500.00),
(19, 6, 12, 'BLT.', 2600.00, 31200.00, 2000.00, 3500.00);

--
-- (Evento) desencadenante `tbl_detalles_ventas`
--
DROP TRIGGER IF EXISTS `trigger_restar_inventario`;
DELIMITER //
CREATE TRIGGER `trigger_restar_inventario` AFTER INSERT ON `tbl_detalles_ventas`
 FOR EACH ROW BEGIN
DECLARE y INT DEFAULT 0;
DECLARE x DOUBLE (11,2) DEFAULT 0.0;
DECLARE z INT DEFAULT 0;
DECLARE cantuniblt INT DEFAULT 0;
   IF NEW.medida='BLT.' THEN
        UPDATE tbl_productos SET cantidad_blt=cantidad_blt-NEW.cantidad, cantidad_unitaria=cantidad_unitaria-cant_unidades_en_blt WHERE idproducto=NEW.fkproducto;
   ELSE        
        SELECT cant_unidades_en_blt INTO cantuniblt FROM tbl_productos WHERE idproducto=NEW.fkproducto;
        IF NEW.cantidad >= cantuniblt THEN
               SET y:=NEW.cantidad / cantuniblt;
               SET x:=(NEW.cantidad / cantuniblt) - y;
               SET z:= x*cantuniblt;
               UPDATE tbl_productos SET cantidad_unitaria=cantidad_unitaria-NEW.cantidad, cantidad_blt=cantidad_blt-y  WHERE idproducto=NEW.fkproducto;
        ELSE
              UPDATE tbl_productos SET cantidad_unitaria=cantidad_unitaria-NEW.cantidad  WHERE idproducto=NEW.fkproducto;              
        END IF;        
   END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_fletes`
--

CREATE TABLE IF NOT EXISTS `tbl_fletes` (
  `idflete` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `transportista` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fkpedido` int(11) NOT NULL,
  `costo` double(11,2) NOT NULL,
  `estatus_flete` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idflete`),
  KEY `fkpedido` (`fkpedido`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `tbl_fletes`
--


--
-- (Evento) desencadenante `tbl_fletes`
--
DROP TRIGGER IF EXISTS `trigger_cta_pagar_flete`;
DELIMITER //
CREATE TRIGGER `trigger_cta_pagar_flete` AFTER INSERT ON `tbl_fletes`
 FOR EACH ROW begin
if new.estatus_flete='PAGADO'  then
insert into tbl_cuentas_pagar (fkdeuda, tipo_deuda, tabla, monto_total, monto_pagar, monto_debe, estatus_ctapag, fecha) 
values (new.idflete, 'FLETE' , 'tbl_fletes', new.costo,  new.costo, 0, new.estatus_flete, now());
end if;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_modalidades_pagos`
--

CREATE TABLE IF NOT EXISTS `tbl_modalidades_pagos` (
  `idmodalidad` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idmodalidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `tbl_modalidades_pagos`
--

INSERT INTO `tbl_modalidades_pagos` (`idmodalidad`, `descripcion`) VALUES
(1, '--'),
(2, 'TRANSFERENCIA'),
(3, 'DEPOSITO'),
(4, 'EFECTIVO'),
(5, 'TDD'),
(6, 'TDC'),
(7, 'CHEQUE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_nros_cuentas_vendedores`
--

CREATE TABLE IF NOT EXISTS `tbl_nros_cuentas_vendedores` (
  `fkvendedor` int(11) NOT NULL,
  `banco` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `cuenta` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_cuenta` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `estatus_cta_vend` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  KEY `fkvendedor` (`fkvendedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcar la base de datos para la tabla `tbl_nros_cuentas_vendedores`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_pagos`
--

CREATE TABLE IF NOT EXISTS `tbl_pagos` (
  `idpago` int(11) NOT NULL AUTO_INCREMENT,
  `fkdeuda` int(11) NOT NULL,
  `tipo_pago` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `monto` double(11,2) NOT NULL,
  `modalidad_pago` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `banco` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  `nro_referencia` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_pago` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idpago`),
  KEY `fkdeuda` (`fkdeuda`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `tbl_pagos`
--

INSERT INTO `tbl_pagos` (`idpago`, `fkdeuda`, `tipo_pago`, `monto`, `modalidad_pago`, `banco`, `fecha`, `nro_referencia`, `estatus_pago`) VALUES
(3, 16, 'PEDIDO', 5000.00, 'CHEQUE', 'vene', '2018-10-20', '6y6y6', 'PAGADO');

--
-- (Evento) desencadenante `tbl_pagos`
--
DROP TRIGGER IF EXISTS `trigger_pagos`;
DELIMITER //
CREATE TRIGGER `trigger_pagos` AFTER INSERT ON `tbl_pagos`
 FOR EACH ROW begin

UPDATE tbl_cuentas_pagar SET 
                         monto_pagar=monto_pagar-new.monto,
                         monto_debe=monto_debe+new.monto
WHERE tipo_deuda=new.tipo_pago AND fkdeuda=new.fkdeuda;

UPDATE tbl_cuentas_pagar SET 
                         estatus_ctapag='PAGADO'
WHERE tipo_deuda=new.tipo_pago AND fkdeuda=new.fkdeuda AND (monto_pagar<=0 OR monto_debe>=monto_total);

UPDATE tbl_cuentas_pagar SET 
                         estatus_ctapag='POR PAGAR'
WHERE tipo_deuda=new.tipo_pago AND fkdeuda=new.fkdeuda AND (monto_pagar>0 OR monto_debe<monto_total);

end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_parametros`
--

CREATE TABLE IF NOT EXISTS `tbl_parametros` (
  `iva` double(11,2) DEFAULT NULL,
  `nombre_empresa` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `rif` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ciudad` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `region` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nombre_encargado` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fono` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `porc_ganancia` double(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcar la base de datos para la tabla `tbl_parametros`
--

INSERT INTO `tbl_parametros` (`iva`, `nombre_empresa`, `rif`, `direccion`, `ciudad`, `region`, `nombre_encargado`, `fono`, `porc_ganancia`) VALUES
(16.00, 'DISTRIBUIDORA MERITZE ORTEGA, C.A', 'J-30953444-0', 'CALLE MISISSIPI CASA NRO. 2 URB FCO. DE MIRANDA SAN FELIX - EDO.  BOLIVAR', 'Guayana', 'San Felix', 'Carmen Monterola', '0414-', 30.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_pedidos`
--

CREATE TABLE IF NOT EXISTS `tbl_pedidos` (
  `idpedido` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_pedido` datetime NOT NULL,
  `fkproveedor` int(11) NOT NULL,
  `forma_pago` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nro_operacion` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha_llegada` date DEFAULT NULL,
  `subtotal` double(11,2) DEFAULT NULL,
  `iva` double(11,2) DEFAULT NULL,
  `total_pedido` double(11,2) DEFAULT NULL,
  `estatus_pedido` varchar(10) COLLATE utf8_spanish2_ci DEFAULT 'EN ESPERA',
  PRIMARY KEY (`idpedido`),
  KEY `fkproveedor` (`fkproveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=17 ;

--
-- Volcar la base de datos para la tabla `tbl_pedidos`
--

INSERT INTO `tbl_pedidos` (`idpedido`, `fecha_pedido`, `fkproveedor`, `forma_pago`, `nro_operacion`, `fecha_llegada`, `subtotal`, `iva`, `total_pedido`, `estatus_pedido`) VALUES
(16, '2018-10-20 19:37:35', 3, '', '', '2018-10-20', 60000.00, 0.00, 60000.00, 'POR PAGAR');

--
-- (Evento) desencadenante `tbl_pedidos`
--
DROP TRIGGER IF EXISTS `trigger_cta_pagar_pedido`;
DELIMITER //
CREATE TRIGGER `trigger_cta_pagar_pedido` AFTER UPDATE ON `tbl_pedidos`
 FOR EACH ROW begin

IF NEW.estatus_pedido='POR PAGAR'  THEN
     IF EXISTS(SELECT fkdeuda FROM tbl_cuentas_pagar WHERE  tipo_deuda='PEDIDO' AND fkdeuda=NEW.idpedido)  THEN
          UPDATE tbl_cuentas_pagar SET 
                         monto_total=NEW.total_pedido, 
                        monto_pagar=NEW.total_pedido 
          WHERE tipo_deuda='PEDIDO' AND fkdeuda=NEW.idpedido;
     ELSE
         insert into tbl_cuentas_pagar (fkdeuda, tipo_deuda, tabla, monto_total, monto_pagar, monto_debe, estatus_ctapag, fecha) 
         values (new.idpedido, 'PEDIDO' , 'tbl_pedidos', new.total_pedido,  new.total_pedido, 0, 'POR PAGAR', now());
     END IF;
END IF;
IF NEW.estatus_pedido='EN ESPERA' THEN
       UPDATE tbl_cuentas_pagar SET 
                         estatus_ctapag='POR PAGAR'
       WHERE tipo_deuda='PEDIDO' AND fkdeuda=NEW.idpedido;
END IF;
UPDATE tbl_cuentas_pagar SET 
                         estatus_ctapag='PAGADA'
WHERE tipo_deuda='PEDIDO' AND fkdeuda=NEW.idpedido AND (monto_pagar<=0 OR monto_debe>=monto_total);
UPDATE tbl_cuentas_pagar SET 
                         estatus_ctapag='POR PAGAR'
WHERE tipo_deuda='PEDIDO' AND fkdeuda=NEW.idpedido AND (monto_pagar>0 OR monto_debe<monto_total);

IF NEW.estatus_pedido='ANULADO' THEN
       UPDATE tbl_cuentas_pagar SET 
                         estatus_ctapag='ANULADA'
       WHERE tipo_deuda='PEDIDO' AND fkdeuda=NEW.idpedido;
END IF;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_precios_productos`
--

CREATE TABLE IF NOT EXISTS `tbl_precios_productos` (
  `fkproducto` int(11) NOT NULL,
  `descripcion_precio` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_unitario` double(11,2) DEFAULT NULL,
  `precio_blt` double(11,2) DEFAULT NULL,
  `estatus_precio` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  `fecha_ultim_actualizacion` date NOT NULL,
  KEY `fkproducto` (`fkproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcar la base de datos para la tabla `tbl_precios_productos`
--

INSERT INTO `tbl_precios_productos` (`fkproducto`, `descripcion_precio`, `precio_unitario`, `precio_blt`, `estatus_precio`, `fecha_ultim_actualizacion`) VALUES
(6, 'USUARA', 125.00, 3000.00, 'ACTIVO', '2018-10-20'),
(6, 'Precio Regulado', 108.33, 2600.00, 'ACTIVO', '2018-10-20'),
(7, 'USURA', 250.00, 6000.00, 'ACTIVO', '2018-10-20'),
(7, 'Precio Regulado', 162.50, 3900.00, 'ACTIVO', '2018-10-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_precios_ventas_vendedores`
--

CREATE TABLE IF NOT EXISTS `tbl_precios_ventas_vendedores` (
  `fkventa` int(11) NOT NULL,
  `fkproducto` int(11) NOT NULL,
  `precio_vendedor` double(11,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` double(11,2) NOT NULL,
  KEY `fkventa` (`fkventa`),
  KEY `fkproducto` (`fkproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcar la base de datos para la tabla `tbl_precios_ventas_vendedores`
--

INSERT INTO `tbl_precios_ventas_vendedores` (`fkventa`, `fkproducto`, `precio_vendedor`, `cantidad`, `total`) VALUES
(18, 7, 4000.00, 12, 48000.00),
(18, 6, 5000.00, 10, 50000.00),
(19, 7, 3000.00, 15, 36000.00),
(19, 6, 8000.00, 12, 120000.00);

--
-- (Evento) desencadenante `tbl_precios_ventas_vendedores`
--
DROP TRIGGER IF EXISTS `trigger_cta_pagar_vendedor`;
DELIMITER //
CREATE TRIGGER `trigger_cta_pagar_vendedor` AFTER INSERT ON `tbl_precios_ventas_vendedores`
 FOR EACH ROW begin
IF EXISTS(SELECT fkdeuda FROM tbl_cuentas_pagar WHERE  tipo_deuda='VENDEDOR' AND fkdeuda=NEW.fkventa)  THEN
          UPDATE tbl_cuentas_pagar SET 
                         monto_total=monto_total+NEW.total, 
                        monto_pagar=monto_pagar+NEW.total 
          WHERE tipo_deuda='VENDEDOR' AND fkdeuda=NEW.fkventa;
ELSE
         INSERT INTO tbl_cuentas_pagar (fkdeuda, tipo_deuda, tabla, monto_total, monto_pagar, monto_debe, estatus_ctapag, fecha) 
        VALUES (NEW.fkventa, 'VENDEDOR' , 'tbl_precios_ventas_vendedores', NEW.total,  NEW.total, 0, 'POR PAGAR', now());
END IF;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_productos`
--

CREATE TABLE IF NOT EXISTS `tbl_productos` (
  `idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_prod` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `marca` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad_unitaria` int(11) NOT NULL,
  `cantidad_blt` int(11) NOT NULL,
  `cant_unidades_en_blt` int(11) NOT NULL,
  `costo` double(11,2) DEFAULT NULL,
  `estatus_prod` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `tbl_productos`
--

INSERT INTO `tbl_productos` (`idproducto`, `descripcion_prod`, `marca`, `cantidad_unitaria`, `cantidad_blt`, `cant_unidades_en_blt`, `costo`, `estatus_prod`) VALUES
(6, 'ARROZ BLANCO', 'MARY', 240, 110, 24, 2000.00, 'ACTIVO'),
(7, 'PASTA LARGA', 'RONCO', 240, 35, 24, 3000.00, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_proveedores`
--

CREATE TABLE IF NOT EXISTS `tbl_proveedores` (
  `idproveedor` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `razon_social` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `representante_legal` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_prov` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idproveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `tbl_proveedores`
--

INSERT INTO `tbl_proveedores` (`idproveedor`, `rif`, `razon_social`, `representante_legal`, `direccion`, `fono`, `email`, `estatus_prov`) VALUES
(3, '0000000', 'HERMANOS MACANA', 'PEDRO LOPEZ', '', '', '', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_regalias`
--

CREATE TABLE IF NOT EXISTS `tbl_regalias` (
  `idregalia` int(11) NOT NULL AUTO_INCREMENT,
  `fkpedido` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `observacion` varchar(200) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `beneficiario` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `responsable` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fkproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `medicion` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `estatus_reg` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idregalia`),
  KEY `fkproducto` (`fkproducto`),
  KEY `fkpedido` (`fkpedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `tbl_regalias`
--


--
-- (Evento) desencadenante `tbl_regalias`
--
DROP TRIGGER IF EXISTS `trigger_restar_inventario_regalia`;
DELIMITER //
CREATE TRIGGER `trigger_restar_inventario_regalia` AFTER INSERT ON `tbl_regalias`
 FOR EACH ROW BEGIN
DECLARE y INT DEFAULT 0;
DECLARE x DOUBLE (11,2) DEFAULT 0.0;
DECLARE z INT DEFAULT 0;
DECLARE cantuniblt INT DEFAULT 0;
   IF NEW.medicion='BLT.' THEN
        UPDATE tbl_productos SET cantidad_blt=cantidad_blt-NEW.cantidad, cantidad_unitaria=cantidad_unitaria-cant_unidades_en_blt WHERE idproducto=NEW.fkproducto;
   ELSE        
        SELECT cant_unidades_en_blt INTO cantuniblt FROM tbl_productos WHERE idproducto=NEW.fkproducto;
        IF NEW.cantidad >= cantuniblt THEN
               SET y:=NEW.cantidad / cantuniblt;
               SET x:=(NEW.cantidad / cantuniblt) - y;
               SET z:= x*cantuniblt;
               UPDATE tbl_productos SET cantidad_unitaria=cantidad_unitaria-NEW.cantidad, cantidad_blt=cantidad_blt-y  WHERE idproducto=NEW.fkproducto;
        ELSE
              UPDATE tbl_productos SET cantidad_unitaria=cantidad_unitaria-NEW.cantidad  WHERE idproducto=NEW.fkproducto;              
        END IF;        
   END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

CREATE TABLE IF NOT EXISTS `tbl_usuarios` (
  `login` varchar(6) COLLATE utf8_spanish2_ci NOT NULL,
  `passw` varchar(32) COLLATE utf8_spanish2_ci NOT NULL,
  `user_name` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nivel` int(11) NOT NULL,
  `email` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `pregunta_secreta_1` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `respuesta_secreta_1` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pregunta_secreta_2` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `respuesta_secreta_2` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `estatus_user` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcar la base de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`login`, `passw`, `user_name`, `nivel`, `email`, `pregunta_secreta_1`, `respuesta_secreta_1`, `pregunta_secreta_2`, `respuesta_secreta_2`, `estatus_user`) VALUES
('rlunar', '827ccb0eea8a706c4c34a16891f84e7b', 'Roberto Lunar', 1, 'roberto_lunar@yahoo.', '-', '-', '-', '-', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_vendedores`
--

CREATE TABLE IF NOT EXISTS `tbl_vendedores` (
  `idvendedor` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_vend` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idvendedor`),
  UNIQUE KEY `rif` (`rif`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `tbl_vendedores`
--

INSERT INTO `tbl_vendedores` (`idvendedor`, `rif`, `nombres`, `email`, `fono`, `estatus_vend`) VALUES
(3, '16395343', 'ROBERTO', 'ROBERTOCLUNARG@GMAIL.COM', '04140952386', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ventas`
--

CREATE TABLE IF NOT EXISTS `tbl_ventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `fkvendedor` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fkcliente` int(11) NOT NULL,
  `iva` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `subtotal` double(11,2) NOT NULL,
  `total_neto` double(11,2) NOT NULL,
  `excento` double(11,2) NOT NULL,
  `estatus_venta` varchar(10) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'pendiente, pagada, anulada',
  PRIMARY KEY (`idventa`),
  KEY `fkcliente` (`fkcliente`),
  KEY `fkvendedor` (`fkvendedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=20 ;

--
-- Volcar la base de datos para la tabla `tbl_ventas`
--

INSERT INTO `tbl_ventas` (`idventa`, `fkvendedor`, `fecha`, `fkcliente`, `iva`, `subtotal`, `total_neto`, `excento`, `estatus_venta`) VALUES
(18, 3, '2018-10-20 20:06:27', 38, '16', 102000.00, 102000.00, 0.00, 'PENDIENTE'),
(19, 3, '2018-10-20 20:12:21', 12, '16', 121200.00, 121200.00, 0.00, 'PAGADA');

--
-- (Evento) desencadenante `tbl_ventas`
--
DROP TRIGGER IF EXISTS `trigger_cuenta_por_cobrar`;
DELIMITER //
CREATE TRIGGER `trigger_cuenta_por_cobrar` AFTER INSERT ON `tbl_ventas`
 FOR EACH ROW BEGIN 

insert into tbl_cuentas_cobrar (fkventa, monto_total, monto_debe, monto_haber, estatus) values (new.idventa, new.total_neto, new.total_neto, 0.0, new.estatus_venta);

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_borrarcuenta_por_pagar`;
DELIMITER //
CREATE TRIGGER `trigger_borrarcuenta_por_pagar` AFTER UPDATE ON `tbl_ventas`
 FOR EACH ROW begin
if new.estatus_venta='ANULADA' then
   delete from cuentas_cobrar where fkventa=new.idventa;
   delete from cuentas_pagar where  fkdeuda=new.idventa and tipo_deuda='VENDEDOR';
end if;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_abonos`
--
CREATE TABLE IF NOT EXISTS `vw_abonos` (
`idabono` int(11)
,`fkventa` int(11)
,`monto` double(11,2)
,`modalidad_pago` varchar(20)
,`fecha_abono` date
,`nro_referencia` varchar(20)
,`fkvendedor` int(11)
,`fecha_venta` varchar(10)
,`fkcliente` int(11)
,`total_neto` double(11,2)
,`estatus_venta` varchar(10)
,`vendedor` varchar(117)
,`nombre_cliente` varchar(67)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_cuentas_cobrar`
--
CREATE TABLE IF NOT EXISTS `vw_cuentas_cobrar` (
`idcuenta_cob` int(11)
,`fkventa` int(11)
,`monto_total` double(11,2)
,`monto_debe` double(11,2)
,`monto_haber` double(11,2)
,`estatus` varchar(10)
,`fkvendedor` int(11)
,`fecha` varchar(10)
,`estatus_venta` varchar(10)
,`fkcliente` int(11)
,`vendedor` varchar(113)
,`cliente` varchar(63)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_cuentas_pagar`
--
CREATE TABLE IF NOT EXISTS `vw_cuentas_pagar` (
`idcuenta_pag` int(11)
,`fkdeuda` int(11)
,`tipo_deuda` varchar(10)
,`tabla` varchar(50)
,`monto_total` double(11,2)
,`monto_pagar` double(11,2)
,`monto_debe` double(11,2)
,`estatus_ctapag` varchar(10)
,`fecha` date
,`fk` int(11)
,`descripcion` varchar(200)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_formas_pagos`
--
CREATE TABLE IF NOT EXISTS `vw_formas_pagos` (
`idpago` int(11)
,`fkdeuda` int(11)
,`tipo_pago` varchar(10)
,`monto` double(11,2)
,`modalidad_pago` varchar(20)
,`banco` varchar(50)
,`fecha` date
,`nro_referencia` varchar(20)
,`estatus_pago` varchar(10)
,`descripcion` varchar(200)
,`fkacreedor` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_graficos_1`
--
CREATE TABLE IF NOT EXISTS `vw_graficos_1` (
`label` varchar(8)
,`dato` double(19,2)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_graficos_2`
--
CREATE TABLE IF NOT EXISTS `vw_graficos_2` (
`label` varchar(8)
,`dato` double(19,2)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_precios_productos_blt`
--
CREATE TABLE IF NOT EXISTS `vw_precios_productos_blt` (
`descripcion_precio` varchar(46)
,`precio` double(11,2)
,`fkproducto` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_precios_productos_unit`
--
CREATE TABLE IF NOT EXISTS `vw_precios_productos_unit` (
`descripcion_precio` varchar(46)
,`precio` double(11,2)
,`fkproducto` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productos`
--
CREATE TABLE IF NOT EXISTS `vw_productos` (
`idproducto` int(11)
,`descripcion` varchar(81)
,`estatus_prod` varchar(10)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productos_pedidos`
--
CREATE TABLE IF NOT EXISTS `vw_productos_pedidos` (
`fkproducto` int(11)
,`fkpedido` int(11)
,`producto` varchar(81)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_resumen_general`
--
CREATE TABLE IF NOT EXISTS `vw_resumen_general` (
`idventa` int(11)
,`fkvendedor` int(11)
,`fecha` datetime
,`fkcliente` int(11)
,`iva` varchar(12)
,`subtotal` double(11,2)
,`estatus_venta` varchar(10)
,`cliente` varchar(63)
,`vendedor` varchar(113)
,`fkproducto` int(11)
,`cantidad` varchar(21)
,`precio` double(11,2)
,`monto_venta` double(11,2)
,`producto` varchar(81)
,`monto` double(11,2)
,`modalidad_pago` varchar(20)
,`fecha_abono` date
,`nro_referencia` varchar(20)
,`precio_vendedor` double(11,2)
,`total_venta_vendedor` double(11,2)
,`precio_venta` double(11,2)
,`pago_vendedor` double(19,2)
,`ganancia` double(19,2)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_vendedores_ctaspagar`
--
CREATE TABLE IF NOT EXISTS `vw_vendedores_ctaspagar` (
`fk` int(11)
,`descripcion` varchar(200)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_ventas`
--
CREATE TABLE IF NOT EXISTS `vw_ventas` (
`idventa` int(11)
,`idvendedor` int(11)
,`rif_vendedor` varchar(12)
,`nombres` varchar(100)
,`idcliente` int(11)
,`rif_cliente` varchar(12)
,`razon_social` varchar(50)
,`fecha` datetime
,`subtotal` double(11,2)
,`total_neto` double(11,2)
,`excento` double(11,2)
,`estatus_venta` varchar(10)
);
-- --------------------------------------------------------

--
-- Estructura para la vista `vw_abonos`
--
DROP TABLE IF EXISTS `vw_abonos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_abonos` AS select `tbl_abonos`.`idabono` AS `idabono`,`tbl_abonos`.`fkventa` AS `fkventa`,`tbl_abonos`.`monto` AS `monto`,`tbl_abonos`.`modalidad_pago` AS `modalidad_pago`,`tbl_abonos`.`fecha` AS `fecha_abono`,`tbl_abonos`.`nro_referencia` AS `nro_referencia`,`tbl_ventas`.`fkvendedor` AS `fkvendedor`,date_format(`tbl_ventas`.`fecha`,'%Y-%m-%d') AS `fecha_venta`,`tbl_ventas`.`fkcliente` AS `fkcliente`,`tbl_ventas`.`total_neto` AS `total_neto`,`tbl_ventas`.`estatus_venta` AS `estatus_venta`,concat(`tbl_vendedores`.`nombres`,' Rif.',`tbl_vendedores`.`rif`) AS `vendedor`,concat(`tbl_clientes`.`razon_social`,' Rif.',`tbl_clientes`.`rif`) AS `nombre_cliente` from (((`tbl_abonos` join `tbl_ventas` on((`tbl_abonos`.`fkventa` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) order by `tbl_abonos`.`fecha` desc;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_cuentas_cobrar`
--
DROP TABLE IF EXISTS `vw_cuentas_cobrar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cuentas_cobrar` AS select `tbl_cuentas_cobrar`.`idcuenta_cob` AS `idcuenta_cob`,`tbl_cuentas_cobrar`.`fkventa` AS `fkventa`,`tbl_cuentas_cobrar`.`monto_total` AS `monto_total`,`tbl_cuentas_cobrar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_cobrar`.`monto_haber` AS `monto_haber`,`tbl_cuentas_cobrar`.`estatus` AS `estatus`,`tbl_ventas`.`fkvendedor` AS `fkvendedor`,date_format(`tbl_ventas`.`fecha`,'%Y-%m-%d') AS `fecha`,`tbl_ventas`.`estatus_venta` AS `estatus_venta`,`tbl_ventas`.`fkcliente` AS `fkcliente`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `vendedor`,concat(`tbl_clientes`.`razon_social`,' ',`tbl_clientes`.`rif`) AS `cliente` from (((`tbl_cuentas_cobrar` join `tbl_ventas` on((`tbl_cuentas_cobrar`.`fkventa` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) order by `tbl_ventas`.`fecha` desc;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_cuentas_pagar`
--
DROP TABLE IF EXISTS `vw_cuentas_pagar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cuentas_pagar` AS select `tbl_cuentas_pagar`.`idcuenta_pag` AS `idcuenta_pag`,`tbl_cuentas_pagar`.`fkdeuda` AS `fkdeuda`,`tbl_cuentas_pagar`.`tipo_deuda` AS `tipo_deuda`,`tbl_cuentas_pagar`.`tabla` AS `tabla`,`tbl_cuentas_pagar`.`monto_total` AS `monto_total`,`tbl_cuentas_pagar`.`monto_pagar` AS `monto_pagar`,`tbl_cuentas_pagar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_pagar`.`estatus_ctapag` AS `estatus_ctapag`,`tbl_cuentas_pagar`.`fecha` AS `fecha`,`tbl_pedidos`.`fkproveedor` AS `fk`,concat(`tbl_proveedores`.`razon_social`,' ',`tbl_proveedores`.`rif`) AS `descripcion` from ((`tbl_cuentas_pagar` join `tbl_pedidos` on((`tbl_cuentas_pagar`.`fkdeuda` = `tbl_pedidos`.`idpedido`))) join `tbl_proveedores` on((`tbl_pedidos`.`fkproveedor` = `tbl_proveedores`.`idproveedor`))) where (`tbl_cuentas_pagar`.`tipo_deuda` = 'PEDIDO') union select `tbl_cuentas_pagar`.`idcuenta_pag` AS `idcuenta_pag`,`tbl_cuentas_pagar`.`fkdeuda` AS `fkdeuda`,`tbl_cuentas_pagar`.`tipo_deuda` AS `tipo_deuda`,`tbl_cuentas_pagar`.`tabla` AS `tabla`,`tbl_cuentas_pagar`.`monto_total` AS `monto_total`,`tbl_cuentas_pagar`.`monto_pagar` AS `monto_pagar`,`tbl_cuentas_pagar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_pagar`.`estatus_ctapag` AS `estatus_ctapag`,`tbl_cuentas_pagar`.`fecha` AS `fecha`,`tbl_fletes`.`fkpedido` AS `fk`,`tbl_fletes`.`transportista` AS `descripcion` from (`tbl_cuentas_pagar` join `tbl_fletes` on((`tbl_cuentas_pagar`.`fkdeuda` = `tbl_fletes`.`idflete`))) where (`tbl_cuentas_pagar`.`tipo_deuda` = 'FLETE') union select `tbl_cuentas_pagar`.`idcuenta_pag` AS `idcuenta_pag`,`tbl_cuentas_pagar`.`fkdeuda` AS `fkdeuda`,`tbl_cuentas_pagar`.`tipo_deuda` AS `tipo_deuda`,`tbl_cuentas_pagar`.`tabla` AS `tabla`,`tbl_cuentas_pagar`.`monto_total` AS `monto_total`,`tbl_cuentas_pagar`.`monto_pagar` AS `monto_pagar`,`tbl_cuentas_pagar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_pagar`.`estatus_ctapag` AS `estatus_ctapag`,`tbl_cuentas_pagar`.`fecha` AS `fecha`,`tbl_ventas`.`fkvendedor` AS `fk`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `descripcion` from ((`tbl_cuentas_pagar` join `tbl_ventas` on((`tbl_cuentas_pagar`.`fkdeuda` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) where (`tbl_cuentas_pagar`.`tipo_deuda` = 'VENDEDOR');

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_formas_pagos`
--
DROP TABLE IF EXISTS `vw_formas_pagos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_formas_pagos` AS select `tbl_pagos`.`idpago` AS `idpago`,`tbl_pagos`.`fkdeuda` AS `fkdeuda`,`tbl_pagos`.`tipo_pago` AS `tipo_pago`,`tbl_pagos`.`monto` AS `monto`,`tbl_pagos`.`modalidad_pago` AS `modalidad_pago`,`tbl_pagos`.`banco` AS `banco`,`tbl_pagos`.`fecha` AS `fecha`,`tbl_pagos`.`nro_referencia` AS `nro_referencia`,`tbl_pagos`.`estatus_pago` AS `estatus_pago`,concat(`tbl_proveedores`.`razon_social`,' ',`tbl_proveedores`.`rif`) AS `descripcion`,`tbl_pedidos`.`fkproveedor` AS `fkacreedor` from ((`tbl_pagos` join `tbl_pedidos` on((`tbl_pagos`.`fkdeuda` = `tbl_pedidos`.`idpedido`))) join `tbl_proveedores` on((`tbl_pedidos`.`fkproveedor` = `tbl_proveedores`.`idproveedor`))) where (`tbl_pagos`.`tipo_pago` = 'PEDIDO') union select `tbl_pagos`.`idpago` AS `idpago`,`tbl_pagos`.`fkdeuda` AS `fkdeuda`,`tbl_pagos`.`tipo_pago` AS `tipo_pago`,`tbl_pagos`.`monto` AS `monto`,`tbl_pagos`.`modalidad_pago` AS `modalidad_pago`,`tbl_pagos`.`banco` AS `banco`,`tbl_pagos`.`fecha` AS `fecha`,`tbl_pagos`.`nro_referencia` AS `nro_referencia`,`tbl_pagos`.`estatus_pago` AS `estatus_pago`,`tbl_fletes`.`transportista` AS `descripcion`,`tbl_fletes`.`fkpedido` AS `fkacreedor` from (`tbl_pagos` join `tbl_fletes` on((`tbl_pagos`.`fkdeuda` = `tbl_fletes`.`idflete`))) where (`tbl_pagos`.`tipo_pago` = 'FLETE') union select `tbl_pagos`.`idpago` AS `idpago`,`tbl_pagos`.`fkdeuda` AS `fkdeuda`,`tbl_pagos`.`tipo_pago` AS `tipo_pago`,`tbl_pagos`.`monto` AS `monto`,`tbl_pagos`.`modalidad_pago` AS `modalidad_pago`,`tbl_pagos`.`banco` AS `banco`,`tbl_pagos`.`fecha` AS `fecha`,`tbl_pagos`.`nro_referencia` AS `nro_referencia`,`tbl_pagos`.`estatus_pago` AS `estatus_pago`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `descripcion`,`tbl_ventas`.`fkvendedor` AS `fkacreedor` from ((`tbl_pagos` join `tbl_ventas` on((`tbl_pagos`.`fkdeuda` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) where (`tbl_pagos`.`tipo_pago` = 'VENDEDOR');

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_graficos_1`
--
DROP TABLE IF EXISTS `vw_graficos_1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_graficos_1` AS select date_format(`tbl_cuentas_pagar`.`fecha`,'%d-%m-%y') AS `label`,sum(`tbl_cuentas_pagar`.`monto_pagar`) AS `dato` from `tbl_cuentas_pagar` where ((cast(`tbl_cuentas_pagar`.`fecha` as date) >= cast((now() - interval 7 day) as date)) and (`tbl_cuentas_pagar`.`estatus_ctapag` = 'POR PAGAR')) group by date_format(`tbl_cuentas_pagar`.`fecha`,'%d-%m-%y') order by `tbl_cuentas_pagar`.`fecha`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_graficos_2`
--
DROP TABLE IF EXISTS `vw_graficos_2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_graficos_2` AS select date_format(`tbl_ventas`.`fecha`,'%d-%m-%y') AS `label`,sum(`tbl_cuentas_cobrar`.`monto_debe`) AS `dato` from (`tbl_cuentas_cobrar` join `tbl_ventas` on((`tbl_cuentas_cobrar`.`fkventa` = `tbl_ventas`.`idventa`))) where ((cast(`tbl_ventas`.`fecha` as date) >= cast((now() - interval 7 day) as date)) and (`tbl_cuentas_cobrar`.`estatus` = 'PENDIENTE')) group by date_format(`tbl_ventas`.`fecha`,'%d-%m-%y') order by `tbl_ventas`.`fecha`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_precios_productos_blt`
--
DROP TABLE IF EXISTS `vw_precios_productos_blt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_precios_productos_blt` AS select concat(`tbl_precios_productos`.`descripcion_precio`,' Bs. ',convert(`tbl_precios_productos`.`precio_blt` using utf8)) AS `descripcion_precio`,`tbl_precios_productos`.`precio_blt` AS `precio`,`tbl_precios_productos`.`fkproducto` AS `fkproducto` from `tbl_precios_productos` where (`tbl_precios_productos`.`estatus_precio` = 'ACTIVO') order by `tbl_precios_productos`.`descripcion_precio`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_precios_productos_unit`
--
DROP TABLE IF EXISTS `vw_precios_productos_unit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_precios_productos_unit` AS select concat(`tbl_precios_productos`.`descripcion_precio`,' Bs. ',convert(`tbl_precios_productos`.`precio_unitario` using utf8)) AS `descripcion_precio`,`tbl_precios_productos`.`precio_unitario` AS `precio`,`tbl_precios_productos`.`fkproducto` AS `fkproducto` from `tbl_precios_productos` where (`tbl_precios_productos`.`estatus_precio` = 'ACTIVO') order by `tbl_precios_productos`.`descripcion_precio`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos`
--
DROP TABLE IF EXISTS `vw_productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos` AS select `tbl_productos`.`idproducto` AS `idproducto`,concat(`tbl_productos`.`descripcion_prod`,' ',`tbl_productos`.`marca`) AS `descripcion`,`tbl_productos`.`estatus_prod` AS `estatus_prod` from `tbl_productos`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos_pedidos`
--
DROP TABLE IF EXISTS `vw_productos_pedidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos_pedidos` AS select `tbl_detalles_pedidos`.`fkproducto` AS `fkproducto`,`tbl_detalles_pedidos`.`fkpedido` AS `fkpedido`,concat(`tbl_productos`.`descripcion_prod`,' ',`tbl_productos`.`marca`) AS `producto` from (`tbl_detalles_pedidos` join `tbl_productos` on((`tbl_detalles_pedidos`.`fkproducto` = `tbl_productos`.`idproducto`))) order by `tbl_productos`.`descripcion_prod`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_resumen_general`
--
DROP TABLE IF EXISTS `vw_resumen_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_resumen_general` AS select `tbl_ventas`.`idventa` AS `idventa`,`tbl_ventas`.`fkvendedor` AS `fkvendedor`,`tbl_ventas`.`fecha` AS `fecha`,`tbl_ventas`.`fkcliente` AS `fkcliente`,`tbl_ventas`.`iva` AS `iva`,`tbl_ventas`.`subtotal` AS `subtotal`,`tbl_ventas`.`estatus_venta` AS `estatus_venta`,concat(`tbl_clientes`.`razon_social`,' ',`tbl_clientes`.`rif`) AS `cliente`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `vendedor`,`tbl_detalles_ventas`.`fkproducto` AS `fkproducto`,concat(convert(`tbl_detalles_ventas`.`cantidad` using utf8),`tbl_detalles_ventas`.`medida`) AS `cantidad`,`tbl_detalles_ventas`.`precio` AS `precio`,`tbl_detalles_ventas`.`precio_total` AS `monto_venta`,concat(`tbl_productos`.`descripcion_prod`,' ',`tbl_productos`.`marca`) AS `producto`,`tbl_abonos`.`monto` AS `monto`,`tbl_abonos`.`modalidad_pago` AS `modalidad_pago`,`tbl_abonos`.`fecha` AS `fecha_abono`,`tbl_abonos`.`nro_referencia` AS `nro_referencia`,`tbl_precios_ventas_vendedores`.`precio_vendedor` AS `precio_vendedor`,`tbl_precios_ventas_vendedores`.`total` AS `total_venta_vendedor`,`tbl_detalles_ventas`.`precio_total` AS `precio_venta`,(`tbl_precios_ventas_vendedores`.`total` - `tbl_detalles_ventas`.`precio_total`) AS `pago_vendedor`,(`tbl_detalles_ventas`.`precio_total` - (`tbl_detalles_ventas`.`costo_prod` * `tbl_detalles_ventas`.`cantidad`)) AS `ganancia` from ((((((`tbl_ventas` join `tbl_detalles_ventas` on((`tbl_detalles_ventas`.`fkventa` = `tbl_ventas`.`idventa`))) join `tbl_productos` on((`tbl_detalles_ventas`.`fkproducto` = `tbl_productos`.`idproducto`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_precios_ventas_vendedores` on(((`tbl_precios_ventas_vendedores`.`fkventa` = `tbl_detalles_ventas`.`fkventa`) and (`tbl_detalles_ventas`.`fkproducto` = `tbl_precios_ventas_vendedores`.`fkproducto`)))) left join `tbl_abonos` on((`tbl_abonos`.`fkventa` = `tbl_ventas`.`idventa`))) order by `tbl_ventas`.`fecha` desc;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_vendedores_ctaspagar`
--
DROP TABLE IF EXISTS `vw_vendedores_ctaspagar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vendedores_ctaspagar` AS select `vw_cuentas_pagar`.`fk` AS `fk`,`vw_cuentas_pagar`.`descripcion` AS `descripcion` from `vw_cuentas_pagar` where ((`vw_cuentas_pagar`.`estatus_ctapag` = 'POR PAGAR') and (`vw_cuentas_pagar`.`tipo_deuda` = 'VENDEDOR')) group by `vw_cuentas_pagar`.`fk`,`vw_cuentas_pagar`.`descripcion`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_ventas`
--
DROP TABLE IF EXISTS `vw_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_ventas` AS select `tbl_ventas`.`idventa` AS `idventa`,`tbl_vendedores`.`idvendedor` AS `idvendedor`,`tbl_vendedores`.`rif` AS `rif_vendedor`,`tbl_vendedores`.`nombres` AS `nombres`,`tbl_clientes`.`idcliente` AS `idcliente`,`tbl_clientes`.`rif` AS `rif_cliente`,`tbl_clientes`.`razon_social` AS `razon_social`,`tbl_ventas`.`fecha` AS `fecha`,`tbl_ventas`.`subtotal` AS `subtotal`,`tbl_ventas`.`total_neto` AS `total_neto`,`tbl_ventas`.`excento` AS `excento`,`tbl_ventas`.`estatus_venta` AS `estatus_venta` from ((`tbl_ventas` join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) order by `tbl_ventas`.`fecha` desc;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `tbl_abonos`
--
ALTER TABLE `tbl_abonos`
  ADD CONSTRAINT `tbl_abonos_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_cierres_diarios_inv`
--
ALTER TABLE `tbl_cierres_diarios_inv`
  ADD CONSTRAINT `tbl_cierres_diarios_inv_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_cuentas_cobrar`
--
ALTER TABLE `tbl_cuentas_cobrar`
  ADD CONSTRAINT `tbl_cuentas_cobrar_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_detalles_pedidos`
--
ALTER TABLE `tbl_detalles_pedidos`
  ADD CONSTRAINT `tbl_detalles_pedidos_ibfk_1` FOREIGN KEY (`fkpedido`) REFERENCES `tbl_pedidos` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_detalles_pedidos_ibfk_2` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`);

--
-- Filtros para la tabla `tbl_detalles_ventas`
--
ALTER TABLE `tbl_detalles_ventas`
  ADD CONSTRAINT `tbl_detalles_ventas_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`),
  ADD CONSTRAINT `tbl_detalles_ventas_ibfk_2` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_fletes`
--
ALTER TABLE `tbl_fletes`
  ADD CONSTRAINT `tbl_fletes_ibfk_1` FOREIGN KEY (`fkpedido`) REFERENCES `tbl_pedidos` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_nros_cuentas_vendedores`
--
ALTER TABLE `tbl_nros_cuentas_vendedores`
  ADD CONSTRAINT `tbl_nros_cuentas_vendedores_ibfk_1` FOREIGN KEY (`fkvendedor`) REFERENCES `tbl_vendedores` (`idvendedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  ADD CONSTRAINT `tbl_pedidos_ibfk_1` FOREIGN KEY (`fkproveedor`) REFERENCES `tbl_proveedores` (`idproveedor`);

--
-- Filtros para la tabla `tbl_precios_productos`
--
ALTER TABLE `tbl_precios_productos`
  ADD CONSTRAINT `tbl_precios_productos_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_precios_ventas_vendedores`
--
ALTER TABLE `tbl_precios_ventas_vendedores`
  ADD CONSTRAINT `tbl_precios_ventas_vendedores_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_precios_ventas_vendedores_ibfk_2` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_regalias`
--
ALTER TABLE `tbl_regalias`
  ADD CONSTRAINT `tbl_regalias_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`),
  ADD CONSTRAINT `tbl_regalias_ibfk_2` FOREIGN KEY (`fkpedido`) REFERENCES `tbl_pedidos` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_ventas`
--
ALTER TABLE `tbl_ventas`
  ADD CONSTRAINT `tbl_ventas_ibfk_1` FOREIGN KEY (`fkcliente`) REFERENCES `tbl_clientes` (`idcliente`),
  ADD CONSTRAINT `tbl_ventas_ibfk_2` FOREIGN KEY (`fkvendedor`) REFERENCES `tbl_vendedores` (`idvendedor`);
