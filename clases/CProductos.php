<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
 require_once("../db/ConexionDB.php");

 class CProductos extends ConexionDB{
 	var $id_producto,
 		$nombre,
		$descripcion,
		$precioCompra,
		$precioVenta;

 	function __construct($db)
	{
    	parent::__construct($db); /*invocar el constructor de la clase padre*/
 	}

 	//setters
 	function setIdProducto($id){
 		$this->id_producto = $id;
 	}

 	function setNombre($n){
 		$this->nombre = $this->scapeString($n);
 	}

 	function setDescripcion($d){
 		$this->descripcion = $this->scapeString($d);
 	}

 	function setPrecioCompra($pc){
 		$this->precioCompra = $pc;
 	}

 	function setPrecioVenta($pv){
 		$this->precioVenta = $pv;
 	}

 	//getters
 	function getIdProducto(){
 		return $this->id_producto;
 	}

 	function getNombre(){
 		return $this->nombre;
 	}

 	function getDescripcion(){
 		return $this->descripcion;
 	}

 	function getPrecioCompra(){
 		return $this->precioCompra;
 	}

 	function getPrecioVenta(){
 		return $this->precioVenta;
 	}

 	function agregarProducto(){
 	 	$sql = "INSERT INTO Productos(nombre,descripcion,precio_compra,precio_venta) VALUES('".$this->nombre."','".$this->descripcion."',".$this->precioCompra.",".$this->precioVenta.");";
 	 	if($this->query($sql)){
 	 		$this->id_producto = $this->getInsertId();
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 	}
 	}

 	function actualizarProducto(){
 		$sql = "UPDATE Productos SET nombre='".$this->nombre."',descripcion='".$this->descripcion."',precio_compra=".$this->precioCompra.",precio_venta=".$this->precioVenta." WHERE id_producto=".$this->id_producto;
 		$this->update($sql);
 		return $this->getAffectedRows();
 	}

 	function eliminar(){
 		$sql = "DELETE FROM Productos WHERE id_producto=".$this->id_producto;
 		$this->update($sql);
 		return $this->getAffectedRows();
 	}

 	function getProductos(){
 	 	$sql = "SELECT id_producto as id, concat(nombre,' $',precio_venta) as value, precio_venta FROM Productos order by nombre";
 	 	$resultado = $this->query($sql);
 	 	if($resultado)
 	 		return $this->getCustomDataListItems($resultado);	
 	 	else
 	 	{
 	 		echo $this->getError();
 	 		return;
 	 	}
 	}

 	function getListaProductos(){
 		$sql = "SELECT id_producto as id, nombre, descripcion, precio_compra, precio_venta FROM Productos order by nombre";
 	 	$resultado = $this->query($sql);
 	 	$cont=0; 	 	
 	 	$flag=true;
 	 	while($data = mysqli_fetch_row($resultado)){
 	 		if($flag){
 	 			$flag=false;
 	 			echo '<tr class="producto sombreado" id="'.$data[0].'">';
 	 		}
 	 		else{
 	 		  echo '<tr class="producto" id="'.$data[0].'">';
 	 		  $flag=true;
 	 		}
 	 			echo '<td>'.$data[0].'</td>';
 	 			echo '<td>'.$data[1].'</td>';
 	 			echo '<td>'.$data[2].'</td>';
 	 			echo '<td>'.$data[3].'</td>';
 	 			echo '<td>'.$data[4].'</td>';
 	 		echo '</tr>';
 	 		$cont++;
 	 	} 	 	
 	 	echo '<div class="total">Total de registros:'.$cont.'</div>';
 	}

 	function findById(){
 		$sql = "SELECT id_producto, nombre, descripcion, precio_compra, precio_venta FROM Productos WHERE id_producto=".$this->id_producto;
 	 	$resultado = $this->query($sql);
 	 	if($resultado){
 	 		$dato = mysqli_fetch_assoc($resultado);
			if($dato!=null){
				$this->setIdProducto($dato['id_producto']);
				$this->setNombre($dato['nombre']);
				$this->setDescripcion($dato['descripcion']);
				$this->setPrecioCompra($dato['precio_compra']);
				$this->setPrecioVenta($dato['precio_venta']);
				return true;
			}
 	 	}
 	 	else
 	 	{ 	 		
 	 		return false;
 	 	}
 	}
 }
?>