<?php

class Controller_Graph extends Controller {

	public function __construct() {
		$this->model = new Model_Graph();
		$this->view = new View();
	}

	/**
	 * @throws Exception
	 */
	public function action_index() {
		Session::safe_session_start();
		Session::auth($_SESSION['token']);
		Session::check_client($_SESSION['client_uuid']);
		try {
			$data = $this->model->get_data($_SESSION['token'], $_SESSION['client_uuid'], $_POST['credit_id']);
			$this->view->generate('view_graph.php', 'view_header.php', $data);
		}
		catch (Exception $exception) {
			$_SESSION['message']['graph_credit'] = 'Информация по кредиту не найдена: ' . $exception->getMessage();
			header('Location: /client#graph_credit');
		}
	}
}