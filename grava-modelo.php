<?php
include("conn.php");
include("functions.php");

// Recebe os dados do formulário
$tipo = $_POST['tipo'];
$titulo = utf8_decode($_POST['titulo']);
$texto = $_POST['txtArtigo'];

$converte = desconversao($texto);
$novoTexto = utf8_decode($converte);

// Grava no banco de dados
$grava = mysqli_query($conn, "INSERT INTO modelos (descricao, tipo, texto) VALUES ('$titulo','$tipo','$novoTexto')");
$id = mysqli_insert_id($conn);

// Redireciona
header("Location: novo-modelo.php?modelo=$id");