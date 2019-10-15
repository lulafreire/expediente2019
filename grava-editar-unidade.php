<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");

// Recebe os dados do formulário
$nome = utf8_decode($_POST['nome']);
$sigla = $_POST['sigla'];
$endereco = utf8_decode($_POST['endereco']);
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$voip = $_POST['voip'];

// Grava no banco de dados
$grava = mysqli_query($conn, "UPDATE unidades SET nome='$nome', sigla='$sigla', end='$endereco', tel='$telefone', email='$email', voip='$voip' WHERE cod='$codUnidade'");

// Redireciona
header("Location: editar-unidade.php?result=1");