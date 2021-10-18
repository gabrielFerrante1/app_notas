<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['cliente'] = array(
			'route' => '/cliente',
			'controller' => 'indexController',
			'action' => 'cliente'
		);

		$routes['registro'] = array(
			'route' => '/registroDb',
			'controller' => 'indexController',
			'action' => 'registrar'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticarDb',
			'controller' => 'indexController',
			'action' => 'autenticar'
		);

		$routes['Newnota'] = array(
			'route' => '/nota',
			'controller' => 'indexController',
			'action' => 'nota'
		);

		$routes['atualizar'] = array(
			'route' => '/atualizar',
			'controller' => 'indexController',
			'action' => 'atualizar'
		);

		$routes['deletar'] = array(
			'route' => '/deletar',
			'controller' => 'indexController',
			'action' => 'deletar'
		);
		$this->setRoutes($routes);
	}

}

?>