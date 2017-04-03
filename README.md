# instalador-sql
TODAS AS DEFINIÇÕES DO ARQUIVO CONFIG, VÃO DE ACORDO COM OS INPUTS DO ARQUIVO INDEX.PHP
PORTANTO, SE COLOCAR APENAS 3 CAMPOS, O ARQUIVO CONFIG QUE SERÁ GERADO, SÓ TERÁ 3 DEFINIÇÕES.

EXEMPLO: 

index.php
<input type="text" name="DB_PDO" class="form-control" placeholder="gerenciador_conteudo" />
<input type="text" name="HOST_PDO" class="form-control" placeholder="localhost" />
<input type="text" name="USUARIO_MYSQL" class="form-control" placeholder="root" />


config.php (que será gerado na instalação)

<?php
define("DB_PDO","teste_instalador"); 
define("HOST_PDO","localhost"); 
define("USUARIO_MYSQL","root"); 
?>

É NECESSÀRIO A EXISTÊNCIA DOS CAMPOS PRA CONEXÃO COM O MYSQL, PRA SÓ ASSIM CONSEGUIR IMPORTAR O ARQUIVO .SQL
