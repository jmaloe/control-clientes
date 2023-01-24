<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
//session_start();
 /*if(!isset($_SESSION['USER']))
 	header("Location:../");
*/
 require_once("Utilidades.php");
 require_once("../controllers/clientes_ctrl.php");
 require_once("../controllers/productos_ctrl.php");
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
			<form name="form_clientes" id="form_clientes" method="POST">
				<div class="panel panel-primary">
				    <div class="panel-heading">
				    	Formulario de Registro de Contratos Nuevos
				    </div>
			    
					<div class="panel-body">
						
						<?php
							$tab_actual=5;
							require_once("tabsmenu.php");
						?>						
						
						<div id="tab-2" class="tab-content">							
							<div class="form-group" id="clientes">
							<input type="hidden" name="id_cliente" id="id_cliente" <?php echo 'value="'.($cliente_encontrado?$clientes->getIdCliente():"0").'"'; ?>>
								<?php 
									if(!empty($msg)){
										echo "<div class='col-md-12' style='color:green;'>$msg</div>";
									}
									if($clientes->getIdCliente()==0){
								?>								
										<input type="text" name="nombre" placeholder="Nombre" <?php echo 'value="'.($cliente_encontrado?$clientes->getNombre():"").'"'; ?>>
										<input type="text" name="apepat" placeholder="Apellido Paterno" <?php echo 'value="'.($cliente_encontrado?$clientes->getApellidoPaterno():"").'"'; ?>>
										<input type="text" name="apemat" placeholder="Apellido Materno" <?php echo 'value="'.($cliente_encontrado?$clientes->getApellidoMaterno():"").'"'; ?>>								
										<button type="submit" class="btn btn-info" name="btn_accion" value="buscar"><i class="fa fa-search"></i> Buscar</button>
										<button type="submit" class="btn btn-warning" name="btn_accion" value="listacontratos"><i class="fa fa-list"></i> Contratos</button>
								<?php
									}
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
										echo '<div class="alert alert-primary col-md-5 col-sm-12" role="alert">';
										echo 'No Contrato: '.$contrato->getNextContrato().'<br>';
										//echo "Cliente: ".$clientes->getNombre()." ".$clientes->getApellidoPaterno()." ".$clientes->getApellidoMaterno()."<br>";
										echo 'Fecha de Inicio: <input type="date" name="fecha_i" id="fecha_i" required><br>';
										echo '<textarea name="descripcion" placeholder="Descripción" maxlength="255" class="form-control" required></textarea>';
										echo "</div>";
										echo '<div class="col-md-6 col-sm-12 seleccion-productos">';
										echo '<datalist id="productos">'.$productos->getProductos().'</datalist>';
										echo '<label>Productos/Servicios:</label>';
										echo '<div class="arti">';
											echo '<div class="fila">';											
											echo '<div class="col-md-5"><input type="hidden" name="producto[id][]" id="id_prod0"><input list="productos" id="0" class="form-control producto" autocomplete="off" placeholder="Producto"></div>';
											echo '<div class="col-md-2"><input type="number" name="producto[cantidad][]" class="form-control cantidad" placeholder="Cant." value="1"></div>';
											echo '<div class="col-md-2"><input type="number" name="producto[precio][]" class="form-control precio" placeholder="Precio"></div>';
											echo '<div class="col-md-2"><input type="number" name="producto[total][]" class="form-control total" placeholder="Total"></div>';
											echo '<div class="col-md-1"><button class="btn btn-info" id="mascampos">+</button></div>';
											echo '</div>';
										echo '</div>';
										echo '<div>Total:<input type="text" name="total" id="total" readonly></div>';
										echo '<div><button type="submit" name="btn_accion" class="btn btn-success" value="nuevocontrato">Crear contrato</button></div>';
										echo "</div>";										
									}
								}
								if(isset($_POST['btn_accion'])){
									if($_POST['btn_accion']=="listacontratos"){
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
										   $contrato->getListaContratos();
										echo "</tbody>";
										echo "</table>";
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

	$(".table-items").on("click",".contrato",function(){
		if($(this).attr("id")!=undefined){
			$.ajax({
		  	type: "POST", //id_producto,cantidad,precio,total, cnsdv+
		  	url: "../controllers/contratos_ctrl.php",
			  data: {"num_contrato":$(this).attr("id"), "btn_accion":"mostrar_detalle"},
			  success: function(result){
			  	$("#dialog").html(result);
		  	},
		  	dataType: "html"
			});			
			$( "#dialog" ).dialog();
		}			
	});

	$(".table-items").on("click",".cliente",function(){
		var id=$(this).attr("id");		
		$("#id_cliente").val(id);
		$("#form_clientes").submit();
	});

	var id=1;
	$(".arti").on("click","#mascampos",function(event){
		event.preventDefault();
		$(".arti").append('<div class="fila">'+								
								'<div class="col-md-5"><input type="hidden" name="producto[id][]" id="id_prod'+id+'"><input list="productos" id="'+id+'" class="form-control producto" autocomplete="off" placeholder="Producto"></div>'+
								'<div class="col-md-2"><input type="number" name=producto[cantidad][] class="form-control cantidad" placeholder="Cant." value="1"></div>'+
								'<div class="col-md-2"><input type="number" name=producto[precio][] class="form-control precio" placeholder="Precio"></div>'+
								'<div class="col-md-2"><input type="number" name=producto[total][] class="form-control total" placeholder="Total"></div>'+
								'<div class="col-md-1"><button class="btn btn-danger remove">-</button></div>'+
							'</div>');
		id++;
	});

	$(".arti").on("click",".remove",function(){		
		var parent = $(this).parent().parent();
		$(parent).find(".total").val("0.0");
		calcularTotalARegistrar($(this));
		$(parent).remove();
	});

	$(".arti").on("change",'.producto',function(){
		var idres = getDataListIdValue("#"+$(this).attr("id"),"#productos");
		$("#id_prod"+$(this).attr("id")).val(idres);
		var parent = $(this).parent().parent();		
		var precio = getValorEnDataList($(this).val(), "precio_venta");
		var cantidad = $(parent).find(".cantidad").val();
		$(parent).find(".precio").val(precio);
		$(parent).find(".total").val(cantidad*precio);
		calcularTotalARegistrar($(this));
	});

	$(".arti").on('change','.cantidad',function(){
		var parent = $(this).parent().parent();
		var precio = $(parent).find(".precio").val();		
		$(parent).find(".total").val( $(this).val()*precio );
		calcularTotalARegistrar( $(this) );		
	});

	$(".arti").on('change','.precio',function(){
		var parent = $(this).parent().parent();
		var cantidad = $(parent).find(".cantidad").val();		
		$(parent).find(".total").val( $(this).val()*cantidad );
		calcularTotalARegistrar( $(this) );		
	});

	function getValorEnDataList(dato_a_buscar, atributoADevolver){
		var x = dato_a_buscar;
		var z = $("#productos");
		var val = $(z).find('option[value="'+x+'"]');
		var endval = val.attr(atributoADevolver);
		if(endval!=undefined)
		 return endval;	
		else
		 return 0;
	}

	function calcularTotalARegistrar(nodo){
		var total=0;
		var grandparent = $(nodo).parent().parent().parent();

		$('.total',$(grandparent)).each(function () {
				total = total + Number( $(this).val() );
		});		
		$("#total").val(total);
	}

	$("#fecha_i").val(getCurrentDate());

});
</script>