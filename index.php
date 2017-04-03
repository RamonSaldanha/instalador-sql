<?php

DEFINE("CAMINHO_CONFIG", "config.php");
DEFINE("CAMINHO_SQL", "teste_instalador.sql");

if(!empty($_POST)){

	// COMEÇO DA PRIMEIRA ETAPA DE INSTALAÇÃO - CRIAÇÃO DO ARQUIVO CONFIG //
	$config_arq = "
	<?php
	/////////////////////////////////////////////////////// 
	/// config gerenciador de conteudo                  ///
	/// criado por ramon saldanha github @ramonsaldanha ///
	///////////////////////////////////////////////////////

	ob_start();


	";

	foreach($_POST as $coluna => $valor) {
		if($valor == "true") {
				$config_arq .= "define(\"{$coluna}\",{$valor}); \n";
		} elseif($valor == "false") {
			$config_arq .= "define(\"{$coluna}\",{$valor}); \n";
		} else {
			$config_arq .= "define(\"{$coluna}\",\"{$valor}\"); \n";
		}
	}


	$config_arq .= "?>";


	//CAMINHO PRA ONDE VAI O ARQUIVO COM AS DEFINIÇÕES!
	$arquivo = CAMINHO_CONFIG;

	@fclose ($fd);
	$fd = @fopen ($arquivo, "w");
	@fputs($fd, $config_arq);
	@fclose($fd);
	// FIM DA PRIMEIRA ETAPA DE INSTALAÇÃO - CRIAÇÃO DO ARQUIVO CONFIG //




	// INÍCIO DA SEGUNDA ETAPA DE INSTALAÇÃO - IMPORTAR ARQUIVO SQL //

	require(CAMINHO_CONFIG);
	require("funcoes.php");


	//NOME DO ARQUIVO SQL NA PASTA RAIZ DO INSTALDOR
	$arquivo_sql = CAMINHO_SQL;

	$conn = new PDO("mysql:host=" . HOST_PDO . ";dbname=" . DB_PDO . ";charset=utf8mb4", USUARIO_MYSQL, SENHA_MYSQL);

	// Estrutura do banco de dados
	if(file_exists($arquivo_sql)) {

		$sql_query = @fread(@fopen($arquivo_sql, 'r'), @filesize($arquivo_sql));
		$sql_query = remove_remarks($sql_query);
		$sql_query = split_sql_file($sql_query, ";");

		for ($i = 0; $i < sizeof($sql_query); $i++) {
			if (trim($sql_query[$i]) != '') {
				$stmt = $conn->prepare(
					$sql_query[$i]
				);
				$stmt->execute();
			}

		}

	} else {
		echo "esse arquivo não existe";
	}

	// FIM DA SEGUNDA ETAPA DE INSTALAÇÃO - IMPORTAR ARQUIVO SQL //

	echo "<div class=\"container\">";
		echo "<div class=\"alert alert-warning\" role=\"alert\">";
			echo "A instalacao ja foi concluida, renomei ou delete a pasta de instalacao.";
		echo "</div>";
	echo "</div>";

	die;
}

?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<style type="text/css">
	body{
		padding-top: 70px;
	}
</style>

<?php

// se já existir um arquivo com o nome de config.php, ele já vai detectar que foi instalado
if(file_exists(CAMINHO_CONFIG)){
	echo "<div class=\"container\">";
		echo "<div class=\"alert alert-warning\" role=\"alert\">";
			echo "A instalacao ja foi concluida, renomei ou delete a pasta de instalacao.";
		echo "</div>";
	echo "</div>";
	die;
}

?>



<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <h3>Instalador php</h3>
  </div>
</nav>

<div class="container">
<br />
<form method="post" role="form">
<div class="row col-lg-6 col-md-12">
	<div class="col-lg-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Banco de dados</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Nome BD</label>
					<input type="text" name="DB_PDO" class="form-control" placeholder="gerenciador_conteudo" />
				</div>
				<div class="form-group">
					<label>Servidor BD</label>
					<input type="text" name="HOST_PDO" class="form-control" placeholder="localhost" />
				</div>
				<div class="form-group">
					<label>Usuario MYSQL</label>
					<input type="text" name="USUARIO_MYSQL" class="form-control" placeholder="root" />
				</div>
				<div class="form-group">
					<label>Senha Mysql</label>
					<input type="text" name="SENHA_MYSQL" class="form-control" />
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">SMTP</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Servidor SMTP</label>
					<input type="text" name="EMAIL_CONF_HOST" class="form-control" placeholder="smtp.gmail.com ou mail.seudominio.com.br" />
				</div>
				<div class="form-group">
					<label>Porta</label>
					<input type="text" name="EMAIL_CONF_PORT" class="form-control" placeholder="587 ou 465" />
				</div>
				<div class="form-group">
					<label>Protocolo</label>
					<input type="text" name="EMAIL_CONF_SMTPSECURITY" class="form-control" placeholder="SSL ou TLS" />
				</div>
				<div class="form-group">
					<label>Usuario SMTP</label>
					<input type="text" name="EMAIL_CONF_EMAIL" class="form-control" placeholder="no-reply@seudominio.com.br" />
				</div>
				<div class="form-group">
					<label>Senha SMTP</label>
					<input type="text" name="EMAIL_CONF_SENHA" class="form-control" placeholder="" />
				</div>

			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">facebook SDK</div>
			<div class="panel-body">
				<div class="form-group">
					<label>APP id</label>
					<input type="text" name="APP_ID_FB" class="form-control" placeholder="1103164066420384" />
				</div>
				<div class="form-group">
					<label>App Secret</label>
					<input type="text" name="APP_SECRET_FB" class="form-control" placeholder="06c9e43c82924f182ee5ca2b45542xc98eb4cdf" />
				</div>
				<div class="form-group">
					<label>URL de retorno</label>
					<input type="text" name="LOGIN_FACEBOOK_ENDERECO_RETORNO" class="form-control" placeholder="index.php?pg=conf_perfil" />
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Thumb Img</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Altura das imagens</label>
					<input type="text" name="ALTURA_IMG_THUMB" class="form-control" placeholder="450 (nao precisa inserir 'px')" /> <br />
					<div class="well well-sm">Escreva "false" para definir que a imagem vai ter suas caracteristicas originais.</div>
				</div>
				<div class="form-group">
					<label>Largura das imagens</label>
					<input type="text" name="LARGURA_IMG_THUMB" class="form-control" placeholder="300 (nao precisa inserir 'px')" /><br />
					<div class="well well-sm">Escreva "false" para definir que a imagem vai ter suas caracteristicas originais.</div>
				</div>
				<div class="form-group">
					<label>Qualidade 1 ~ 100</label>
					<input type="text" name="QUALIDADE_IMG_THUMB" class="form-control" placeholder="70" />
				</div>
			</div>
		</div>
	</div>

