<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");

// Recebe os dados do formulário
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
$grava = mysqli_query($conn, "INSERT INTO contatos (nome, genero, cargo, orgao, endereco, cep, cidade, telefone, email, unidade) VALUES ('$nome','$genero','$cargo','$orgao','$endereco','$cep','$cidade','$telefone','$email','$codUnidade')");
$id = mysqli_insert_id($conn);

// Redireciona
header("Location: novo-contato.php?contato=$id");