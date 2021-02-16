ALTER TABLE `tbl_ventas` ADD `totaliva` DOUBLE( 11, 2 ) NOT NULL;

ALTER TABLE `tbl_ventas` ADD `aplica_divisa` CHAR( 1 ) NULL;

ALTER TABLE `tbl_ventas` ADD `divisa` DOUBLE( 11, 2 ) NULL;

ALTER TABLE `tbl_parametros` ADD `email` VARCHAR( 80 ) NULL;

ALTER 
ALGORITHM=UNDEFINED 
DEFINER=`root`@`localhost` 
SQL SECURITY DEFINER 
VIEW `vw_ventas` AS 
SELECT
tbl_ventas.idventa AS idventa,
tbl_vendedores.idvendedor AS idvendedor,
tbl_vendedores.rif AS rif_vendedor,
tbl_vendedores.nombres AS nombres,
tbl_clientes.idcliente AS idcliente,
tbl_clientes.rif AS rif_cliente,
tbl_clientes.razon_social AS razon_social,
tbl_ventas.fecha AS fecha,
tbl_ventas.subtotal AS subtotal,
tbl_ventas.total_neto AS total_neto,
tbl_ventas.excento AS excento,
tbl_ventas.estatus_venta AS estatus_venta,
tbl_ventas.iva,
tbl_ventas.totaliva,
tbl_ventas.aplica_divisa,
tbl_ventas.divisa
FROM
	(
		(
			`tbl_ventas`
			JOIN `tbl_vendedores` ON (
				(
					`tbl_ventas`.`fkvendedor` = `tbl_vendedores`.`idvendedor`
				)
			)
		)
		JOIN `tbl_clientes` ON (
			(
				`tbl_ventas`.`fkcliente` = `tbl_clientes`.`idcliente`
			)
		)
	)
ORDER BY
	`tbl_ventas`.`fecha` DESC ;

CREATE TABLE IF NOT EXISTS `tbl_pagos_notas_entregas` (
  `idabono` int(11) NOT NULL AUTO_INCREMENT,  
`fkventa` int(11) NOT NULL,  
`monto` double(11,2) NOT NULL,  
`modalidad_pago` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,  
`fecha` date NOT NULL, 
 `nro_referencia` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,  
`estatus_abono` varchar(10) COLLATE utf8_spanish2_ci NOT NULL, 
 PRIMARY KEY (`idabono`),  
KEY `fkventa` (`fkventa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

ALTER TABLE `tbl_pagos_notas_entregas`
  ADD CONSTRAINT `tbl_pagos_notas_entregas_ibfk_1` FOREIGN KEY (`fkventa`) REFERENCES `tbl_notas_entrega` (`idnota`) ON DELETE CASCADE ON UPDATE CASCADE;

create VIEW vw_notas_entregas as
SELECT
tbl_notas_entrega.idnota AS idventa,
tbl_vendedores.idvendedor AS idvendedor,
tbl_vendedores.rif AS rif_vendedor,
tbl_vendedores.nombres AS nombres,
tbl_clientes.idcliente AS idcliente,
tbl_clientes.rif AS rif_cliente,
tbl_clientes.razon_social AS razon_social,
tbl_notas_entrega.fecha AS fecha,
tbl_notas_entrega.subtotal AS subtotal,
tbl_notas_entrega.total_neto AS total_neto,
tbl_notas_entrega.excento AS excento,
tbl_notas_entrega.estatus_nota AS estatus_venta,
tbl_notas_entrega.iva,
tbl_notas_entrega.totaliva,
tbl_notas_entrega.aplica_divisa,
tbl_notas_entrega.divisa
FROM
	(
		(
			`tbl_notas_entrega`
			JOIN `tbl_vendedores` ON (
				(
					`tbl_notas_entrega`.`fkvendedor` = `tbl_vendedores`.`idvendedor`
				)
			)
		)
		JOIN `tbl_clientes` ON (
			(
				`tbl_notas_entrega`.`fkcliente` = `tbl_clientes`.`idcliente`
			)
		)
	)
ORDER BY
	`tbl_notas_entrega`.`fecha` DESC;

create VIEW vw_pagos_notas_entregas as
SELECT
	`tbl_pagos_notas_entregas`.`idabono` AS `idabono`,
	`tbl_pagos_notas_entregas`.`fkventa` AS `fkventa`,
	`tbl_pagos_notas_entregas`.`monto` AS `monto`,
	`tbl_pagos_notas_entregas`.`modalidad_pago` AS `modalidad_pago`,
	`tbl_pagos_notas_entregas`.`fecha` AS `fecha_abono`,
	`tbl_pagos_notas_entregas`.`nro_referencia` AS `nro_referencia`,
	`tbl_notas_entrega`.`fkvendedor` AS `fkvendedor`,
	date_format(
		`tbl_notas_entrega`.`fecha`,
		'%Y-%m-%d'
	) AS `fecha_venta`,
	`tbl_notas_entrega`.`fkcliente` AS `fkcliente`,
	`tbl_notas_entrega`.`total_neto` AS `total_neto`,
	`tbl_notas_entrega`.`estatus_nota` AS `estatus_venta`,
	concat(
		`tbl_vendedores`.`nombres`,
		' Rif.',
		`tbl_vendedores`.`rif`
	) AS `vendedor`,
	concat(
		`tbl_clientes`.`razon_social`,
		' Rif.',
		`tbl_clientes`.`rif`
	) AS `nombre_cliente`
FROM
	(
		(
			(
				`tbl_pagos_notas_entregas`
				JOIN `tbl_notas_entrega` ON (
					(
						`tbl_pagos_notas_entregas`.`fkventa` = `tbl_notas_entrega`.`idnota`
					)
				)
			)
			JOIN `tbl_vendedores` ON (
				(
					`tbl_notas_entrega`.`fkvendedor` = `tbl_vendedores`.`idvendedor`
				)
			)
		)
		JOIN `tbl_clientes` ON (
			(
				`tbl_notas_entrega`.`fkcliente` = `tbl_clientes`.`idcliente`
			)
		)
	)
ORDER BY
	`tbl_pagos_notas_entregas`.`fecha` DESC;

ALTER TABLE `tbl_ventas` ADD `idnota` INT NULL;

