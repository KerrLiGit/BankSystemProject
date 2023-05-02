<?php

class Controller_Client extends Controller {

	public function __construct() {
		$this->model = new Model_Client();
		$this->view = new View();
	}

	/**
	 * @throws Exception
	 */
	public function action_index($id = null) {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$data = $this->model->get_data($_SESSION['token'], $_SESSION['client_uuid'], $_SESSION['message']);
				unset($_SESSION['message']);
			} catch (Exception $exception) {
				$data['message']['error'] = 'Нет доступа к серверу' . $exception->getMessage();
			}
			$this->view->generate('view_client.php', 'view_header.php', $data);
		}
		else {
			throw new LogicException(403);
		}
	}

	public function action_edit() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->edit($_POST);
				$_SESSION['message']['edit'] = 'Данные успешно обновлены';
			} catch (Exception $exception) {
				$_SESSION['message']['edit'] = 'Неправильный формат данных в полях: ' . $exception->getMessage();
			}
			header('Location: /client#edit_client');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_create_account() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->create_account($_POST['client_uuid'], $_POST['currency']);
				$_SESSION['message']['create_account'] = 'Счет успешно создан';
			} catch (Exception $exception) {
				$_SESSION['message']['create_account'] = 'Ошибка при создании счета: ' . $exception->getMessage();
			}
			header('Location: /client#create_account');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_close_account() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->close_account($_POST['account_number']);
				$_SESSION['message']['close_account'] = 'Счет успешно закрыт';
			} catch (Exception $exception) {
				$_SESSION['message']['close_account'] = 'Ошибка при закрытии счета: ' . $exception->getMessage();
			}
			header('Location: /client#close_account');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_push_account() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->push_account($_POST['credit_account_number'], $_POST['sum']);
				$_SESSION['message']['push_account'] = 'Счет успешно пополнен';
			} catch (Exception $exception) {
				$_SESSION['message']['push_account'] = 'Ошибка при пополнении счета: ' . $exception->getMessage();
			}
			header('Location: /client#push_account');
		}
		else {
			throw new Exception(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_pop_account() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->pop_account($_POST['debit_account_number'], $_POST['sum']);
				$_SESSION['message']['pop_account'] = 'Средства успешно сняты';
			} catch (Exception $exception) {
				$_SESSION['message']['pop_account'] = 'Ошибка при снятии средств: ' . $exception->getMessage();
			}
			header('Location: /client#pop_account');
		}
		else {
			throw new Exception(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_transaction_in() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->transaction_in($_POST['debit_account_number'],
					$_POST['credit_account_number'], $_POST['sum']);
				$_SESSION['message']['transaction_in'] = 'Успешный перевод';
			} catch (Exception $exception) {
				$_SESSION['message']['transaction_in'] = 'Ошибка при переводе: ' . $exception->getMessage();
			}
			header('Location: /client#transaction_in');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_transaction_out() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->transaction_out($_SESSION['client_uuid'], $_POST['debit_account_number'],
					$_POST['credit_phone'], $_POST['sum']);
				$_SESSION['message']['transaction_out'] = 'Успешный перевод';
			} catch (Exception $exception) {
				$_SESSION['message']['transaction_out'] = 'Ошибка при переводе: ' . $exception->getMessage();
			}
			header('Location: /client#transaction_out');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_open_deposit() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->open_deposit($_SESSION['client_uuid'], $_POST['type'],
					$_POST['debit_account_number'], $_POST['sum']);
				$_SESSION['message']['open_deposit'] = 'Вклад успешно открыт';
			} catch (Exception $exception) {
				$_SESSION['message']['open_deposit'] = 'Ошибка при открытии вклада: ' . $exception->getMessage();
			}
			header('Location: /client#open_deposit');
		}
		else {
			throw new Exception(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_close_deposit() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->close_deposit($_POST['deposit_id'], $_POST['account_number']);
				$_SESSION['message']['close_deposit'] = 'Вклад успешно закрыт';
			} catch (Exception $exception) {
				$_SESSION['message']['close_deposit'] = 'Ошибка при закрытии вклада: ' . $exception->getMessage();
			}
			header('Location: /client#close_deposit');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_open_credit() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->open_credit($_SESSION['client_uuid'], $_POST['type'], $_POST['sum']);
				$_SESSION['message']['open_credit'] = 'Кредит успешно выдан';
			} catch (Exception $exception) {
				$_SESSION['message']['open_credit'] = 'Ошибка при выдаче кредита: ' . $exception->getMessage();
			}
			header('Location: /client#open_credit');
		}
		else {
			throw new LogicException(403);
		}
	}

	/**
	 * @throws Exception
	 */
	public function action_close_credit() {
		Session::safe_session_start();
		$user = Session::auth($_SESSION['token']);
		if ($user['role'] == 'admin' || $user['role'] == 'operator') {
			Session::check_client($_SESSION['client_uuid']);
			try {
				$this->model->close_credit($_POST['credit_id']);
				$_SESSION['message']['close_credit'] = 'Кредит успешно погашен и закрыт';
			} catch (Exception $exception) {
				$_SESSION['message']['close_credit'] = 'Ошибка при закрытии кредита: ' . $exception->getMessage();
			}
			header('Location: /client#close_credit');
		}
		else {
			throw new LogicException(403);
		}
	}
}