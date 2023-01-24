<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
//session_start();
 /*if(!isset($_SESSION['USER']))
 	header("Location:../");
*/
require_once("Utilidades.php");
require_once("../controllers/productos_ctrl.php");

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
			<form name="form_productos" id="form_productos" method="POST">
				<div class="panel panel-primary">
				    <div class="panel-heading">
				    	Formulario de Alta de Productos/Servicios
				    </div>
			    
					<div class="panel-body">						
						<?php
							$tab_actual=6;
							require_once("tabsmenu.php");
						?>						
						<div id="tab-6" class="tab-content">
							<?php 
								if(!empty($msg)){
									echo "<div class='col-md-12' style='color:green;'>$msg</div>";
								}
							?>
							<div class="form-group">								
								<input type="hidden" name="id_producto" id="id_producto" <?php echo 'value="'.($obj_encontrado?$productos->getIdProducto():"0").'"'; ?>>
								<input type="text" name="nombre" placeholder="Nombre del Producto" <?php echo 'value="'.($obj_encontrado?$productos->getNombre():"").'"'; ?>>
								<input type="text" name="descripcion" placeholder="Descripcion" <?php echo 'value="'.($obj_encontrado?$productos->getDescripcion():"").'"'; ?>>
								<input type="number" name="preciocompra" placeholder="Precio Compra" <?php echo 'value="'.($obj_encontrado?$productos->getPrecioCompra():"").'"'; ?>>
								<input type="number" name="precioventa" placeholder="Precio Venta" <?php echo 'value="'.($obj_encontrado?$productos->getPrecioVenta():"").'"'; ?>>
								<?php								    
								    getAcciones($obj_encontrado,false);
									echo '<label style="text-align:left;margin-top:5px">Productos</label>';									
									echo "<table class='table-items'>";
									echo "<tbody>";
									echo '<tr>
											<th>ID</th>
											<th>Nombre</th>
											<th>Descripcion</th>
											<th>Precio Compra</th>
											<th>Precio Venta</th>
										  </tr>';
									$productos->getListaProductos();
									echo "</tbody>";
									echo "</table>";
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
?>

<!--script src="../js/utilidades_registro.js"></script-->
<script>
$(document).ready(function() {
	$(".eliminar").click(function(event){
		//event.preventDefault();
		if(confirm("¿Seguro que desea eliminar el producto?")){
			//$(".dardebaja").submit();			
		}else{
			return false;
		}
	});

	$(".table-items").on("click",".producto",function(){
		var id=$(this).attr("id");
		$("#id_producto").val(id);
		$("#form_productos").submit();		
	});
});
</script>