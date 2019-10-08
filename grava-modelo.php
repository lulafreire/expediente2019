<?php
include("conn.php");

// Recebe os dados do formulário
$tipo = $_POST['tipo'];
$titulo = utf8_decode($_POST['titulo']);
$texto = $_POST['txtArtigo'];

// Grava no banco de dados
$grava = mysqli_query($conn, "INSERT INTO modelos (descricao, tipo, texto) VALUES ('$titulo','$tipo','$texto')");
$id = mysqli_insert_id($conn);

// Redireciona
header("Location: novo-modelo.php?modelo=$id");