<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");

// Recebe os dados do formulário
$id = $_POST['id'];
$nome = utf8_decode($_POST['nome']);
$genero = $_POST['genero'];
$cargo = utf8_decode($_POST['cargo']);
$orgao = utf8_decode($_POST['orgao']);
$endereco = utf8_decode($_POST['endereco']);
$cep = $_POST['cep'];
$cidade = utf8_decode($_POST['cidade']);
$telefone = $_POST['telefone'];
$email = $_POST['email'];

// Grava no banco de dados
$grava = mysqli_query($conn, "UPDATE contatos SET nome='$nome', genero='$genero', cargo='$cargo', orgao='$orgao', endereco='$endereco', cep='$cep', cidade='$cidade', telefone='$telefone', email='$email' WHERE id='$id'");

// Redireciona
header("Location: editar-contato.php?contato=$id&id=$id");