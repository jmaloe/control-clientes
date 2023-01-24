<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
session_start();
 if(!isset($_SESSION['USER']))
 	header("Location:../");

 require_once("Utilidades.php");
 require_once("../controllers/clientes_ctrl.php");
 require_once("../controllers/abonos_ctrl.php");
 require_once("../controllers/contratos_ctrl.php");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Control de clientes</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Control de Clientes">
		<link href="../imagenes/favicon.ico" rel="icon" type="image/x-icon">
		<?php 
			getStyles();
		?>
	</head>
	<body class="todo-contenido">
		<div id="container">		
			<form name="form_pagos" id="form_pagos" method="POST">
				<div class="panel panel-primary">
				    <div class="panel-heading">
				    	Formulario de Abonos/Pagos
				    </div>
			    
					<div class="panel-body">
						
						<?php
							$tab_actual=2;
							require_once("tabsmenu.php");							
						?>																
						<div id="tab-5" class="tab-content">							
							<div class="col-md-12">
								<input type="hidden" name="id_cliente" id="id_cliente" <?php echo 'value="'.($cliente_encontrado?$clientes->getIdCliente():"0").'"'; ?>>
								<?php 
									if(!empty($msg)){
										echo "<div class='col-md-12' style='color:green;'>$msg</div>";
									}
									if($clientes->getIdCliente()==0)
									{
								?>
								<input type="text" name="nombre" placeholder="Nombre">
								<input type="text" name="apepat" placeholder="Apellido Paterno">
								<input type="text" name="apemat" placeholder="Apellido Materno">
								<button type="submit" class="btn btn-info" name="btn_accion" value="buscar"><i class="fa fa-search"></i> Buscar</button>
								<?php 
									}
								?>
							</div>
							<div id="clientes_abonos">
								<?php								
								if($cliente_encontrado){
									echo '<label style="text-align:left;margin-top:5px">Cliente:</label>';
									echo "<table class='table-items'>";
						 	 		echo "<tbody>";
						 	 		echo "<tr>
						 	 				<th>ID</th>
						 	 				<th>Nombre</th>
						 	 			  	<th>Apellido Paterno</th>
						 	 			  	<th>Apellido Materno</th>
						 	 			  	<th>Dirección</th>
						 	 			  	<th>Teléfono</th>
						 	 			  	<th>Fecha de alta</th>
						 	 			  	<th>Fecha de Baja</th>
						 	 			  	<th>Dia de pago</th>
						 	 			  	<th>Saldo $</th>
						 	 			  </tr>";
									   $clientes->buscarCliente();
									echo "</tbody>";
									echo "</table>";
									if($clientes->getIdCliente()>0){
										//CONTRATOS DEL CLIENTE
										echo '<input type="hidden" name="num_contrato" id="num_contrato" value="'.($contrato_encontrado?$contrato->getNumContrato():"0").'">';
										echo "<table class='table-items'>";
							 	 		echo "<tbody>";
							 	 		echo "<tr>
							 	 				<th>Num. Contrato</th>
							 	 				<th>ID Cliente</th>
							 	 				<th>Cliente</th>
							 	 			  	<th>Fecha de inicio</th>
							 	 			  	<th>Fecha de Termino</th>
							 	 			  	<th>Descripción</th>
							 	 			  	<th>Total $</th>
							 	 			  	<th>Vigente</th>
							 	 			  </tr>";
							 	 			  if($contrato->getNumContrato()>0){							 	 			  	
							 	 			  		$contrato->getContratosDelCliente($clientes->getIdCliente(), "AND ctrt.num_contrato=".$contrato->getNumContrato());
							 	 			  }
							 	 			  else{
										   			$contrato->getContratosDelCliente($clientes->getIdCliente(),"");
												}
										echo "</tbody>";
										echo "</table>";
										if($contrato->getNumContrato()>0)
										{
											//MESES POR COBRAR
											//echo '<input type="hidden" name="id_usuario" value="'.$_SESSION["ID_USER"].'">';
											echo '<div class="alert alert-primary col-md-5 col-sm-12" role="alert">';										
												echo '<div>Detalles del pago</div>';
												echo "<div>Cliente: ".$clientes->getIdCliente().", ".$clientes->getNombre()." ".$clientes->getApellidoPaterno()." ".$clientes->getApellidoMaterno()."</div>";
												echo '<div>Saldo anterior:<input type="text" id="saldo_anterior" value="'.$clientes->getSaldo().'" readonly></div>';
												echo '<div>Nuevo Saldo: <input type="number" name="saldo" id="saldo" value="'.$clientes->getSaldo().'" readonly></div>';
												echo '<div>Total a pagar: <input type="text" id="totalapagar" value="'.$contrato->getTotal().'" readonly></div>';
												echo '<div>Abono: <input type="number" name="total_abono" id="total_abono" required maxlength="4"></div>';
												echo '<div><button type="submit" name="btn_accion" class="btn btn-success" value="registrar_abono">Registrar Pago</button></div>';
											echo "</div>";
											echo '<div class="col-md-6 col-sm-12 meses-adeudados">';
												echo '<table class="table-items">
														<thead>
															<tr>
																<th>#</th>
																<th>Mes</th>
																<th>Año</th>
																<th>Fecha de pago</th>
																<th>Estatus</th>
															</tr>
														</thead>';
												echo '<tbody class="mesesxcobrar">';
												 		$abonos->getMesesPorCobrar($contrato->getNumContrato());
												echo '</tbody>'; 		
												echo '</table>';											
											echo "</div>";
										}
									}									
								}
								?>	
							</div>
						</div>
					</div>
				</div>	  
			</form>			
			<div id="dialog" title="Detalle">
  				<p>Esperando resultado...</p>
			</div>
		<?php 
			echo getHomeButton(); 
			echo getFooter();
		?>		
		</div>
	</body>
</html>
<?php
 getScripts(); /*scripts y estilos comunes utilizados en los demas modulos del sistema*/ 
 $db->close_conn();
?>

<!--script src="../js/utilidades_registro.js"></script-->
<script>
$(document).ready(function() {

	$(".table-items").on("click",".cliente",function(){
		var id=$(this).attr("id");		
		$("#id_cliente").val(id);
		$("#num_contrato").val(0);
		$("#form_pagos").submit();
	});

	$(".table-items").on("click",".contrato",function(){
		var id=$(this).attr("id");		
		$("#num_contrato").val(id);
		$("#form_pagos").submit();
	});	

	$("#total_abono").on("change",function(){
		var total = $("#totalapagar").val();
		var totalabono = $("#total_abono").val();		
		var saldoanterior = $("#saldo_anterior").val();
		var num_pagos = Math.floor((parseFloat(totalabono)+parseFloat(saldoanterior)) / total);
		var sobrante = (parseFloat(totalabono)+parseFloat(saldoanterior)) % total;
		$("#saldo").val(sobrante);
		var totalelements=0, contador=1;
		totalelements = $(".mesesxcobrar").find('tr').size();
		uncheck(totalelements);
	    while(contador<=totalelements & num_pagos>0)
	    {
	    	if( !$("#chk"+contador).prop("disabled") ){	    		
	    		$("#chk"+contador).prop("checked",true);
	    		num_pagos--;
	    	}
	    	contador++;
	    }
	});

	function uncheck(totalelements){
		for (var cont = 1; cont <= totalelements; cont++) {
			if(!$("#chk"+cont).prop("disabled"))
				$("#chk"+cont).prop("checked",false);
		};
	}

	$("#total_abono").focus();

});
</script>