<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$nota = Container::getModel('Cliente');

		$get = isset($_GET['p']) ? $_GET['p'] : '';

		$nota->__set('id_usuario', $_COOKIE['id']);

		$nota->__set('titulo', $get);

		$notas = $nota->getNota();

		$this->view->notas = $notas;
	
		$this->render('index');
	}

	public function cliente() {
		$this->render('cliente');
	}
	public function registrar() {
		//receber dados do formulario
			//fazendo instancia do usuario com a conexão com o banco
			$usuario = Container::getModel('Cliente');
	
			$usuario->__set('nome', $_POST['nome']);
			$usuario->__set('email', $_POST['email']);
			//aplicando cripitografia de ponta nas senhas dos usuarios  
			$usuario->__set('senha', md5($_POST['senha']));
	
			//se o metodo validarCadastro() retornar true ele salva no banco de dados e verificando com base em uma consulta de emails pelo banco de dados se já não exite um usuario registrado com esse email, caso não ele salva no banco
			if($usuario->validarCadastro())  {  
				
				//verificando se o email já não existe
			  if(count($usuario->getUsuarioPorEmail()) == 0) {
				  //sucesso
				  $usuario->salvar(); 
	
				  //caso o rseultado seja positivo para as verificações ele executa uma renderização da página cadastro.phtml
				 header('Location: /cliente');
			  } else {
				//caso o email já exista direcionado ele para index com o email como parametro para ser futuramente recuperado
				header('Location: /cliente?e='. $_POST['email'] );
			  }                           
			   
			} else {
			   //caso as verificações sejam negativas ele mnatem os dados nos campos para uma possivel correção do usuario
			   $this->view->usuario = array(
				 'nome' => $_POST['nome'],
				 'email' => $_POST['email'],
				 'senha' => $_POST['senha'],
			   );
	
			   //caso o rseultado seja negativo para as verificações ele executa uma renderização da página inscreverse.phtml
			   $this->view->erroCadastro = true;
	
			   header('Location: /?registro=erro');
			}
			
	   }
  
	   //este metodo verifica se o usuario tem cadastro no banco
	   public function autenticar() {
		//a class Container instanica o modelo e a conexão com o banco de dados
		$usuario = Container::getModel('Cliente');


		//setando o modelo com as informações digitadas pelo usuario
		$usuario->__set('email', $_POST['email']);
		//convertendo senha cripitografada para senha normal que fara a consulta no banco de dados
		$usuario->__set('senha', md5($_POST['senha']));
  
		//verificando se os dados digitados pelo usuario existem no banco de dados
		$usuario->autenticar();
  
		//se os campos id e nome tiverem prenchidos sginifa que a consulta do banco de dados retornou um registro, e sgnifica que a conta existe  
		if(!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))) {
  
		  //Criando os cookies
		  setcookie('id', $usuario->__get('id'), time() + 390000);

		  setcookie('nome', $usuario->__get('nome'), time() + 390000);

		  setcookie('email', $usuario->__get('email'), time() + 390000);
  
		  header('Location: /');
		  
		} else  {
		  header('Location: /cliente?login=erro');
		}

	}
  
	public function validarAutenticacao() {
  
	  //protegendo as paginas para apenas os usuarios logados
	   if(!isset($_COOKIE['id']) || $_COOKIE['id'] == '' || !isset($_COOKIE['nome']) || $_COOKIE['nome'] == '') {
		 //caso as verificações sejam negativas o usuario voltara para a pagina inicial
	   header('Location: /cliente?login=erro');          
	   }
	}

	public function nota() {
		$new = Container::getModel('Cliente');

		$new->__set('id_usuario', strip_tags($_COOKIE['id']));
		$new->__set('nota', strip_tags($_POST['nota']));
		$new->__set('titulo', htmlentities($_POST['titulo']));
		$new->__set('prioridade', strip_tags($_POST['prioridade']));

		$new->setNota();

		header('Location: /');
	}

	public function atualizar() {
		$att = Container::getModel('Cliente');

		$att->__set('nota', $_POST['nota']);
		$att->__set('id', $_POST['id']);

		$att->attNota();

		header('Location: /');
		

	}

	public function deletar() {
		$del = Container::getModel('Cliente');

		$del->__set('id', $_GET['id']);
		$del->deletar();

		header('Location: /');
	}
}


?>