<?php 
function barra_menu2(){
    $barra='';
    $barra.='<ul class="nav navbar-top-links navbar-right">                              
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="consultar_auditorias.php">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> Auditorias
                                    <span class="pull-right text-muted small">Ver Actividades de usuarios</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="rep_abono_contable1.php">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i>Rep. Contable
                                    <span class="pull-right text-muted small">Por Fecha</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="rep_inventario.php">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i>Rep. Inventario
                                    <span class="pull-right text-muted small">Por Fecha</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> '.$_SESSION['username'].'</a>
                        </li>
                        <li><a href="user.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>';
return $barra;            
}            
?>