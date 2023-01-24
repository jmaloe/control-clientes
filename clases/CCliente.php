<?php
/*Autor: Jesus Malo, support: dic.malo@gmail.com*/
 require_once("../db/ConexionDB.php");

 class CCliente extends ConexionDB{
 	var $id_cliente=0,
 		$nombre="",
 		$apellidoPaterno="",
		$apellidoMaterno="",
		$direccion,
		$telefono,
		$fechaAlta,
		$fechaDeBaja,
		$diadepago,
		$saldo,
		$truefalse=true,
		$total=0;

 	function __construct($db)
	{
    	parent::__construct($db); /*invocar el constructor de la clase padre*/
 	}

 	//setters
 	function setIdCliente($id){
 		$this->id_cliente = $id;
 	}

 	function setNombre($name){
 		$this->nombre = $this->scapeString($name);
 	}

 	function setApellidoPaterno($s){
 		$this->apellidoPaterno = $this->scapeString($s);
 	}

 	function setApellidoMaterno($t){
 		$this->apellidoMaterno = $this->scapeString($t);
 	}

 	function setDireccion($d){
 		$this->direccion = $this->scapeString($d);
 	}

 	function setTelefono($t){
 		$this->telefono = $this->scapeString($t);
 	}

 	function setFechaAlta($fr){
 		$this->fechaAlta = $this->scapeString($this->getFechaToMysql($fr));
 	}

 	function setFechaBaja($fb){
 		$this->fechaBaja = $this->scapeString($this->getFechaToMysql($fb));
 	}

	function setDiaDePago($ddp){
 		$this->diadepago = $this->scapeString($ddp);
 	}

	function setSaldo($s){
 		$this->saldo = $this->scapeString($s);
 	} 	 	

 	//getters
 	function getIdCliente(){
 		return $this->id_cliente;
 	}

 	function getNombre(){
 		return $this->nombre;
 	}

 	function getApellidoPaterno(){
 		return $this->apellidoPaterno;
 	}

 	function getApellidoMaterno(){
 		return $this->apellidoMaterno;
 	}

 	function getDireccion(){
 		return $this->scapeString($this->direccion);
 	}

 	function getTelefono(){
 		return $this->telefono;
 	}

 	function getFechaAlta(){
 		return $this->fechaAlta;
 	}

 	function getFechaBaja(){
 		return $this->fechaDeBaja;
 	}

 	function getDiaDePago(){
 		return $this->diadepago;
 	}

	function getSaldo(){
 		return $this->saldo;
 	} 

 	function agregarCliente(){
 	 	$sql = "INSERT INTO Clientes (nombre,apellido_paterno,apellido_materno,direccion,telefono,dia_de_pago,saldo)  VALUES('".$this->nombre."','".$this->apellidoPaterno."','".$this->apellidoMaterno."','".$this->direccion."','".$this->telefono."',".$this->diadepago.",".$this->saldo.");";
 	 	//echo $sql;
 	 	if($this->query($sql)){
 	 		$this->id_cliente = $this->getInsertId();	
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 	}	
 	}

 	function actualizarCliente(){
 		$sql = "UPDATE Clientes SET nombre='".$this->nombre."',apellido_paterno='".$this->apellidoPaterno."',apellido_materno='".$this->apellidoMaterno."',direccion='".$this->direccion."',telefono='".$this->telefono."',dia_de_pago=".$this->diadepago." WHERE id_cliente=".$this->id_cliente.";";
 		$this->update($sql);
      return $this->getAffectedRows(); /*id*/   
 	}

 	function actualizarSaldo(){
 		$sql = "UPDATE Clientes SET saldo=".$this->saldo." WHERE id_cliente=".$this->id_cliente.";";
 		$this->update($sql);
      return $this->getAffectedRows(); /*id*/   
 	}

 	function darDeBaja(){
 		$sql = "UPDATE Clientes SET fecha_baja=NOW() WHERE id_cliente=".$this->id_cliente.";";
 		$this->update($sql);
 		return $this->getAffectedRows();
 	}

 	function buscarCliente(){
 		$clausula = "";
 		if($this->nombre!="")
 			$clausula = "nombre like '%".$this->nombre."%'";
 		if($this->apellidoPaterno!="")
 		{
 			if($this->nombre!="")
 				$clausula.=" AND ";
 			$clausula.=" apellido_paterno like '%".$this->apellidoPaterno."%'";
 		}	
 		if($this->apellidoMaterno!="")
 		{
 			if($this->nombre!="" | $this->apellidoPaterno!="")
 				$clausula.=" AND ";
 			$clausula.=" apellido_materno like '%".$this->apellidoMaterno."%'";
 		} 		
 		$sql = "SELECT * FROM Clientes WHERE $clausula";
 	 	$resultado = $this->query($sql); 	 	
 	 	if($resultado){ 	 		
 	 		while($dato = mysqli_fetch_assoc($resultado)){
 	 			$this->doBlock($dato);
 	 			$this->truefalse?$this->truefalse=false:$this->truefalse=true; 	 			
 	 		} 	 		
 	 	}
 	 	else{
 	 		echo "<label>Ningun resultado encontrado</label>";
 	 	}
 	}

 	function findById(){
 		$sql = "SELECT * FROM clientes WHERE id_cliente=".$this->id_cliente;
 	 	$resultado = $this->query($sql); 	 	
 	 	if($resultado){
 	 		$dato = mysqli_fetch_assoc($resultado);
			echo $dato['id_cliente'];
			if(isset($dato['id_cliente'])){
				$this->setIdCliente($dato['id_cliente']);
				$this->setNombre($dato['nombre']);
				$this->setApellidoPaterno($dato['apellido_paterno']);
				$this->setApellidoMaterno($dato['apellido_materno']);
				$this->setDireccion($dato['direccion']);
				$this->setTelefono($dato['telefono']);
				$this->setFechaAlta($dato['fecha_alta']);
				$this->setFechaBaja($dato['fecha_baja']);
				$this->setDiaDePago($dato['dia_de_pago']);
				$this->setSaldo($dato['saldo']);
			  return true;
			} 	 		
 	 	}
 	 	else{
 	 		return false;
 	 	}
 	}

 	function getClientesActivos(){
 	 	$sql = "SELECT * FROM Clientes ORDER BY id_cliente DESC;";
 	 	$resultado = $this->query($sql); 	 	
 	 	
 	 	if($resultado)
 	 	while($dato = mysqli_fetch_assoc($resultado))
 	 	{
		  	$this->doBlock($dato);
			$this->truefalse?$this->truefalse=false:$this->truefalse=true;
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 	}
 	}

 	function getClientesConAdeudos(){
 		$sql = "SELECT * FROM Clientes";
 	 	$resultado = $this->query($sql); 	 	
 	 	
 	 	if($resultado)
 	 	{ 	 		
 	 		while($dato = mysqli_fetch_assoc($resultado))
 	 		{
 	 			echo "<td>".$dato['id_cliente']."</td>";
 	 			echo "<td>".$dato['nombre']."</td>";
 	 			echo "<td>".$dato['apellido_paterno']."</td>";
 	 			echo "<td>".$dato['apellido_materno']."</td>";
 	 			echo "<td>".$dato['direccion']."</td>";
 	 			echo "<td>".$dato['telefono']."</td>";
 	 			echo "<td>".$dato['fecha_alta']."</td>";
 	 			echo "<td>".$dato['fecha_baja']."</td>";
 	 			echo "<td>".$dato['dia_de_pago']."</td>";
 	 			echo "<td>".$dato['saldo']."</td>";
 	 		} 	 		
 	 	}
 	 	else
 	 	{
 	 		echo $this->getError();
 	 	}
 	}

 	function doBlock($dato){
 		if(isset($dato['fecha_baja']))
 			echo "<tr style='color:red' ".($this->truefalse?' class="cliente sombreado"':'class="cliente"')." id='".$dato['id_cliente']."'>";
 		else
 			echo "<tr style='color:green' ".($this->truefalse?' class="cliente sombreado"':'class="cliente"')." id='".$dato['id_cliente']."'>";
 		foreach ($dato as $key => $value) {
 			echo "<td>".$value."</td>";
 		}
 	echo "</tr>";
 	}

	 function findByName(){
		$clausula = "";
		if($this->nombre!="")
			$clausula = "nombre like '%".$this->nombre."%'";
		if($this->apellidoPaterno!="")
		{
			if($this->nombre!="")
				$clausula.=" AND ";
			$clausula.=" apellido_paterno like '%".$this->apellidoPaterno."%'";
		}	
		if($this->apellidoMaterno!="")
		{
			if($this->nombre!="" | $this->apellidoPaterno!="")
				$clausula.=" AND ";
			$clausula.=" apellido_materno like '%".$this->apellidoMaterno."%'";
		} 		
		$sql = "SELECT * FROM Clientes WHERE $clausula";
		 $resultado = $this->query($sql); 	 	
		 if($resultado){ 	 		
			 return true; 	 		
		 }
		 else{
			 return false;
		 }
	}

 }
?>