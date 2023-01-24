<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
//session_start();
 /*if(!isset($_SESSION['USER']))
 	header("Location:../");
*/
 require_once("Utilidades.php");
 require_once("../controllers/abonos_ctrl.php");
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
				    	Pagos vencidos
				    </div>
			    
					<div class="panel-body">
						
						<?php
							$tab_actual=4;
							require_once("tabsmenu.php");
						?>						
						<div id="tab-4" class="tab-content">							
							<div id="AdeudosClientes">
								<?php
									$abonos->getPagosVencidos();
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
	$("#HistorialPagos").on("click","tr",function(){
		if($(this).attr("value")!=undefined){
			$.ajax({
		  	type: "POST", //id_producto,cantidad,precio,total, cnsdv+
		  	url: "HistorialPagos.php",
			  data: {"no_venta":$(this).attr("value")},
			  success: function(result){
			  	$("#dialog").html(result);			  		  	
		  	},
		  	dataType: "html"
			});			
			$( "#dialog" ).dialog();
		}			
	});

	$(".table-items").on("click",".detalle",function(){
		var id=$(this).attr("id");		
		$("#id_cliente").val(id);
		$("#form_clientes").submit();
	});

});
</script>