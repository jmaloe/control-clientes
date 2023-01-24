<?php
echo '<ul class="tabs-menu">
		<li id="tab1"'.($tab_actual==1?" class='current'":"").'>
			<a href="FClientes.php" class="goTab" name="tab" value="1"><i class="fa fa-group"> Clientes</i></a>
		</li>
		<li id="tab2"'.($tab_actual==2?" class='current'":"").'>
			<a href="FAbonos.php" class="goTab" name="tab" value="2"><i class="fa fa-money"> Abono/Pago</i></a>
		</li>
		<li id="tab3"'.($tab_actual==3?" class='current'":"").'>
			<a href="FHistorial.php" class="goTab" name="tab" value="3"><i class="fa fa-tasks"> Historial de pagos</i></a>
		</li>
		<li id="tab4"'.($tab_actual==4?" class='current'":"").'>
			<a href="FPagosVencidos.php" class="goTab" name="tab" value="4"><i class="fa fa-warning"> Pagos vencidos</i></a>
		</li>
		<li id="tab5"'.($tab_actual==5?" class='current'":"").'>
			<a href="FContratos.php" class="goTab" name="tab" value="5"><i class="fa fa-file-text-o"> Contratos</i></a>
		</li>
		<li id="tab6"'.($tab_actual==6?" class='current'":"").'>
			<a href="FProductos.php" class="goTab" name="tab" value="6"><i class="fa fa-apple"> Productos/Servicios</i></a>
		</li>
	  </ul>';
?>