</div>

<div class="row col-lg-6 col-md-12">
	<div class="col-lg-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Informacoes Gerais</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Email para atendimento</label>
					<input type="text" name="EMAIL_RECEBE_CONTATO" class="form-control" placeholder="atendimento@gmail.com ou atendimento@seudominio.com" />
				</div>

				<div class="form-group">
					<label>Cadastro livre</label>
					<input type="text" name="HABILITAR_CADASTRO" class="form-control" placeholder="true ou false" /><br />
					<div class="well well-sm">O sistema vai ser liberado para cadastro? isso forcara a pagina de login, tambem exibir um formulario de cadastro.</div>
				</div>

				<div class="form-group">
					<label>Cadastro sujeito a aprovacao do administrador</label>
					<input type="text" name="HABILITAR_AUTORIZACAO_CADASTRO" class="form-control" placeholder="true ou false" />
				</div>

				<div class="form-group">
					<label>Cadastros sujeito a confirmacao por email</label>
					<input type="text" name="HABILITAR_CONFIRMAR_EMAIL_CADASTRO" class="form-control" placeholder="true ou false" />
				</div>

				<div class="form-group">
					<label>Limite de impressoes por pagina</label>
					<input type="text" name="LIMITE_PAGINACAO" class="form-control" placeholder="6" />
				</div>

				<div class="form-group">
					<label>Input padrao</label>
					<input type="text" name="TIPO_PADRAO_INPUTS" class="form-control" placeholder="text" />
				</div>

				<div class="form-group">
					<label>Titulo da pagina</label>
					<input type="text" name="TITULO_DO_SITE" class="form-control" placeholder="Gerenciador de Conteudo" />
				</div>

				<div class="form-group">
					<label>Logotipo do site</label>
					<input type="text" name="LOGOTIPO" class="form-control" placeholder="http://i.imgur.com/NxEcDBn.jpg" />
				</div>
				<div class="form-group">
					<label>Cor padrao do topo do site</label>
					<input type="text" name="COR_PADRAO_TOPO_SITE" class="form-control" placeholder="#ccc" />
				</div>
				<div class="form-group">
					<label>Cor padrao da fonte do topo do site</label>
					<input type="text" name="COR_PADRAO_FONTE_TOPO_SITE" class="form-control" placeholder="#ccc" />
				</div>
			</div>
		</div>


	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Caminhos</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Endereco do sistema</label>
					<input type="text" name="ENDERECO_SITE" class="form-control" placeholder="http://127.0.0.1/gerenciador-conteudo/" />
				</div>

				<div class="form-group">
					<label>Endereco do portal</label>
					<input type="text" name="ENDERECO_PORTAL" class="form-control" placeholder="http://127.0.0.1/" /> <br />
					<div class="well well-sm">Endereco do portal - Endereco do lugar onde esta o seu site, a pagina inicial onde sera inserido os conteudos.</div>
				</div>

				<div class="form-group">
					<label>Pasta raiz</label>
					<input type="text" class="form-control" name="ENDERECO_ROOT" placeholder="http://127.0.0.1/" /> <br />
					<div class="well well-sm">Endereco root do seu servidor apache, eh a pagina inicial do site; endereco da pasta www ou public_html da sua hospedagem.</div>
				</div>

				<div class="form-group">
					<label>Pasta raiz do sistema</label>
					<input type="text" name="DIRETORIO_BASE" class="form-control" placeholder="gerenciador-conteudo/" />
				</div>

				<div class="form-group">
					<label>Pasta de armazenar imagens</label>
					<input type="text" name="CAMINHO_IMG" class="form-control" placeholder="img_usuario/" />
				</div>

				<div class="form-group">
					<label>Pasta p/ arquivos exportados</label>
					<input type="text" name="CAMINHO_EXCEL_CSV" class="form-control" placeholder="excel_import/" /> <br />
					<div class="well well-sm">O sistema tem um mecanismo que detecta caso exista uma tabela no banco de dados com o nome "newsletter", deduz que esta tabela e responsavel por armazenar usuarios cadastrados no newsletter, e da a opcao de exporta-los para um arquivo excel(.csv) compativel com todos os mecanismos de envio de emails.</div>
				</div>

			</div>
		</div>

	</div>
	<div class="form-group text-center">
		<button class="btn btn-success btn-lg" type="submit">Instalar</button>
	</div>
</form>
</div>
</body>