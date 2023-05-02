<?php

class Controller_Operator extends Controller {

	function __construct() {
		$this->model = new Model_Operator();
		$this->view = new View();
	}

	/**
	 * @throws Exception
	 */
	function action_index() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			$data = $this->model->get_data($_SESSION['token'], null, $_SESSION['message']);
			unset($_SESSION['message']);
			$this->view->generate('view_operator.php', 'view_header.php', $data);
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	function action_find_by_passport() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			try {
				$_SESSION['client_uuid'] = $this->model->find_by_passport($_POST['passport']);
				header('Location: /client');
			} catch (Exception $exception) {
				$_SESSION['message']['client'] = $exception->getMessage();
				header('Location: /operator');
			}
		}
		else {
			throw new LogicException(403);
		}
	}

	function action_create_client() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			try {
				$this->model->create_client($_POST);
				$_SESSION['message']['create_client'] = 'Клиент успешно создан';
			} catch (Exception $exception) {
				$_SESSION['message']['create_client'] = 'Неправильный формат данных в полях:' . $exception->getMessage();
			}
			header('Location: /operator');
		}
		else {
			throw new LogicException(403);
		}
	}

}