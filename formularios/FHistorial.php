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
				    	Historial de Últimos Pagos
				    </div>
			    
					<div class="panel-body">
						
						<?php
							$tab_actual=3;
							require_once("tabsmenu.php");
						?>												
						
						<div id="tab-3" class="tab-content">
							<!--div id="date">
								<label>Consultar pagos de una fecha específica</label>
								<input type="date" id="fecha_consulta">
								<button type="button" class="btn btn-success btn_consultar_historial" value="2">Consultar</button>
							</div-->							
							<div id="HistorialPagos">
							<label>Historial de últimos pagos</label>	
								<?php 
									$abonos->getHistorialAbonos();
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

	$(".table-items").on("click","tr",function(){
		if($(this).attr("id")!=undefined){
			$.ajax({
		  	type: "POST", //id_producto,cantidad,precio,total, cnsdv+
		  	url: "../controllers/abonos_ctrl.php",
			  data: {"no_pago":$(this).attr("id"), "btn_accion":"mostrar_detalle"},
			  success: function(result){
			  	$("#dialog").html(result);
		  	},
		  	dataType: "html"
			});			
			$( "#dialog" ).dialog();
		}			
	});	

	$(".table-items").on("click",".pago",function(){
		var id=$(this).attr("id");		
		$("#id_cliente").val(id);
		$("#form_clientes").submit();
	});

});
</script>