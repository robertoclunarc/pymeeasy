/*
MySQL Backup
Source Server Version: 5.5.8
Source Database: bdpymeasy
Date: 29/11/2020 22:09:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `tbl_abonos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_abonos`;
CREATE TABLE `tbl_abonos` (
  `idabono` int(11) NOT NULL AUTO_INCREMENT,
  `fkventa` int(11) NOT NULL,
  `monto` double(11,2) NOT NULL,
  `modalidad_pago` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` date NOT NULL,
  `nro_referencia` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_abono` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idabono`),
  KEY `fkventa` (`fkventa`),
  CONSTRAINT `tbl_abonos_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_auditorias`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_auditorias`;
CREATE TABLE `tbl_auditorias` (
  `idauditoria` int(11) NOT NULL AUTO_INCREMENT,
  `actividad` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `login` varchar(6) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`idauditoria`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_cierres_diarios_inv`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_cierres_diarios_inv`;
CREATE TABLE `tbl_cierres_diarios_inv` (
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
  KEY `fkproducto` (`fkproducto`),
  CONSTRAINT `tbl_cierres_diarios_inv_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_clientes`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_clientes`;
CREATE TABLE `tbl_clientes` (
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_cuentas_bancarias`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_cuentas_bancarias`;
CREATE TABLE `tbl_cuentas_bancarias` (
  `idcuenta_bnc` int(11) NOT NULL AUTO_INCREMENT,
  `nro_cuenta` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `banco` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idcuenta_bnc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_cuentas_cobrar`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_cuentas_cobrar`;
CREATE TABLE `tbl_cuentas_cobrar` (
  `idcuenta_cob` int(11) NOT NULL AUTO_INCREMENT,
  `fkventa` int(11) NOT NULL,
  `monto_total` double(11,2) NOT NULL,
  `monto_debe` double(11,2) NOT NULL,
  `monto_haber` double(11,2) DEFAULT '0.00',
  `estatus` varchar(10) COLLATE utf8_spanish2_ci DEFAULT 'PENDIENTE',
  PRIMARY KEY (`idcuenta_cob`),
  KEY `fkventa` (`fkventa`),
  CONSTRAINT `tbl_cuentas_cobrar_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_cuentas_pagar`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_cuentas_pagar`;
CREATE TABLE `tbl_cuentas_pagar` (
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_detalles_notas_creditos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_detalles_notas_creditos`;
CREATE TABLE `tbl_detalles_notas_creditos` (
  `fknota` int(11) NOT NULL,
  `fkproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `medida` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` double(11,2) NOT NULL,
  `precio_total` double(11,2) NOT NULL,
  `estatus_nota` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  KEY `fkproducto` (`fkproducto`),
  KEY `fknota` (`fknota`) USING BTREE,
  CONSTRAINT `tbl_detalles_notas_creditos_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`),
  CONSTRAINT `tbl_detalles_notas_creditos_ibfk_2` FOREIGN KEY (`fknota`) REFERENCES `tbl_notas_creditos` (`idnota`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_detalles_pedidos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_detalles_pedidos`;
CREATE TABLE `tbl_detalles_pedidos` (
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
  KEY `fkproducto` (`fkproducto`),
  CONSTRAINT `tbl_detalles_pedidos_ibfk_1` FOREIGN KEY (`fkpedido`) REFERENCES `tbl_pedidos` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_detalles_pedidos_ibfk_2` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_detalles_ventas`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_detalles_ventas`;
CREATE TABLE `tbl_detalles_ventas` (
  `fkventa` int(11) NOT NULL,
  `fkproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `medida` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` double(11,2) NOT NULL,
  `precio_total` double(11,2) NOT NULL,
  `costo_prod` double(11,2) DEFAULT NULL,
  `precio_factura` double(11,2) DEFAULT NULL,
  KEY `fkproducto` (`fkproducto`),
  KEY `fkventa` (`fkventa`),
  CONSTRAINT `tbl_detalles_ventas_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`),
  CONSTRAINT `tbl_detalles_ventas_ibfk_2` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_divisas`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_divisas`;
CREATE TABLE `tbl_divisas` (
  `id_divisa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_divisa` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `simbologia` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id_divisa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_fletes`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_fletes`;
CREATE TABLE `tbl_fletes` (
  `idflete` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `transportista` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fkpedido` int(11) NOT NULL,
  `costo` double(11,2) NOT NULL,
  `estatus_flete` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idflete`),
  KEY `fkpedido` (`fkpedido`),
  CONSTRAINT `tbl_fletes_ibfk_1` FOREIGN KEY (`fkpedido`) REFERENCES `tbl_pedidos` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_modalidades_pagos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_modalidades_pagos`;
CREATE TABLE `tbl_modalidades_pagos` (
  `idmodalidad` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idmodalidad`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_notas_creditos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_notas_creditos`;
CREATE TABLE `tbl_notas_creditos` (
  `idnota` int(11) NOT NULL AUTO_INCREMENT,
  `fkvendedor` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fkcliente` int(11) NOT NULL,
  `iva` double(11,2) NOT NULL,
  `subtotal` double(11,2) NOT NULL,
  `total_neto` double(11,2) NOT NULL,
  `excento` double(11,2) NOT NULL,
  `estatus_nota` varchar(10) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'pendiente, pagada, anulada',
  `divisa` double(11,2) NOT NULL,
  `totaliva` double(11,2) NOT NULL,
  PRIMARY KEY (`idnota`),
  KEY `fkcliente` (`fkcliente`),
  KEY `fkvendedor` (`fkvendedor`),
  CONSTRAINT `tbl_notas_creditos_ibfk_1` FOREIGN KEY (`fkcliente`) REFERENCES `tbl_clientes` (`idcliente`),
  CONSTRAINT `tbl_notas_creditos_ibfk_2` FOREIGN KEY (`fkvendedor`) REFERENCES `tbl_vendedores` (`idvendedor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_nros_cuentas_vendedores`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_nros_cuentas_vendedores`;
CREATE TABLE `tbl_nros_cuentas_vendedores` (
  `fkvendedor` int(11) NOT NULL,
  `banco` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `cuenta` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_cuenta` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `estatus_cta_vend` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  KEY `fkvendedor` (`fkvendedor`),
  CONSTRAINT `tbl_nros_cuentas_vendedores_ibfk_1` FOREIGN KEY (`fkvendedor`) REFERENCES `tbl_vendedores` (`idvendedor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_pagos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_pagos`;
CREATE TABLE `tbl_pagos` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_parametros`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_parametros`;
CREATE TABLE `tbl_parametros` (
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

-- ----------------------------
--  Table structure for `tbl_pedidos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_pedidos`;
CREATE TABLE `tbl_pedidos` (
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
  KEY `fkproveedor` (`fkproveedor`),
  CONSTRAINT `tbl_pedidos_ibfk_1` FOREIGN KEY (`fkproveedor`) REFERENCES `tbl_proveedores` (`idproveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_precios_divisa`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_precios_divisa`;
CREATE TABLE `tbl_precios_divisa` (
  `idprecio_divisa` int(11) NOT NULL AUTO_INCREMENT,
  `fechahora_actulizacion` datetime NOT NULL,
  `monto_actualizacion` double(11,2) NOT NULL,
  `fk_divisa` int(11) NOT NULL,
  PRIMARY KEY (`idprecio_divisa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_precios_productos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_precios_productos`;
CREATE TABLE `tbl_precios_productos` (
  `fkproducto` int(11) NOT NULL,
  `descripcion_precio` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_unitario` double(11,2) DEFAULT NULL,
  `precio_blt` double(11,2) DEFAULT NULL,
  `estatus_precio` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  `fecha_ultim_actualizacion` date NOT NULL,
  `moneda` int(11) DEFAULT NULL,
  KEY `fkproducto` (`fkproducto`),
  CONSTRAINT `tbl_precios_productos_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_precios_ventas_vendedores`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_precios_ventas_vendedores`;
CREATE TABLE `tbl_precios_ventas_vendedores` (
  `fkventa` int(11) NOT NULL,
  `fkproducto` int(11) NOT NULL,
  `precio_vendedor` double(11,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` double(11,2) NOT NULL,
  KEY `fkventa` (`fkventa`),
  KEY `fkproducto` (`fkproducto`),
  CONSTRAINT `tbl_precios_ventas_vendedores_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_ventas` (`idventa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_precios_ventas_vendedores_ibfk_2` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_productos`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_productos`;
CREATE TABLE `tbl_productos` (
  `idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_prod` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `marca` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad_unitaria` int(11) NOT NULL,
  `cantidad_blt` int(11) NOT NULL,
  `cant_unidades_en_blt` int(11) NOT NULL,
  `costo` double(11,2) DEFAULT NULL,
  `estatus_prod` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_proveedores`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_proveedores`;
CREATE TABLE `tbl_proveedores` (
  `idproveedor` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `razon_social` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `representante_legal` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_prov` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idproveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_regalias`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_regalias`;
CREATE TABLE `tbl_regalias` (
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
  KEY `fkpedido` (`fkpedido`),
  CONSTRAINT `tbl_regalias_ibfk_1` FOREIGN KEY (`fkproducto`) REFERENCES `tbl_productos` (`idproducto`),
  CONSTRAINT `tbl_regalias_ibfk_2` FOREIGN KEY (`fkpedido`) REFERENCES `tbl_pedidos` (`idpedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_usuarios`;
CREATE TABLE `tbl_usuarios` (
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

-- ----------------------------
--  Table structure for `tbl_vendedores`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_vendedores`;
CREATE TABLE `tbl_vendedores` (
  `idvendedor` int(11) NOT NULL AUTO_INCREMENT,
  `rif` varchar(12) COLLATE utf8_spanish2_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estatus_vend` varchar(10) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`idvendedor`),
  UNIQUE KEY `rif` (`rif`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  Table structure for `tbl_ventas`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_ventas`;
CREATE TABLE `tbl_ventas` (
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
  KEY `fkvendedor` (`fkvendedor`),
  CONSTRAINT `tbl_ventas_ibfk_1` FOREIGN KEY (`fkcliente`) REFERENCES `tbl_clientes` (`idcliente`),
  CONSTRAINT `tbl_ventas_ibfk_2` FOREIGN KEY (`fkvendedor`) REFERENCES `tbl_vendedores` (`idvendedor`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- ----------------------------
--  View definition for `vw_abonos`
-- ----------------------------
DROP VIEW IF EXISTS `vw_abonos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_abonos` AS select `tbl_abonos`.`idabono` AS `idabono`,`tbl_abonos`.`fkventa` AS `fkventa`,`tbl_abonos`.`monto` AS `monto`,`tbl_abonos`.`modalidad_pago` AS `modalidad_pago`,`tbl_abonos`.`fecha` AS `fecha_abono`,`tbl_abonos`.`nro_referencia` AS `nro_referencia`,`tbl_ventas`.`fkvendedor` AS `fkvendedor`,date_format(`tbl_ventas`.`fecha`,'%Y-%m-%d') AS `fecha_venta`,`tbl_ventas`.`fkcliente` AS `fkcliente`,`tbl_ventas`.`total_neto` AS `total_neto`,`tbl_ventas`.`estatus_venta` AS `estatus_venta`,concat(`tbl_vendedores`.`nombres`,' Rif.',`tbl_vendedores`.`rif`) AS `vendedor`,concat(`tbl_clientes`.`razon_social`,' Rif.',`tbl_clientes`.`rif`) AS `nombre_cliente` from (((`tbl_abonos` join `tbl_ventas` on((`tbl_abonos`.`fkventa` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) order by `tbl_abonos`.`fecha` desc ;

-- ----------------------------
--  View definition for `vw_cierre_inventario`
-- ----------------------------
DROP VIEW IF EXISTS `vw_cierre_inventario`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `vw_cierre_inventario` AS SELECT
tbl_cierres_diarios_inv.idcierre,
tbl_cierres_diarios_inv.fkproducto,
tbl_productos.descripcion_prod,
tbl_productos.marca,
tbl_cierres_diarios_inv.cantidad_unidades,
tbl_cierres_diarios_inv.cantidad_blt,
tbl_cierres_diarios_inv.cant_unidades_en_blt,
tbl_cierres_diarios_inv.observacion,
tbl_cierres_diarios_inv.responsable,
tbl_cierres_diarios_inv.estatus_cierre,
tbl_cierres_diarios_inv.fecha
FROM
tbl_productos
INNER JOIN tbl_cierres_diarios_inv ON tbl_cierres_diarios_inv.fkproducto = tbl_productos.idproducto
WHERE
tbl_cierres_diarios_inv.estatus_cierre = 'CERRADO' ;

-- ----------------------------
--  View definition for `vw_cuentas_cobrar`
-- ----------------------------
DROP VIEW IF EXISTS `vw_cuentas_cobrar`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_cuentas_cobrar` AS select `tbl_cuentas_cobrar`.`idcuenta_cob` AS `idcuenta_cob`,`tbl_cuentas_cobrar`.`fkventa` AS `fkventa`,`tbl_cuentas_cobrar`.`monto_total` AS `monto_total`,`tbl_cuentas_cobrar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_cobrar`.`monto_haber` AS `monto_haber`,`tbl_cuentas_cobrar`.`estatus` AS `estatus`,`tbl_ventas`.`fkvendedor` AS `fkvendedor`,date_format(`tbl_ventas`.`fecha`,'%Y-%m-%d') AS `fecha`,`tbl_ventas`.`estatus_venta` AS `estatus_venta`,`tbl_ventas`.`fkcliente` AS `fkcliente`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `vendedor`,concat(`tbl_clientes`.`razon_social`,' ',`tbl_clientes`.`rif`) AS `cliente` from (((`tbl_cuentas_cobrar` join `tbl_ventas` on((`tbl_cuentas_cobrar`.`fkventa` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) order by `tbl_ventas`.`fecha` desc ;

-- ----------------------------
--  View definition for `vw_cuentas_pagar`
-- ----------------------------
DROP VIEW IF EXISTS `vw_cuentas_pagar`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_cuentas_pagar` AS select `tbl_cuentas_pagar`.`idcuenta_pag` AS `idcuenta_pag`,`tbl_cuentas_pagar`.`fkdeuda` AS `fkdeuda`,`tbl_cuentas_pagar`.`tipo_deuda` AS `tipo_deuda`,`tbl_cuentas_pagar`.`tabla` AS `tabla`,`tbl_cuentas_pagar`.`monto_total` AS `monto_total`,`tbl_cuentas_pagar`.`monto_pagar` AS `monto_pagar`,`tbl_cuentas_pagar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_pagar`.`estatus_ctapag` AS `estatus_ctapag`,`tbl_cuentas_pagar`.`fecha` AS `fecha`,`tbl_pedidos`.`fkproveedor` AS `fk`,concat(`tbl_proveedores`.`razon_social`,' ',`tbl_proveedores`.`rif`) AS `descripcion` from ((`tbl_cuentas_pagar` join `tbl_pedidos` on((`tbl_cuentas_pagar`.`fkdeuda` = `tbl_pedidos`.`idpedido`))) join `tbl_proveedores` on((`tbl_pedidos`.`fkproveedor` = `tbl_proveedores`.`idproveedor`))) where (`tbl_cuentas_pagar`.`tipo_deuda` = 'PEDIDO') union select `tbl_cuentas_pagar`.`idcuenta_pag` AS `idcuenta_pag`,`tbl_cuentas_pagar`.`fkdeuda` AS `fkdeuda`,`tbl_cuentas_pagar`.`tipo_deuda` AS `tipo_deuda`,`tbl_cuentas_pagar`.`tabla` AS `tabla`,`tbl_cuentas_pagar`.`monto_total` AS `monto_total`,`tbl_cuentas_pagar`.`monto_pagar` AS `monto_pagar`,`tbl_cuentas_pagar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_pagar`.`estatus_ctapag` AS `estatus_ctapag`,`tbl_cuentas_pagar`.`fecha` AS `fecha`,`tbl_fletes`.`fkpedido` AS `fk`,`tbl_fletes`.`transportista` AS `descripcion` from (`tbl_cuentas_pagar` join `tbl_fletes` on((`tbl_cuentas_pagar`.`fkdeuda` = `tbl_fletes`.`idflete`))) where (`tbl_cuentas_pagar`.`tipo_deuda` = 'FLETE') union select `tbl_cuentas_pagar`.`idcuenta_pag` AS `idcuenta_pag`,`tbl_cuentas_pagar`.`fkdeuda` AS `fkdeuda`,`tbl_cuentas_pagar`.`tipo_deuda` AS `tipo_deuda`,`tbl_cuentas_pagar`.`tabla` AS `tabla`,`tbl_cuentas_pagar`.`monto_total` AS `monto_total`,`tbl_cuentas_pagar`.`monto_pagar` AS `monto_pagar`,`tbl_cuentas_pagar`.`monto_debe` AS `monto_debe`,`tbl_cuentas_pagar`.`estatus_ctapag` AS `estatus_ctapag`,`tbl_cuentas_pagar`.`fecha` AS `fecha`,`tbl_ventas`.`fkvendedor` AS `fk`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `descripcion` from ((`tbl_cuentas_pagar` join `tbl_ventas` on((`tbl_cuentas_pagar`.`fkdeuda` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) where (`tbl_cuentas_pagar`.`tipo_deuda` = 'VENDEDOR') ;

-- ----------------------------
--  View definition for `vw_formas_pagos`
-- ----------------------------
DROP VIEW IF EXISTS `vw_formas_pagos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_formas_pagos` AS select `tbl_pagos`.`idpago` AS `idpago`,`tbl_pagos`.`fkdeuda` AS `fkdeuda`,`tbl_pagos`.`tipo_pago` AS `tipo_pago`,`tbl_pagos`.`monto` AS `monto`,`tbl_pagos`.`modalidad_pago` AS `modalidad_pago`,`tbl_pagos`.`banco` AS `banco`,`tbl_pagos`.`fecha` AS `fecha`,`tbl_pagos`.`nro_referencia` AS `nro_referencia`,`tbl_pagos`.`estatus_pago` AS `estatus_pago`,concat(`tbl_proveedores`.`razon_social`,' ',`tbl_proveedores`.`rif`) AS `descripcion`,`tbl_pedidos`.`fkproveedor` AS `fkacreedor` from ((`tbl_pagos` join `tbl_pedidos` on((`tbl_pagos`.`fkdeuda` = `tbl_pedidos`.`idpedido`))) join `tbl_proveedores` on((`tbl_pedidos`.`fkproveedor` = `tbl_proveedores`.`idproveedor`))) where (`tbl_pagos`.`tipo_pago` = 'PEDIDO') union select `tbl_pagos`.`idpago` AS `idpago`,`tbl_pagos`.`fkdeuda` AS `fkdeuda`,`tbl_pagos`.`tipo_pago` AS `tipo_pago`,`tbl_pagos`.`monto` AS `monto`,`tbl_pagos`.`modalidad_pago` AS `modalidad_pago`,`tbl_pagos`.`banco` AS `banco`,`tbl_pagos`.`fecha` AS `fecha`,`tbl_pagos`.`nro_referencia` AS `nro_referencia`,`tbl_pagos`.`estatus_pago` AS `estatus_pago`,`tbl_fletes`.`transportista` AS `descripcion`,`tbl_fletes`.`fkpedido` AS `fkacreedor` from (`tbl_pagos` join `tbl_fletes` on((`tbl_pagos`.`fkdeuda` = `tbl_fletes`.`idflete`))) where (`tbl_pagos`.`tipo_pago` = 'FLETE') union select `tbl_pagos`.`idpago` AS `idpago`,`tbl_pagos`.`fkdeuda` AS `fkdeuda`,`tbl_pagos`.`tipo_pago` AS `tipo_pago`,`tbl_pagos`.`monto` AS `monto`,`tbl_pagos`.`modalidad_pago` AS `modalidad_pago`,`tbl_pagos`.`banco` AS `banco`,`tbl_pagos`.`fecha` AS `fecha`,`tbl_pagos`.`nro_referencia` AS `nro_referencia`,`tbl_pagos`.`estatus_pago` AS `estatus_pago`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `descripcion`,`tbl_ventas`.`fkvendedor` AS `fkacreedor` from ((`tbl_pagos` join `tbl_ventas` on((`tbl_pagos`.`fkdeuda` = `tbl_ventas`.`idventa`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) where (`tbl_pagos`.`tipo_pago` = 'VENDEDOR') ;

-- ----------------------------
--  View definition for `vw_graficos_1`
-- ----------------------------
DROP VIEW IF EXISTS `vw_graficos_1`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_graficos_1` AS select date_format(`tbl_cuentas_pagar`.`fecha`,'%d-%m-%y') AS `label`,sum(`tbl_cuentas_pagar`.`monto_pagar`) AS `dato` from `tbl_cuentas_pagar` where ((cast(`tbl_cuentas_pagar`.`fecha` as date) >= cast((now() - interval 7 day) as date)) and (`tbl_cuentas_pagar`.`estatus_ctapag` = 'POR PAGAR')) group by date_format(`tbl_cuentas_pagar`.`fecha`,'%d-%m-%y') order by `tbl_cuentas_pagar`.`fecha` ;

-- ----------------------------
--  View definition for `vw_graficos_2`
-- ----------------------------
DROP VIEW IF EXISTS `vw_graficos_2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_graficos_2` AS select date_format(`tbl_ventas`.`fecha`,'%d-%m-%y') AS `label`,sum(`tbl_cuentas_cobrar`.`monto_debe`) AS `dato` from (`tbl_cuentas_cobrar` join `tbl_ventas` on((`tbl_cuentas_cobrar`.`fkventa` = `tbl_ventas`.`idventa`))) where ((cast(`tbl_ventas`.`fecha` as date) >= cast((now() - interval 7 day) as date)) and (`tbl_cuentas_cobrar`.`estatus` = 'PENDIENTE')) group by date_format(`tbl_ventas`.`fecha`,'%d-%m-%y') order by `tbl_ventas`.`fecha` ;

-- ----------------------------
--  View definition for `vw_precios_productos_blt`
-- ----------------------------
DROP VIEW IF EXISTS `vw_precios_productos_blt`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_precios_productos_blt` AS SELECT
	concat(
		`tbl_precios_productos`.`descripcion_precio`,
		' ',
		CONVERT (
			`tbl_precios_productos`.`precio_blt` USING utf8
		), '(',tbl_divisas.simbologia,')'
	) AS `descripcion_precio`,
	`tbl_precios_productos`.`precio_blt` AS `precio`,
	`tbl_precios_productos`.`fkproducto` AS `fkproducto`
FROM
	`tbl_precios_productos` , tbl_divisas
WHERE
	(
	  tbl_divisas.id_divisa=tbl_precios_productos.moneda and`tbl_precios_productos`.`estatus_precio` = 'ACTIVO'
	)
ORDER BY
	`tbl_precios_productos`.`descripcion_precio` ;

-- ----------------------------
--  View definition for `vw_precios_productos_unit`
-- ----------------------------
DROP VIEW IF EXISTS `vw_precios_productos_unit`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_precios_productos_unit` AS SELECT
	concat(
		`tbl_precios_productos`.`descripcion_precio`,
		' ',
		CONVERT (
			`tbl_precios_productos`.`precio_unitario` USING utf8
		), '(',tbl_divisas.simbologia,')'
	) AS `descripcion_precio`,
	`tbl_precios_productos`.`precio_unitario` AS `precio`,
	`tbl_precios_productos`.`fkproducto` AS `fkproducto`
FROM
	`tbl_precios_productos`, tbl_divisas
WHERE
	(
		tbl_divisas.id_divisa=tbl_precios_productos.moneda and `tbl_precios_productos`.`estatus_precio` = 'ACTIVO'
	)
ORDER BY
	`tbl_precios_productos`.`descripcion_precio` ;

-- ----------------------------
--  View definition for `vw_precio_actual_divisa`
-- ----------------------------
DROP VIEW IF EXISTS `vw_precio_actual_divisa`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_precio_actual_divisa` AS select `tbl_precios_divisa`.`idprecio_divisa` AS `idprecio_divisa`,`tbl_precios_divisa`.`fechahora_actulizacion` AS `fechahora_actulizacion`,`tbl_precios_divisa`.`monto_actualizacion` AS `monto_actualizacion` from `tbl_precios_divisa` where (`tbl_precios_divisa`.`fechahora_actulizacion` = (select max(`tbl_precios_divisa`.`fechahora_actulizacion`) from `tbl_precios_divisa`)) ;

-- ----------------------------
--  View definition for `vw_productos`
-- ----------------------------
DROP VIEW IF EXISTS `vw_productos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_productos` AS select `tbl_productos`.`idproducto` AS `idproducto`,concat(`tbl_productos`.`descripcion_prod`,' ',`tbl_productos`.`marca`) AS `descripcion`,`tbl_productos`.`estatus_prod` AS `estatus_prod` from `tbl_productos` ;

-- ----------------------------
--  View definition for `vw_productos_pedidos`
-- ----------------------------
DROP VIEW IF EXISTS `vw_productos_pedidos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_productos_pedidos` AS select `tbl_detalles_pedidos`.`fkproducto` AS `fkproducto`,`tbl_detalles_pedidos`.`fkpedido` AS `fkpedido`,concat(`tbl_productos`.`descripcion_prod`,' ',`tbl_productos`.`marca`) AS `producto` from (`tbl_detalles_pedidos` join `tbl_productos` on((`tbl_detalles_pedidos`.`fkproducto` = `tbl_productos`.`idproducto`))) order by `tbl_productos`.`descripcion_prod` ;

-- ----------------------------
--  View definition for `vw_resumen_general`
-- ----------------------------
DROP VIEW IF EXISTS `vw_resumen_general`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_resumen_general` AS select `tbl_ventas`.`idventa` AS `idventa`,`tbl_ventas`.`fkvendedor` AS `fkvendedor`,`tbl_ventas`.`fecha` AS `fecha`,`tbl_ventas`.`fkcliente` AS `fkcliente`,`tbl_ventas`.`iva` AS `iva`,`tbl_ventas`.`subtotal` AS `subtotal`,`tbl_ventas`.`estatus_venta` AS `estatus_venta`,concat(`tbl_clientes`.`razon_social`,' ',`tbl_clientes`.`rif`) AS `cliente`,concat(`tbl_vendedores`.`nombres`,' ',`tbl_vendedores`.`rif`) AS `vendedor`,`tbl_detalles_ventas`.`fkproducto` AS `fkproducto`,concat(convert(`tbl_detalles_ventas`.`cantidad` using utf8),`tbl_detalles_ventas`.`medida`) AS `cantidad`,`tbl_detalles_ventas`.`precio` AS `precio`,`tbl_detalles_ventas`.`precio_total` AS `monto_venta`,concat(`tbl_productos`.`descripcion_prod`,' ',`tbl_productos`.`marca`) AS `producto`,`tbl_abonos`.`monto` AS `monto`,`tbl_abonos`.`modalidad_pago` AS `modalidad_pago`,`tbl_abonos`.`fecha` AS `fecha_abono`,`tbl_abonos`.`nro_referencia` AS `nro_referencia`,`tbl_precios_ventas_vendedores`.`precio_vendedor` AS `precio_vendedor`,`tbl_precios_ventas_vendedores`.`total` AS `total_venta_vendedor`,`tbl_detalles_ventas`.`precio_total` AS `precio_venta`,(`tbl_precios_ventas_vendedores`.`total` - `tbl_detalles_ventas`.`precio_total`) AS `pago_vendedor`,(`tbl_detalles_ventas`.`precio_total` - (`tbl_detalles_ventas`.`costo_prod` * `tbl_detalles_ventas`.`cantidad`)) AS `ganancia` from ((((((`tbl_ventas` join `tbl_detalles_ventas` on((`tbl_detalles_ventas`.`fkventa` = `tbl_ventas`.`idventa`))) join `tbl_productos` on((`tbl_detalles_ventas`.`fkproducto` = `tbl_productos`.`idproducto`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_precios_ventas_vendedores` on(((`tbl_precios_ventas_vendedores`.`fkventa` = `tbl_detalles_ventas`.`fkventa`) and (`tbl_detalles_ventas`.`fkproducto` = `tbl_precios_ventas_vendedores`.`fkproducto`)))) left join `tbl_abonos` on((`tbl_abonos`.`fkventa` = `tbl_ventas`.`idventa`))) order by `tbl_ventas`.`fecha` desc ;

-- ----------------------------
--  View definition for `vw_vendedores_ctaspagar`
-- ----------------------------
DROP VIEW IF EXISTS `vw_vendedores_ctaspagar`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_vendedores_ctaspagar` AS select `vw_cuentas_pagar`.`fk` AS `fk`,`vw_cuentas_pagar`.`descripcion` AS `descripcion` from `vw_cuentas_pagar` where ((`vw_cuentas_pagar`.`estatus_ctapag` = 'POR PAGAR') and (`vw_cuentas_pagar`.`tipo_deuda` = 'VENDEDOR')) group by `vw_cuentas_pagar`.`fk`,`vw_cuentas_pagar`.`descripcion` ;

-- ----------------------------
--  View definition for `vw_ventas`
-- ----------------------------
DROP VIEW IF EXISTS `vw_ventas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `vw_ventas` AS select `tbl_ventas`.`idventa` AS `idventa`,`tbl_vendedores`.`idvendedor` AS `idvendedor`,`tbl_vendedores`.`rif` AS `rif_vendedor`,`tbl_vendedores`.`nombres` AS `nombres`,`tbl_clientes`.`idcliente` AS `idcliente`,`tbl_clientes`.`rif` AS `rif_cliente`,`tbl_clientes`.`razon_social` AS `razon_social`,`tbl_ventas`.`fecha` AS `fecha`,`tbl_ventas`.`subtotal` AS `subtotal`,`tbl_ventas`.`total_neto` AS `total_neto`,`tbl_ventas`.`excento` AS `excento`,`tbl_ventas`.`estatus_venta` AS `estatus_venta` from ((`tbl_ventas` join `tbl_vendedores` on((`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`))) join `tbl_clientes` on((`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`))) order by `tbl_ventas`.`fecha` desc ;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `tbl_abonos` VALUES ('5','19','121200.00','CHEQUE','2018-10-21','565656565','PAGADO');
INSERT INTO `tbl_auditorias` VALUES ('197','Nuevo inicio de sesion','rlunar','2018-10-20 19:16:23'), ('198','Registro de producto: ARROZ BLANCO','rlunar','2018-10-20 19:34:47'), ('199','Registro de producto: PASTA LARGA','rlunar','2018-10-20 19:35:15'), ('200','Registro de Nuevo Proveedor: HERMANOS MACANA','rlunar','2018-10-20 19:36:01'), ('201','Registro de Pedido: 16','rlunar','2018-10-20 19:37:35'), ('202','Actualizacion de Pedido: 16','rlunar','2018-10-20 19:38:49'), ('203','Pago de Pedido: 16','rlunar','2018-10-20 19:58:52'), ('204','Registro de Nuevo Vendedor: ROBERTO','rlunar','2018-10-20 20:01:33'), ('205','Registro de Precio Producto ID: 6.- Bs.3000','rlunar','2018-10-20 20:03:47'), ('206','Registro de Precio Producto ID: 6.- Bs.2600','rlunar','2018-10-20 20:03:47'), ('207','Registro de Precio Producto ID: 7.- Bs.6000','rlunar','2018-10-20 20:04:37'), ('208','Registro de Precio Producto ID: 7.- Bs.3900','rlunar','2018-10-20 20:04:37'), ('209','Registro de Venta: 18','rlunar','2018-10-20 20:06:27'), ('210','Registro de Venta: 19','rlunar','2018-10-20 20:12:21'), ('211','Nuevo inicio de sesion','rlunar','2018-10-21 16:07:34'), ('212','Nuevo inicio de sesion','rlunar','2020-11-10 13:16:02'), ('213','Actualizacion de Datos de Empresa','rlunar','2020-11-10 14:04:40'), ('214','Actualizacion del registro Vendedor: --','rlunar','2020-11-10 14:05:41'), ('215','Actualizacion del registro Vendedor: N/A','rlunar','2020-11-10 14:06:09'), ('216','Nuevo inicio de sesion','rlunar','2020-11-22 10:37:16'), ('217','Registro de Nuevo Vendedor: N/A','rlunar','2020-11-22 13:54:43'), ('218','Cierre de sesion','rlunar','2020-11-22 14:03:10'), ('219','Nuevo inicio de sesion','rlunar','2020-11-22 14:03:17'), ('220','Nuevo inicio de sesion','rlunar','2020-11-24 16:45:13'), ('221','Nuevo inicio de sesion','rlunar','2020-11-24 16:45:24'), ('222','Registro de Precio Producto ID: 6.- Bs.15','rlunar','2020-11-24 18:04:41'), ('223','Nuevo inicio de sesion','rlunar','2020-11-25 17:43:21'), ('224','Nuevo inicio de sesion','rlunar','2020-11-29 08:09:16'), ('225','Registro de Nota de Credito: 1','rlunar','2020-11-29 08:18:34'), ('226','Registro de Nota de Credito: 2','rlunar','2020-11-29 15:37:40'), ('227','Registro de Nota de Credito: 3','rlunar','2020-11-29 22:00:40');
INSERT INTO `tbl_clientes` VALUES ('2','V12003177','VICTOR SANVICENTE','VICTOR SANVICENTE','','','','ACTIVO'), ('3','COMERCIALPER','COMERCIAL PEREZ','COMERCIAL PEREZ','','','','ACTIVO'), ('4','J306364234','COMERCIAL EL ENCANTO','COMERCIAL EL ENCANTO','','','','ACTIVO'), ('5','J410151927','INVERSIONES AMISHA EXPRESS','INVERSIONES AMISHA EXPRESS','','','','ACTIVO'), ('6','J41108384-4','INVERSIONES J Y Y RONDON ','INVERSIONES J Y Y RONDON ','','','','ACTIVO'), ('7','J40566966','INVERSIONES VAIVEN','INVERSIONES VAIVEN','','','','ACTIVO'), ('8','J296684073','INVERSIONES PALMA REAL','INVERSIONES PALMA REAL','','','','ACTIVO'), ('9','J402474067','CARNICERIA EL TORO DE ORO','CARNICERIA EL TORO DE ORO',NULL,NULL,NULL,'ACTIVO'), ('10','J307470615','EXQUISITESES PANADERIA SALTO ANGEL','EXQUISITESES PANADERIA SALTO ANGEL',NULL,NULL,NULL,'ACTIVO'), ('11','J407844016','INVERSIONES ALLOPY','INVERSIONES ALLOPY',NULL,NULL,NULL,'ACTIVO'), ('12','J407530763','DALIDY FESTEJOS Y SUMINISTROS C.A','DALIDY FESTEJOS Y SUMINISTROS C.A',NULL,NULL,NULL,'ACTIVO'), ('13','V113363025','INVERSIONES JOSE MANUEL FIGUEROA URETRA','INVERSIONES JOSE MANUEL FIGUEROA URETRA',NULL,NULL,NULL,'ACTIVO'), ('14','J298483229','CHARCUTERIA GL, C.A','CHARCUTERIA GL, C.A',NULL,NULL,NULL,'ACTIVO'), ('15','J402862768','INVERSIONES MARCANO PEREZ','INVERSIONES MARCANO PEREZ',NULL,NULL,NULL,'ACTIVO'), ('16','V110081185','OVI, C.A','OVI, C.A',NULL,NULL,NULL,'ACTIVO'), ('17','J403117152','CHARCUTERIA PIÃ‘A','CHARCUTERIA PIÃ‘A','','','','ACTIVO'), ('18','J405873922','INVERSIONES Y SERVICIOS YELI','INVERSIONES Y SERVICIOS YELI',NULL,NULL,NULL,'ACTIVO'), ('19','J409707555','SUPERFERIA LA TOMATINA','SUPERFERIA LA TOMATINA',NULL,NULL,NULL,'ACTIVO'), ('20','J409238652','RYGYN BODEGON','RYGYN BODEGON',NULL,NULL,NULL,'ACTIVO'), ('21','J309655507','PANADERIA Y PASTELERIA ANA NELLY, C.A','PANADERIA Y PASTELERIA ANA NELLY, C.A',NULL,NULL,NULL,'ACTIVO'), ('38','J403084165','BODEGON SAN ANTONIO','BODEGON SAN ANTONIO',NULL,NULL,NULL,'ACTIVO'), ('39','J407510320','BODEGON PICHON','BODEGON PICHON','','','','INACTIVO'), ('40','J312148578','CHARCUTERIA LOS HERMANOS','CHARCUTERIA LOS HERMANOS',NULL,NULL,NULL,'ACTIVO'), ('41','V121261908','CHARCUTERIA KASUPO AMADO DAVID','CHARCUTERIA KASUPO AMADO DAVID',NULL,NULL,NULL,'ACTIVO'), ('42','J407769308','INVERSIONES DAVI Y WILLIAN','INVERSIONES DAVI Y WILLIAN',NULL,NULL,NULL,'ACTIVO'), ('43','J405655933','INVERSIONES LA POPULAR','INVERSIONES LA POPULAR',NULL,NULL,NULL,'ACTIVO'), ('44','J408129035','CHARCUTERIA EL GRAN YO SOY','CHARCUTERIA EL GRAN YO SOY',NULL,NULL,NULL,'ACTIVO'), ('45','V216769798','JULIE DEBRA','JULIE DEBRA',NULL,NULL,NULL,'ACTIVO'), ('46','J407742850','MULTIVARIEDADES MIS DOS ESTRELLAS','MULTIVARIEDADES MIS DOS ESTRELLAS',NULL,NULL,NULL,'ACTIVO'), ('47','J408729652','INVERSIONES MI PROVISION','INVERSIONES MI PROVISION',NULL,NULL,NULL,'ACTIVO'), ('48','J40599830','INVERSIONES LA BEDICION DE DIOS','INVERSIONES LA BEDICION DE DIOS',NULL,NULL,NULL,'ACTIVO'), ('49','KA VALLENILL','KA VALLENILLA','KA VALLENILLA',NULL,NULL,NULL,'ACTIVO'), ('50','OVI, C.A','OVI, C.A','OVI, C.A',NULL,NULL,NULL,'ACTIVO'), ('51','V113392157','LOS PANES DE ARELYS','LOS PANES DE ARELYS',NULL,NULL,NULL,'ACTIVO'), ('52','123456','prueba 2','prueba 2','prueba 2','prueba 2','prueba2@prueba.com','ACTIVO'), ('53','1234567','prueba 4','','NULL','prueba 4','prueba 4@prueba .cpm','ACTIVO');
INSERT INTO `tbl_cuentas_cobrar` VALUES ('6','18','102000.00','102000.00','0.00','PENDIENTE'), ('7','19','121200.00','0.00','121200.00','PAGADO');
INSERT INTO `tbl_cuentas_pagar` VALUES ('8','16','PEDIDO','tbl_pedidos','60000.00','55000.00','5000.00','POR PAGAR','2018-10-20'), ('9','18','VENDEDOR','tbl_precios_ventas_vendedores','22000.00','22000.00','0.00','POR PAGAR','2018-10-20'), ('10','19','VENDEDOR','tbl_precios_ventas_vendedores','34800.00','34800.00','0.00','POR PAGAR','2018-10-20');
INSERT INTO `tbl_detalles_notas_creditos` VALUES ('1','7','5','BLT.','3.00','15.00','ACTIVO'), ('1','6','2','BLT.','15.00','30.00','ACTIVO'), ('2','7','6','BLT.','3.00','18.00','ACTIVO'), ('2','6','15','BLT.','15.00','225.00','ACTIVO'), ('3','7','12','BLT.','3900.00','46800.00','ACTIVO');
INSERT INTO `tbl_detalles_pedidos` VALUES ('3','16','7','12','BLT.',NULL,'3000.00','36000.00','POR PAGAR'), ('4','16','6','12','BLT.',NULL,'2000.00','24000.00','POR PAGAR');
INSERT INTO `tbl_detalles_ventas` VALUES ('18','7','12','BLT.','6000.00','72000.00','3000.00','6500.00'), ('18','6','10','BLT.','3000.00','30000.00','2000.00','3500.00'), ('19','7','15','BLT.','6000.00','90000.00','3000.00','8500.00'), ('19','6','12','BLT.','2600.00','31200.00','2000.00','3500.00');
INSERT INTO `tbl_divisas` VALUES ('1','Dolar','$'), ('2','Bolivares','Bs.');
INSERT INTO `tbl_modalidades_pagos` VALUES ('1','--'), ('2','TRANSFERENCIA'), ('3','DEPOSITO'), ('4','EFECTIVO'), ('5','TDD'), ('6','TDC'), ('7','CHEQUE');
INSERT INTO `tbl_notas_creditos` VALUES ('1','3','2020-11-29 08:18:34','38','16.00','45.00','50.40','1.80','PENDIENTE','567295.42','7.20'), ('2','3','2020-11-29 15:37:40','4','0.00','243.00','243.00','0.00','PAGADA','567295.42','0.00'), ('3','3','2020-11-29 22:00:40','41','16.00','46800.00','54288.00','0.00','PAGADA','567295.42','7488.00');
INSERT INTO `tbl_pagos` VALUES ('3','16','PEDIDO','5000.00','CHEQUE','vene','2018-10-20','6y6y6','PAGADO');
INSERT INTO `tbl_parametros` VALUES ('16.00','INRECA','J-40959190-5','Unare 2','Guayana','Puerto Ordaz','Eglis Martinez','0414-','0.00');
INSERT INTO `tbl_pedidos` VALUES ('16','2018-10-20 19:37:35','3','','','2018-10-20','60000.00','0.00','60000.00','POR PAGAR');
INSERT INTO `tbl_precios_divisa` VALUES ('1','2020-11-10 09:00:01','554995.72','1'), ('2','2020-11-10 13:00:01','567295.42','1');
INSERT INTO `tbl_precios_productos` VALUES ('6','USUARA','125.00','3000.00','INACTIVO','2018-10-20','2'), ('6','Precio Regulado','108.33','2600.00','INACTIVO','2018-10-20','2'), ('7','USURA','250.00','6000.00','ACTIVO','2018-10-20','2'), ('7','Precio Regulado','162.50','3900.00','ACTIVO','2018-10-20','2'), ('7','Precio dolar','0.50','3.00','ACTIVO','2020-11-24','1'), ('6','Precio dollar','0.63','15.00','ACTIVO','2020-11-24','1');
INSERT INTO `tbl_precios_ventas_vendedores` VALUES ('18','7','4000.00','12','48000.00'), ('18','6','5000.00','10','50000.00'), ('19','7','3000.00','15','36000.00'), ('19','6','8000.00','12','120000.00');
INSERT INTO `tbl_productos` VALUES ('6','ARROZ BLANCO','MARY','240','110','24','2000.00','ACTIVO'), ('7','PASTA LARGA','RONCO','240','35','24','3000.00','ACTIVO');
INSERT INTO `tbl_proveedores` VALUES ('3','0000000','HERMANOS MACANA','PEDRO LOPEZ','','','','ACTIVO');
INSERT INTO `tbl_usuarios` VALUES ('rlunar','81dc9bdb52d04dc20036dbd8313ed055','Roberto Lunar','1','roberto_lunar@yahoo.','-','-','-','-','ACTIVO');
INSERT INTO `tbl_vendedores` VALUES ('3','0','N/A','--','--','ACTIVO');
INSERT INTO `tbl_ventas` VALUES ('18','3','2018-10-20 20:06:27','38','16','102000.00','102000.00','0.00','PENDIENTE'), ('19','3','2018-10-20 20:12:21','12','16','121200.00','121200.00','0.00','PAGADA');

-- ----------------------------
--  Trigger definition for `tbl_abonos`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_upd_cuenta_cobrar` AFTER INSERT ON `tbl_abonos` FOR EACH ROW BEGIN

update tbl_cuentas_cobrar set monto_haber=monto_haber+new.monto, monto_debe=monto_debe-new.monto where fkventa=new.fkventa;

update tbl_cuentas_cobrar set estatus='PAGADO' where monto_haber>=monto_total and fkventa=new.fkventa;

END
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_cierres_diarios_inv`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_actualizar_producto` AFTER INSERT ON `tbl_cierres_diarios_inv` FOR EACH ROW BEGIN 

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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_cuentas_cobrar`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_validar_venta` AFTER UPDATE ON `tbl_cuentas_cobrar` FOR EACH ROW begin
update tbl_ventas set estatus_venta='PAGADA' where total_neto<=new.monto_haber and idventa=new.fkventa and estatus_venta='PENDIENTE';
end
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_detalles_notas_creditos`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_restar_inventario_notacredito` AFTER INSERT ON `tbl_detalles_notas_creditos` FOR EACH ROW BEGIN 

IF NEW.estatus_nota = 'PAGADA' THEN
update tbl_productos set 
       cantidad_blt = cantidad_blt - NEW.cantidad,
       cantidad_unitaria = cantidad_unitaria - (cant_unidades_en_blt * NEW.cantidad)
where idproducto = NEW.fkproducto;

update tbl_productos set estatus_prod='ACTIVO' 
where idproducto = NEW.fkproducto and cantidad_unitaria >0;

update tbl_productos set estatus_prod='INACTIVO' 
where idproducto =NEW.fkproducto and cantidad_unitaria <=0;
END IF;

END
;;
DELIMITER ;
DELIMITER ;;
CREATE TRIGGER `trigger_devolver_inventario` AFTER UPDATE ON `tbl_detalles_notas_creditos` FOR EACH ROW BEGIN
DECLARE y INT DEFAULT 0;
DECLARE x DOUBLE (11,2) DEFAULT 0.0;
DECLARE z INT DEFAULT 0;
DECLARE cantuniblt INT DEFAULT 0;
IF NEW.estatus_nota='ANULADA' THEN
   IF NEW.medida='BLT.' THEN
        UPDATE tbl_productos SET cantidad_blt=cantidad_blt+NEW.cantidad, cantidad_unitaria=cantidad_unitaria+cant_unidades_en_blt WHERE idproducto=NEW.fkproducto;
   ELSE        
        SELECT cant_unidades_en_blt INTO cantuniblt FROM tbl_productos WHERE idproducto=NEW.fkproducto;
        IF NEW.cantidad >= cantuniblt THEN
               SET y:=NEW.cantidad / cantuniblt;
               SET x:=(NEW.cantidad / cantuniblt) + y;
               SET z:= x*cantuniblt;
               UPDATE tbl_productos SET cantidad_unitaria=cantidad_unitaria+NEW.cantidad, cantidad_blt=cantidad_blt+y  WHERE idproducto=NEW.fkproducto;
        ELSE
              UPDATE tbl_productos SET cantidad_unitaria=cantidad_unitaria+NEW.cantidad  WHERE idproducto=NEW.fkproducto;              
        END IF;        
   END IF;
END IF;

IF NEW.estatus_nota = 'PAGADA' THEN
update tbl_productos set 
       cantidad_blt = cantidad_blt - NEW.cantidad,
       cantidad_unitaria = cantidad_unitaria - (cant_unidades_en_blt * NEW.cantidad)
where idproducto = OLD.fkproducto;

update tbl_productos set estatus_prod='ACTIVO' 
where idproducto = OLD.fkproducto and cantidad_unitaria >0;

update tbl_productos set estatus_prod='INACTIVO' 
where idproducto = OLD.fkproducto and cantidad_unitaria <=0;
END IF;
END
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_detalles_pedidos`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `aumento_inventario` AFTER UPDATE ON `tbl_detalles_pedidos` FOR EACH ROW BEGIN 

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
;;
DELIMITER ;
DELIMITER ;;
CREATE TRIGGER `disminuir_inventario` AFTER DELETE ON `tbl_detalles_pedidos` FOR EACH ROW BEGIN 

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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_detalles_ventas`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_restar_inventario` AFTER INSERT ON `tbl_detalles_ventas` FOR EACH ROW BEGIN
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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_fletes`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_cta_pagar_flete` AFTER INSERT ON `tbl_fletes` FOR EACH ROW begin
if new.estatus_flete='PAGADO'  then
insert into tbl_cuentas_pagar (fkdeuda, tipo_deuda, tabla, monto_total, monto_pagar, monto_debe, estatus_ctapag, fecha) 
values (new.idflete, 'FLETE' , 'tbl_fletes', new.costo,  new.costo, 0, new.estatus_flete, now());
end if;
end
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_notas_creditos`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_devolucion_por_anulacion` AFTER UPDATE ON `tbl_notas_creditos` FOR EACH ROW BEGIN
IF NEW.estatus_nota='ANULADA' THEN
   
        UPDATE tbl_detalles_notas_creditos SET estatus_nota=NEW.estatus_nota WHERE fknota=NEW.idnota;
  
END IF;
END
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_pagos`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_pagos` AFTER INSERT ON `tbl_pagos` FOR EACH ROW begin

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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_pedidos`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_cta_pagar_pedido` AFTER UPDATE ON `tbl_pedidos` FOR EACH ROW begin

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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_precios_ventas_vendedores`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_cta_pagar_vendedor` AFTER INSERT ON `tbl_precios_ventas_vendedores` FOR EACH ROW begin
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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_regalias`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_restar_inventario_regalia` AFTER INSERT ON `tbl_regalias` FOR EACH ROW BEGIN
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
;;
DELIMITER ;

-- ----------------------------
--  Trigger definition for `tbl_ventas`
-- ----------------------------
DELIMITER ;;
CREATE TRIGGER `trigger_cuenta_por_cobrar` AFTER INSERT ON `tbl_ventas` FOR EACH ROW BEGIN 

insert into tbl_cuentas_cobrar (fkventa, monto_total, monto_debe, monto_haber, estatus) values (new.idventa, new.total_neto, new.total_neto, 0.0, new.estatus_venta);

END
;;
DELIMITER ;
DELIMITER ;;
CREATE TRIGGER `trigger_borrarcuenta_por_pagar` AFTER UPDATE ON `tbl_ventas` FOR EACH ROW begin
if new.estatus_venta='ANULADA' then
   delete from cuentas_cobrar where fkventa=new.idventa;
   delete from cuentas_pagar where  fkdeuda=new.idventa and tipo_deuda='VENDEDOR';
end if;
end
;;
DELIMITER ;
