<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");

// Recebe os dados do formulário
$chave = utf8_decode($_POST['chave']);

// Grava no banco de dados
$grava = mysqli_query($conn, "UPDATE unidades SET chave='$chave' WHERE cod='$codUnidade'");

// Redireciona
header("Location: editar-chave.php?chave=$chave");