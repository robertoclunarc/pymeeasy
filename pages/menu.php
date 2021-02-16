<?php
function barra_menu(){
    //session_start();
    $barra='';
    if (isset($_SESSION['username']) && isset($_SESSION['userid']))
        switch ($_SESSION['nivel']) {
        case 1:
    $barra.='<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Inicio</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-table fa-fw"></i> Inventario<span class="fa arrow"></span></a>
                             <ul class="nav nav-second-level">
                                <li>
                                    <a href="producto_nuevo.php">Registrar Producto</a>
                                </li>
                                <li>
                                    <a href="consultar_productos.php">Consultar Producto</a>
                                </li>
                                <li>
                                    <a href="consulta_precios.php">Consultar Precios</a>
                                </li>
                                <li>
                                    <a href="consultar_cargas_inventario.php">Consultar Cargas Invent.</a>
                                </li>                                
                                <li>
                                    <a href="regalias.php">Registrar Regalias</a>
                                </li>
                                <li>
                                    <a href="consulta_regalias.php">Consulta Regalias</a>
                                </li>
                             </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Pedidos (Compras)<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="pedido_nuevo.php">Registrar Pedido</a>
                                </li>
                                <li>
                                    <a href="consulta_pedidos.php">Consultar Pedidos</a>
                                </li>
                             </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Ventas y Pagos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="cuadres_precios.php">Ajuste de Precios</a>
                                </li>
                                <li>
                                    <a href="nueva_venta.php">Registrar Venta</a>
                                </li>
                                <li>
                                    <a href="consultar_ventas.php">Consultar Reg. Ventas</a>
                                </li>
                                
                                <li>
                                    <a href="consultar_abonos.php">Consultar Abonos</a>
                                </li>
                                <li>
                                    <a href="cuentas_cobrar.php">Cuentas x Cobrar</a>
                                </li>
                                <li>
                                    <a href="cuentas_pagar.php">Cuentas x Pagar</a>
                                </li>
                                <li>
                                    <a href="consultar_pagos.php">Consultar Pagos</a>
                                </li>
                                <li>
                                    <a href="pagos_flete.php">Pago de Fletes</a>
                                </li>
                                <li>
                                    <a href="consultar_fletes.php">Consultar Pago de Fletes</a>
                                </li>
                                
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-shopping-cart"></i>  Notas de Entrega<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">N.E. Ventas<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="nueva_notaent.php">Registrar Nota Entrega</a>
                                        </li>
                                        <li>
                                            <a href="rep_nota_ent1.php">Ver Notas Entrega</a>
                                        </li>
                                        
                                     </ul>
                                            <!-- /.nav-third-level -->
                                </li>
                                <li>
                                    <a href="#">N.C. Compras<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="nueva_notacred.php">Registrar Nota Credito</a>
                                        </li>
                                        <li>
                                            <a href="consultar_notacredito.php">Ver Notas Creditos</a>
                                        </li>
                                        <li>
                                            <a href="actualizar_notacredito.php">Actualizar Nota Credito</a>
                                        </li>
                                     </ul>
                                            <!-- /.nav-third-level -->
                                </li>
                             </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Clientes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="cliente_nuevo.php">Nuevo Cliente</a>
                                </li>
                                <li>
                                    <a href="consulta_clientes.php">Ver Clientes</a>
                                </li>
                                <li>
                                    <a href="actualizar_clientes.php">Actualizar Cliente</a>
                                </li>
                            </ul>
                        </li>
                         <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Proveedores<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="proveedor_nuevo.php">Registrar Proveedor</a>
                                </li>
                                <li>
                                    <a href="consulta_proveedores.php">Ver Proveedores</a>
                                </li> 
                                <li>
                                    <a href="actualizar_proveedores.php">Actualizar Proveedores</a>
                                </li>
                             </ul>
                        </li>
                       <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Vendedores<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="vendedor_nuevo.php">Registrar Vendedor</a>
                                </li>
                                <li>
                                    <a href="consultar_vendedores.php">Ver Vendedores</a>
                                </li>
                                <li>
                                    <a href="actualizar_vendedor.php">Actualizar Vendedores</a>
                                </li>
                             </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Parametros<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="parametros1.php">Datos Empresa</a>
                                </li>
                                <li>
                                    <a href="cuentas_bancarias.php">Cuentas Bancarias</a>
                                </li>
                                <li>
                                    <a href="consulta_precios_divisas.php">Precio Divisas</a>
                                </li>
                             </ul>
                        </li>
                    </ul>
                </div>
            </div>';
    break;
    case 2:
      $barra.='<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Inicio</a>
                        </li>
                        
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i>Pedidos (Compras)<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="pedido_nuevo.php">Registrar Pedido</a>
                                </li>
                                <li>
                                    <a href="consulta_pedidos.php">Consultar Pedidos</a>
                                </li>
                             </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i>Ventas y Pagos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                
                                <li>
                                    <a href="nueva_venta.php">Registrar Venta</a>
                                </li>
                                <li>
                                    <a href="consultar_ventas.php">Consultar Reg. Ventas</a>
                                </li>
                                
                                <li>
                                    <a href="consultar_abonos.php">Consultar Abonos</a>
                                </li>
                                <li>
                                    <a href="cuentas_cobrar.php">Cuentas x Cobrar</a>
                                </li>
                                <li>
                                    <a href="cuentas_pagar.php">Cuentas x Pagar</a>
                                </li>
                                <li>
                                    <a href="consultar_pagos.php">Consultar Pagos</a>
                                </li>
                                <li>
                                    <a href="pagos_flete.php">Pago de Fletes</a>
                                </li>
                                <li>
                                    <a href="consultar_fletes.php">Consultar Pago de Fletes</a>
                                </li>
                                
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i>Clientes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="cliente_nuevo.php">Nuevo Cliente</a>
                                </li>
                                <li>
                                    <a href="consulta_clientes.php">Ver Clientes</a>
                                </li>
                                <li>
                                    <a href="actualizar_clientes.php">Actualizar Cliente</a>
                                </li>
                            </ul>
                        </li>
                         <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i>Proveedores<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                <li>
                                    <a href="proveedor_nuevo.php">Registrar Proveedor</a>
                                </li>
                                <li>
                                    <a href="consulta_proveedores.php">Ver Proveedores</a>
                                </li> 
                                <li>
                                    <a href="actualizar_proveedores.php">Actualizar Proveedores</a>
                                </li>
                             </ul>
                        </li>
                       
                        
                    </ul>
                </div>
            </div>';
 }                 
return $barra;
}
?>