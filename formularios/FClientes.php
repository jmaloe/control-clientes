<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
//session_start();
 /*if(!isset($_SESSION['USER']))
 	header("Location:../");
*/
 require_once("Utilidades.php");
 require_once("../controllers/clientes_ctrl.php");
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
			<form name="form_clientes" id="form_clientes" method="POST">
				<div class="panel panel-primary">
				    <div class="panel-heading">
				    	Formulario de Alta, Baja, Actualización y Eliminación de Clientes
				    </div>
			    
					<div class="panel-body">
						
						<?php
							$tab_actual=1;
							require_once("tabsmenu.php");
						?>						
						
						<div id="tab-1" class="tab-content">							
							<div class="form-group" id="clientes">
								<?php 
									if(!empty($msg)){
										echo "<div class='col-md-12' style='color:green;'>$msg</div>";
									}									
								?>								
								<input type="hidden" name="id_cliente" id="id_cliente" <?php echo 'value="'.($cliente_encontrado?$clientes->getIdCliente():"0").'"'; ?>>
								<input type="text" name="nombre" placeholder="Nombre" <?php echo 'value="'.($cliente_encontrado?$clientes->getNombre():"").'"'; ?> required>
								<input type="text" name="apepat" placeholder="Apellido Paterno" <?php echo 'value="'.($cliente_encontrado?$clientes->getApellidoPaterno():"").'"'; ?> required>
								<input type="text" name="apemat" placeholder="Apellido Materno" <?php echo 'value="'.($cliente_encontrado?$clientes->getApellidoMaterno():"").'"'; ?> required>
								<input type="text" name="direccion" placeholder="Dirección" <?php echo 'value="'.($cliente_encontrado?$clientes->getDireccion():"").'"'; ?> required>
								<input type="number" name="telefono" placeholder="Teléfono" <?php echo 'value="'.($cliente_encontrado?$clientes->getTelefono():"").'"'; ?> required>
								<input type="number" name="ddp" placeholder="Día de pago" <?php echo 'value="'.($cliente_encontrado?$clientes->getDiaDePago():"").'"'; ?> required>
								<?php
									//Guardar, Actualizar, Eliminar
								    getAcciones($cliente_encontrado, true);
									echo '<label style="text-align:left;margin-top:5px">Clientes</label>';									
									echo "<table class='table-items'>";
									echo "<tbody>";
									echo '<tr><th>#Cliente</th><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Dirección</th><th>Teléfono</th><th>Fecha de alta</th><th>Fecha de baja</th><th title="Día de pago">DDP</th><th>Saldo</th></tr>';
									$clientes->getClientesActivos();
									echo "</tbody>";
									echo "</table>";
									//echo getExportarExcelButton();
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
 //getExcelExportScripts();
 $db->close_conn();
?>

<!--script src="../js/utilidades_registro.js"></script-->
<script>
$(document).ready(function() {	

	$(".dardebaja").click(function(event){		
		if(!confirm("¿Seguro que desea dar de baja al cliente?")){			
			event.preventDefault();
		}
	});

	$(".table-items").on("click",".cliente",function(){
		var id=$(this).attr("id");		
		$("#id_cliente").val(id);
		$("#form_clientes").submit();
	});

});
</script>