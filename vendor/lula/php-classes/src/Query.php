<?php

namespace Lula;
use \Lula\DB\Sql;
use \Lula\Mailer;
use \Lula\Model;

class Query{

    public static function listAll() {
    
        // Pesquisa todos os usuários
        $sql = new Sql();
        return $sql->select("SELECT nome, origem, icon_query, numero FROM tb_sistemas UNION ALL SELECT nome, origem, icon_query, numero FROM tb_formularios UNION ALL SELECT nome, origem, icon_query, numero FROM tb_links UNION ALL SELECT nome, origem, icon_query, numero FROM tb_archives");
            
    }

    public static function maisAcessados($tabela) {
    
        // Pesquisa todos os usuários
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM $tabela ORDER BY hits DESC LIMIT 5");

        return $results;
            
    }

    public static function todos($tabela) {
    
        // Pesquisa todos os usuários
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM $tabela ORDER BY nome ASC");

        return $results;
            
    }

    public static function listUnidades() {
    
        // Pesquisa todas as unidades
        $sql = new Sql();
        return $sql->select("SELECT codigo, nome FROM tb_unidades ORDER BY codigo ASC");
            
    }

    public static function buscaProcessos($q)
    {
        $sql = new Sql();
        return $sql->select("SELECT * from tb_archives WHERE nome like :q OR numero like :q ORDER BY nome ASC", array(
            ":q"=>"%".$q."%"
        ));

    }

    public static function buscaSistemas($q)
    {
        $sql = new Sql();
        return $sql->select("SELECT * from tb_sistemas WHERE nome = :q", array(
            ":q"=>$q
        ));
    }
    
    public static function buscaFormularios($q)
    {
        $sql = new Sql();
        return $sql->select("SELECT * from tb_formularios WHERE nome = :q", array(
            ":q"=>$q
        ));
    }
    
    public static function buscaLinks($q)
    {
        $sql = new Sql();
        return $sql->select("SELECT * from tb_links WHERE nome = :q", array(
            ":q"=>$q
        ));
    }

