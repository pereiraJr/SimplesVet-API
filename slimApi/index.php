<?php
require 'vendor/autoload.php';
include 'config.php';
$app = new Slim\App(["settings" => $config]);
//Handle Dependencies
$container = $app->getContainer();

$container['db'] = function ($c) {

   try{
       $db = $c['settings']['db'];
       $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
       );
       $pdo = new PDO("mysql:host=" . $db['servername'] . ";dbname=" . $db['dbname'],
       $db['username'], $db['password'],$options);
       return $pdo;
   }
   catch(\Exception $ex){
       return $ex->getMessage();
   }

};

$app->get('/animais', function ($request,$response) {
   try{
       $con = $this->db;
       $sql = "SELECT * FROM animal";
       $result = null;
       foreach ($con->query($sql) as $row) {
           $result[] = $row;
       }
       if($result){
           return $response->withJson($result);
       }else{
           return $response->withJson(array('status' => 'Users Not Found'),422);
       }

   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }

});

$app->post('/animais', function ($request,$response) {
   try{
       $con = $this->db;
       $sql = "INSERT into animal(nome, raca, peso) values (:nome, :raca, :peso)";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':nome' => $request->getParam('nome'),
       ':raca' => $request->getParam('raca'),
       ':peso' => $request->getParam('peso')
       );
       $result =  $pre->execute($values);
       if($result){
           return $response->withJson(array('status' => 'Animal Inserido'),200);
       }else{
           return $response->withJson(array('status' => 'Deu Pau'),422);
       }
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }

});

$app->post('/animais/{id}', function ($request,$response) {
   try{
       $id = $request->getAttribute('id');
       $con = $this->db;
       $sql = "UPDATE animal SET nome=:nome,raca=:raca,peso=:peso WHERE id = :id";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':nome' => $request->getParam('nome'),
       ':raca' => $request->getParam('raca'),
       ':peso' => $request->getParam('peso'),
       ':id' => $id
       );
       $result =  $pre->execute($values);
       if($result){
           return $response->withJson(array('status' => 'Animal Atualizado'),200);
       }else{
           return $response->withJson(array('status' => 'Animal nao encontrado'),422);
       }
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }

});

$app->get('/animais/{id}', function ($request,$response) {
   try{
       $id     = $request->getAttribute('id');
       $con = $this->db;
       $sql = "SELECT * FROM animal WHERE id = :id";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':id' => $id);
       $pre->execute($values);
       $result = $pre->fetch();
       if($result){
           return $response->withJson(array('status' => 'Animal Encontrado','result'=> $result),200);
       }else{
           return $response->withJson(array('status' => 'Animal não encontrado'),422);
       }

   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }

});

$app->delete('/animais/{id}', function ($request,$response) {
   try{
       $id     = $request->getAttribute('id');
       $con = $this->db;
       $sql = "DELETE FROM animal WHERE id = :id";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':id' => $id);
       $result = $pre->execute($values);
       if($result){
           return $response->withJson(array('status' => 'Animal Deletado :( '),200);
       }else{
           return $response->withJson(array('status' => 'Animal n Encontrado :('),422);
       }
     }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }

});

$app->run();
