<?php

class Controller_Auth extends Controller {

	function __construct() {
		$this->model = new Model_Auth();
		$this->view = new View();
	}

	/**
	 * @throws Exception
	 */
	function action_index() {
		Session::safe_session_start();
		$data = $this->model->get_data(null, null, $_SESSION['message']);
		unset($_SESSION['message']);
		$this->view->generate('', 'view_auth.php', $data);
	}

	/**
	 * @throws Exception
	 */
	function action_signin() {
		Session::safe_session_start();
		try {
			$this->model->signin($_POST['login'], $_POST['pass']);
		}
		catch (LogicException $exception) {
			$_SESSION['message']['auth'] = 'Неверный пароль: вход не выполнен';
			header('Location: /auth');
		}
	}

	/**
	 * @throws Exception
	 */
	function action_signout() {
		$this->model->signout();
	}

}