    public static function getURL($origem, $id)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT url FROM $origem WHERE id = :id", array(
            ":id"=>$id
        ));

        return $results[0]['url'];
    }

    public static function getData($origem, $id)
    {
        $sql = new Sql();
        return $results = $sql->select("SELECT * FROM $origem WHERE id = :id", array(
            ":id"=>$id
        ));
    }

    public static function getAnexos($id)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_anexos WHERE arquivo = :id", array(
            ":id"=>$id
        ));
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
        ];
    }

    public static function hits($origem, $id)
    {
        $sql = new Sql();
        $hits = $sql->select("SELECT hits FROM $origem WHERE id = :id", array(
            ":id"=>$id
        ));

        $hitsAtual = $hits[0]['hits'];
        $hitsMais  = $hitsAtual + 1;

        $hitsPlus = $sql->query("UPDATE $origem SET hits = :hitsMais WHERE id = :id", array(
            "hitsMais"=>$hitsMais,
            "id"=>$id
        ));
    }

    public static function download($nomeArquivo, $url)
    {
        define('DIR_DOWNLOAD', 'anexos/');
        $arquivo = $url;
        $ext = substr($url, -3);
        $nomeArquivo = $nomeArquivo.".".$ext;
        $arquivo = filter_var($arquivo, FILTER_SANITIZE_STRING);
        $arquivo = basename($arquivo);
        $caminho_download = DIR_DOWNLOAD . $arquivo;
        if (!file_exists($caminho_download))
            die('Arquivo não existe!');
        header('Content-type: octet/stream');
        header('Content-disposition: attachment; filename="'.$nomeArquivo.'";');
        header('Content-Length: '.filesize($caminho_download));
        readfile($caminho_download);
    }

    public static function detalhaConteudo($origem, $id)
    {
        $sql = new Sql();
        return $results = $sql->select("SELECT * FROM $origem WHERE id = :id", array(
            ":id"=>$id
        ));
    }

    public static function getIcons()
    {
        $sql = new Sql();
        return $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_icons ORDER BY class ASC");
        $totalIcons = $sql->select("SELECT FOUND_ROWS() AS nrtotalicons");
       
    }

    public static function getUsers($lotacao, $page = 1, $itensPerPage = 1)
    {
        $start = ($page - 1) * $itensPerPage;
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_users WHERE lotacao = :lotacao ORDER BY nome ASC LIMIT $start, $itensPerPage", array(
            ":lotacao"=>$lotacao
        ));
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
            "pages"=>ceil($total[0]['nrtotal'] / $itensPerPage),
            "actual"=>$page
		];
    }
   
    public function conteudoPaginacao($page = 1, $itensPerPage = 7)
    {
        $start = ($page - 1) * $itensPerPage;
        
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_sistemas UNION ALL SELECT * FROM tb_formularios UNION ALL SELECT * FROM tb_links ORDER BY nome ASC LIMIT $start, $itensPerPage");
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
            "pages"=>ceil($total[0]['nrtotal'] / $itensPerPage),
            "actual"=>$page
        ];
    }

    public function getUnidades($page = 1, $itensPerPage = 7)
    {
        $start = ($page - 1) * $itensPerPage;
        
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_unidades ORDER BY nome ASC LIMIT $start, $itensPerPage");
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
            "pages"=>ceil($total[0]['nrtotal'] / $itensPerPage),
            "actual"=>$page
        ];
    }

    public function deleteUnidade($id)
    {
        $sql = new Sql();
        
        // Pesquisa o código da Unidade para verificar se houve mudança
        $results = $sql->select("SELECT codigo FROM tb_unidades WHERE id=:id", array(
            ":id"=>$id
        ));

        $codigo = $results[0]['codigo'];
        
        $sql->query("UPDATE tb_users SET lotacao = '' WHERE lotacao = :codigo", array(
            ":codigo"=>$codigo
        ));

        $sql->query("DELETE FROM tb_unidades WHERE id = :id", array(
            ":id"=>$id
        ));
    }

    public function editarUnidade($dados)
    {
        $sql = new Sql();

        // Pesquisa o código da Unidade para verificar se houve mudança
        $results = $sql->select("SELECT codigo FROM tb_unidades WHERE id=:id", array(
            ":id"=>$dados['id']
        ));

        $codAtual = $results[0]['codigo'];

        if($codAtual != $dados['codigo'])
        {
            $sql->query("UPDATE tb_users SET lotacao = :codigo WHERE lotacao = :codAtual", array(
                ":codigo"=>$dados['codigo'],
                ":codAtual"=>$codAtual
            ));
        }

        $sql->query("UPDATE tb_unidades SET nome = :nome, codigo = :codigo, telefone = :telefone, endereco = :endereco WHERE id = :id", array(
            ":nome"=>$dados['nome'],
            ":codigo"=>$dados['codigo'],
            ":telefone"=>$dados['telefone'],
            ":endereco"=>$dados['endereco'],
            "id"=>$dados['id']
        ));
    }

    public function avisos($unidade, $page = 1, $itensPerPage = 1)
    {
        $start = ($page - 1) * $itensPerPage;
        
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_avisos WHERE unidade = :unidade ORDER BY data DESC LIMIT $start, $itensPerPage", array(
            ":unidade"=>$unidade
        ));
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
            "pages"=>ceil($total[0]['nrtotal'] / $itensPerPage),
            "actual"=>$page
        ];
    }

    public function getAvisos($page = 1, $itensPerPage = 2)
    {
        $start = ($page - 1) * $itensPerPage;
        
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_avisos ORDER BY data DESC LIMIT $start, $itensPerPage");
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");
        
        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
            "pages"=>ceil($total[0]['nrtotal'] / $itensPerPage),
            "actual"=>$page,
        ];
    }

    public function novoAviso($dados)
    {
        $sql = new Sql();
        $sql->query("INSERT INTO tb_avisos (titulo, texto, url, autor, data, unidade) VALUES (:titulo, :texto, :url, :autor, curdate(), :unidade)", array(
            ":titulo"=>$dados['titulo'],
            ":texto"=>$dados['texto'],
            ":url"=>$dados['url'],
            ":autor"=>$_SESSION[User::SESSION]['nome'],
            ":unidade"=>$_SESSION[User::SESSION]['lotacao']
        ));
    }

    public function editarAviso($dados)
    {
        $sql = new Sql();
        $sql->query("UPDATE tb_avisos SET titulo = :titulo, texto = :texto, url = :url WHERE id = :id", array(
            ":titulo"=>$dados['titulo'],
            ":texto"=>$dados['texto'],
            ":url"=>$dados['url'],
            ":id"=>$dados['id']
        ));
    }

    public function deleteAviso($id)
    {
        $sql = new Sql();
        $sql->query("DELETE FROM tb_avisos WHERE id = :id", array(
            ":id"=>$id
        ));
    }
    
    public static function novoConteudo($tabela, $nome, $descricao, $url, $origem, $icone, $tags)
    {
        $sql = new Sql();
        $results = $sql->query("INSERT INTO $tabela (nome, descricao, url, origem, icone, tags, hits) VALUES (:nome, :descricao, :url, :origem, :icone, :tags, 0)", array(
            ":nome"=>$nome,
            ":descricao"=>$descricao,
            ":url"=>$url,
            ":origem"=>$origem,
            ":icone"=>$icone,
            ":tags"=>$tags
        ));

    }

    public static function cadastraUnidade($dados)
    {
        $sql = new Sql();
        $sql->query("INSERT INTO tb_unidades (nome, codigo, telefone, endereco) VALUES (:nome, :codigo, :telefone, :endereco)", array(
            ":nome"=>strtoupper($dados['nome']),
            ":codigo"=>$dados['codigo'],
            ":telefone"=>$dados['telefone'],
            ":endereco"=>$dados['endereco']
        ));
    }

    public static function updateConteudo($id, $tabela, $nome, $descricao, $url, $origem, $icone, $tags)
    {
        $sql = new Sql();
        $results = $sql->query("UPDATE $tabela set nome = :nome, descricao = :descricao, url = :url, origem = :origem, icone = :icone, tags = :tags WHERE id = :id", array(
            ":id"=>$id,
            ":nome"=>$nome,
            ":descricao"=>$descricao,
            ":url"=>$url,
            ":origem"=>$origem,
            ":icone"=>$icone,
            ":tags"=>$tags
        ));

    }

    public static function excluirConteudo($origem, $id)
    {
        $tabela = "tb_".strtolower($origem);
        $sql = new Sql();
        $sql->query("DELETE FROM $tabela WHERE id = :id", array(
            ":id"=>$id
        ));
    }

}