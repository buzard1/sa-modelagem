<?php
session_start();
session_unset(); // limpa todas as variáveis de sessão
session_destroy(); // destrói a sessão
header("Location: index.php"); // redireciona para a página de login
exit();
?>
