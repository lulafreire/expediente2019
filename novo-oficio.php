<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- JQUERY completar formulário -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>

    <title>.:: Expediente ::.</title>
  </head>
  <body>

  
  <script type='text/javascript'>
			$(document).ready(function(){
				$("input[name='destinatario']").blur(function(){
					var $cargo = $("input[name='cargo']");
					var $orgao = $("input[name='orgao']");
					$.getJSON('function.php',{ 
						destinatario: $( this ).val() 
					},function( json ){
						$cargo.val( json.cargo );
						$orgao.val( json.orgao );
					});
				});
			});
		</script> 

        
        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <h6><i class="fas fa-file"></i> Novo <b>OFÍCIO</b></h6>
                        <hr size="1" width="100%">
                    </div>
                    <div class="row">
                        <div class="form-group col mx-0">
                            <form method="post" action="">
                                <label for="matricula">Destinatário</label>
                                <input name="matricula" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <label for="cargo">Cargo</label>
                                <input name="cargo" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <label for="orgao">Órgão</label>
                                <input name="orgao" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <label for="endereco">Endereço</label>
                                <input name="endereco" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <label for="cep">CEP</label>
                                <input name="cep" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                                                
                                <label for="tratamento">Forma de Tratamento</label>
                                <input name="tratamento" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                
                            </form>                    
                        </div>                        
                    </div>                    
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-6">
                            <label for="destinatario">Resposta ao Ofício</label>
                            <input name="destinatario" class="form-control form-control-sm mb-2" type="text" placeholder="Nome do Destinatário">
                        </div>
                        <div class="col-6">
                            <label for="destinatario">Assunto</label>
                            <input name="destinatario" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                        </div>                                          
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="dvCentro">
                                <textarea id="txtArtigo" name="txtArtigo"></textarea>
                            </div>
                        </div>                        
                    </div>                    
                    <div class="row">
                        <div class="col-6">
                            <label for="destinatario">Emissor</label>
                            <input name="destinatario" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                        </div>
                        <div class="col-6">                            
                            <input type="submit" class="btn btn-success btn-sm mr-auto" value="Salvar">
                        </div>                       
                    </div>
                </div>
            </div>            
        </div>
        
    

    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/popper.js/dist/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'txtArtigo' );
    </script>
  </body>
</html>