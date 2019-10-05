<?php

include("conn.php");
$idAnexo = $_GET['idAnexo'];
$idOficio = $_GET['idOficio'];
$arquivo  = $_GET['arquivo'];

$delete = mysqli_query($conn, "DELETE FROM anexos WHERE id='$idAnexo'");
// Grava o evento
$sqlEvento = mysqli_query($conn, "insert into eventos (data, descricao, referencia) values (now(),'ANEXO EXCLUíDO','$idOficio')");

unlink ("anexos/$arquivo");

// Verifica o tipo do ofício (0 = emitido, 1 = recebido)
$sqlTipo = mysqli_query($conn, "SELECT tipo FROM documentos WHERE id = '$idOficio'");
while($t=mysqli_fetch_array($sqlTipo))
{
	$tipo = $t['tipo'];
}

if($tipo =='0') {

	header('location: anexar-oficio-emitido.php?id='.$idOficio);

} else {

	header('location: anexar-oficio-recebido.php?id='.$idOficio);	

}


?>