<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['cargo'])) {
  echo json_encode(['ok'=>false,'msg'=>'Acesso negado']); exit();
}

try{
  $id = (int)($_POST['id'] ?? 0);
  if ($id<=0) throw new Exception('ID inválido');

  // Campos que existem em ordem_serv
  $aparelho = trim($_POST['aparelho'] ?? '');
  $servico  = trim($_POST['servico']  ?? '');
  $data_entrada = $_POST['data_entrada'] ?: null; // YYYY-MM-DD
  $data_saida   = $_POST['data_saida']   ?: null;
  $valor   = str_replace(',','.', preg_replace('/[^\d,\.]/','', $_POST['valor'] ?? '0')); // normaliza
  $tipo    = trim($_POST['tipo_pagamento'] ?? '');
  $status  = trim($_POST['status'] ?? '');

  $sql = "UPDATE ordem_serv
          SET Aparelho = :aparelho,
              servico = :servico,
              data_entrada = :de,
              data_saida   = :ds,
              valor = :valor,
              tipo_pagamento = :tp,
              status = :st
          WHERE id_ordem_serv = :id";
  $st = $pdo->prepare($sql);
  $st->bindValue(':aparelho',$aparelho);
  $st->bindValue(':servico',$servico);
  $st->bindValue(':de',$data_entrada ?: null, PDO::PARAM_NULL | PDO::PARAM_STR);
  $st->bindValue(':ds',$data_saida   ?: null, PDO::PARAM_NULL | PDO::PARAM_STR);
  $st->bindValue(':valor', $valor!=='' ? $valor : 0);
  $st->bindValue(':tp',$tipo);
  $st->bindValue(':st',$status);
  $st->bindValue(':id',$id, PDO::PARAM_INT);
  $ok = $st->execute();

  if (!$ok) throw new Exception('Falha ao atualizar');

  $ret = [
    'ok'=>true,
    'data'=>[
      'Aparelho'=>$aparelho,
      'servico'=>$servico,
      'data_entrada_br'=>$data_entrada ? date('d/m/Y', strtotime($data_entrada)) : '-',
      'data_saida_br'  =>$data_saida   ? date('d/m/Y', strtotime($data_saida))   : '-',
      'valor_fmt'=>number_format((float)$valor,2,',','.'),
      'tipo_pagamento'=>$tipo,
      'status'=>$status,
      'status_class'=> (function($s){
        $s = mb_strtolower($s,'UTF-8');
        if ($s==='em andamento' || $s==='andamento') return 'andamento';
        if ($s==='concluído' || $s==='concluido')   return 'concluido';
        if ($s==='cancelado') return 'cancelado';
        return 'pendente';
      })($status)
    ]
  ];
  echo json_encode($ret);
}catch(Exception $e){
  echo json_encode(['ok'=>false,'msg'=>$e->getMessage()]);
}
