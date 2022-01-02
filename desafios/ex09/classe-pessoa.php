<?php

class Pessoa
{
 private $pdo;
    //6 functions
    //conection com database
    //metodo construtor rece variaveis que serão usadas no cod;
    public function __construct($dbname, $host, $user, $senha)
    {
        try{
            $this-> pdo= new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);

        }
        catch(PDOException $e){
         
         echo "Erro na conexao com o banco erro: ".$e->getMessage();
         //para o restante do codigo
         exit();
        }
        catch(Exception $e){
            echo "erro generico erro: ".$e->getMessage();
            //para o restante do codigo
            exit();
        }
       
    }
     //função que busca os dados e os coloca na parte direita do site.
      public function BuscarDados()
      {
                  //estudar comand
                  //declaramos array aqui, pois caso o $res nao retorne nd la em baixo ele só mostra um valor vazio em ves de um erro
                  $res=array();
          $cmd = $this->pdo->query("SELECT * FROM PESSOA ORDER BY nome");
          $res= $cmd->fetchAll(PDO::FETCH_ASSOC);

          //retorna a informação pra quem chamar o método
          return $res;
          // fetchAll forma um array dentor de um array ent é uma matriz 
      }
      // insere a pessoa na batabase
      public function CadastrarPessoa($nome,$telefone,$email)
      {
          // select para verificar se  o email já existe
          $cmd= $this->pdo->prepare("SELECT id from pessoa where email=:e");
         
          $cmd->bindValue(":e",$email);
          $cmd->execute();
          //para pegar o numero de linhas OBS: PARECE QUE O COUNT($CMD) NÃO FUNCIONA AQUI...
          if($cmd->rowCount()>0){
              return false;
          }
          else{
              $cmd = $this->pdo->prepare("INSERT INTO PESSOA (nome,telefone,email) values (:n,:t,:e)");
              $cmd->bindValue(":n",$nome);
              $cmd->bindValue(":t",$telefone);
              $cmd->bindValue(":e",$email);
              $cmd->execute();
              return true;

          }
          
      }
      public function ExcluirPessoa($id)
      {
          $cmd= $this->pdo->prepare("Delete from pessoa where id=:id");
          $cmd->bindValue(":id",$id);
          $cmd->execute();
      }
    
    

      // Busca os dados de uma pessoa especifica
    public function BuascarDadosdeUmaPessoa($id)
      {
         $res=array(); 
       $cmd= $this->pdo->prepare("Select * from pessoa where id=:id");
       $cmd->bindValue(":id",$id);
       $cmd->execute();
       $res= $cmd->fetch(PDO::FETCH_ASSOC);
       return $res;
      }

      //atualiza os dados no banco

    public function AtualizarDados($id,$nome,$telefone,$email)  
    {        //verifica se o email já existe
       

        $cmd= $this->pdo->prepare("UPDATE pessoa SET nome=:n,telefone=:t,email=:e WHERE id=:id");
        $cmd->bindValue(":n",$nome);
        $cmd->bindValue(":t",$telefone);
        $cmd->bindValue(":e",$email);
        $cmd->bindValue(":id",$id);
        $cmd->execute();
        
        
    }
}




?>

