<?php
// Configurações para a conexão com o banco de dados

// $host: Define o endereço do servidor onde o banco de dados está hospedado.
// 'localhost' significa que o banco de dados está rodando na mesma máquina
// que o servidor web (onde o PHP está sendo executado).
$host = 'localhost';

// $dbname: Define o nome do banco de dados ao qual queremos nos conectar.
// Neste caso, o banco de dados se chama 'senai_login'.
$dbname = 'sa_mobilerepair';

// $user: Define o nome de usuário para acessar o banco de dados.
// 'root' é um usuário comum em ambientes de desenvolvimento MySQL,
// geralmente com permissões totais (mas não recomendado para produção sem senha!).
$user = 'root';

// $pass: Define a senha para o usuário do banco de dados.
// '' (uma string vazia) significa que não há senha configurada para o usuário 'root'.
// Isso é comum em configurações locais de desenvolvimento, mas **altamente inseguro** para servidores de produção.
$pass = '';

// O bloco try...catch é usado para tratamento de erros.
// Se algo der errado ao tentar conectar ao banco de dados (dentro do 'try'),
// o código dentro do 'catch' será executado para lidar com o erro de forma controlada.
try {
    // Tenta criar uma nova conexão com o banco de dados usando PDO.
    // PDO é uma extensão do PHP que fornece uma interface consistente para acessar
    // diferentes tipos de bancos de dados (MySQL, PostgreSQL, SQLite, etc.).

    // "mysql:host=$host;dbname=$dbname" é o DSN (Data Source Name).
    // Ele especifica:
    //   - 'mysql': o tipo de driver do banco de dados (neste caso, MySQL).
    //   - "host=$host": o servidor do banco de dados (o valor da variável $host, ou seja, 'localhost').
    //   - "dbname=$dbname": o nome do banco de dados (o valor da variável $dbname, ou seja, 'senai_login').
    // As variáveis $user e $pass são o nome de usuário e a senha para autenticação.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    // Configura o PDO para lançar exceções em caso de erros.
    // PDO::ATTR_ERRMODE: Define o modo de relatório de erros.
    // PDO::ERRMODE_EXCEPTION: Se ocorrer um erro na comunicação com o banco (ex: consulta SQL errada),
    // o PDO lançará uma exceção (um tipo especial de erro que pode ser "capturado" pelo bloco catch).
    // Isso é bom para depuração e para tratar erros de forma mais robusta.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Se a conexão for bem-sucedida, a variável $pdo agora contém um objeto
    // que representa a conexão com o banco de dados. Este objeto $pdo
    // será usado em outros scripts (como o login.php) para executar consultas SQL.

} catch (PDOException $e) {
    // Se ocorrer qualquer erro (uma PDOException) durante a tentativa de conexão no bloco 'try',
    // o código dentro deste bloco 'catch' será executado.

    // $e é um objeto que contém informações sobre o erro que ocorreu.
    // $e->getMessage() retorna uma mensagem descrevendo o erro.

    // die() interrompe a execução do script e exibe uma mensagem.
    // É uma forma simples de lidar com um erro crítico de conexão.
    // Em um ambiente de produção, você poderia querer registrar o erro em um arquivo de log
    // ou mostrar uma mensagem mais amigável para o usuário, sem expor detalhes técnicos.
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// Se o script chegar até aqui sem entrar no 'catch', significa que a conexão
// foi estabelecida com sucesso e o objeto $pdo está pronto para ser usado.
// Se este arquivo for incluído (usando 'require' ou 'include') em outros scripts PHP,
// a variável $pdo estará disponível para eles.
?>
