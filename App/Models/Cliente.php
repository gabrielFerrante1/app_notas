<?php 

namespace App\Models;

//classe que conecta com banco de dados nossos modelos
use MF\Model\Model;

class Cliente extends Model {

    private $id_usuario;
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $nota;
    private $prioridade;
    private $titulo;

    //funções para a manipulação de todos os atributos do objeto
    public function __get($atributo) {
    	return $this->$atributo;
    }

    public function __set($atributo, $valor) {
    	$this->$atributo = $valor;
    }

    //salvar dados do usuario no banco
    public function salvar() {
    	$query = "insert into usuarios(nome, email, senha)values(:nome, :email, :senha)";
        //acessando a conexao com o banco de dados via classe Model que foi extendida para está classe  
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':nome', $this->__get('nome'));
    	$stmt->bindValue(':email', $this->__get('email'));
    	$stmt->bindValue(':senha', $this->__get('senha'));
    	$stmt->execute();

    	return $this;
    }

    //validar se um cadastro pode ser feito
    public function validarCadastro() {
    	$valido = true;

    	if(strlen($this->__get('nome')) < 3) {
    		$valido = false;
    	}

    	if(strlen($this->__get('email')) < 5) {
    		$valido = false;
    	}

    	if(strlen($this->__get('senha')) < 3) {
    		$valido = false;
      }

    	return $valido;
    }

    //recuperar um usuario por email para verificar se já não existe
    public function getUsuarioPorEmail() {
    	$query = "select nome, email from usuarios where email = :email";
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':email', $this->__get('email'));
    	$stmt->execute();

    	return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function autenticar() {
        $query = "select id, nome, email from usuarios where email = :email and senha = :senha;";
 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
 
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        //verificando se os dados não são vazios, se nao forem vazios ele prenche com os dados do formulario front-end
        if(!empty($usuario['id']) && !empty($usuario['nome'])) {
           $this->__set('id', $usuario['id']);
           $this->__set('nome', $usuario['nome']);
        };
 
        return $this;
    }

    public function setNota() {
    	$query = "insert into tb_notas(id_usuario, nota, prioridade, titulo)values(:id_usuario, :nota, :prioridade, :titulo)";
        //acessando a conexao com o banco de dados via classe Model que foi extendida para está classe  
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    	$stmt->bindValue(':nota', $this->__get('nota'));
    	$stmt->bindValue(':prioridade', $this->__get('prioridade'));
        $stmt->bindValue(':titulo', $this->__get('titulo'));
    	$stmt->execute();

    	return $this;
    }

    public function getNota() {
    	$query = "select *, DATE_FORMAT(data, '%d/%m/%Y') as data from tb_notas where id_usuario = :id_usuario AND titulo like :titulo order by prioridade desc";
        //acessando a conexao com o banco de dados via classe Model que foi extendida para está classe  
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':titulo', '%'.$this->__get('titulo').'%');
    	$stmt->execute();

    	return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function attNota() {
    	$query = "update tb_notas set nota = :nota where id = :id";
        //acessando a conexao com o banco de dados via classe Model que foi extendida para está classe  
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':id', $this->__get('id'));
    	$stmt->bindValue(':nota', $this->__get('nota'));
    	$stmt->execute();

    	return $this;
    }

    public function deletar() {
    	$query = "delete from tb_notas  where id = :id";
        //acessando a conexao com o banco de dados via classe Model que foi extendida para está classe  
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':id', $this->__get('id'));
    	$stmt->execute();

    	return $this;
    }

}//Finalização da class
?>