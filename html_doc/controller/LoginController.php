<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');

class LoginController extends BaseController
{
	protected $db;
	public function __construct()
	{
		$this->db = new DbController();
	}

	// 최근 접속 날짜와 아이피 수정
	public function updateEmp($lastconnect_ip, $lastconnect_date, $emp_id)
	{
		$params = array();
    $sql = "UPDATE emp SET lastconnect_ip = :lastconnect_ip, lastconnect_date = :lastconnect_date WHERE emp_id = :emp_id";
		$params = array(
			'lastconnect_ip' => $lastconnect_ip,
			'lastconnect_date' => $lastconnect_date,
			'emp_id' => $emp_id
		);
    $this->db->plural($sql, $params);
	}
}

$controller = new LoginController();
$base_controller = new BaseController();
$process_mode = isset($_REQUEST['process_mode']) ? $_REQUEST['process_mode'] : '';

try {
// error_log('domain_name[' . DOMAIN_NAME . ']');
	if ($process_mode == 'login') {
		// ID、PASSWORD確認
		$emp_id = strtolower($_POST['emp_id']);
		//$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$password = strtolower($_POST['password']);
		$emp_info = $base_controller->getEmpInfo($emp_id);

		// 로그인 실패 시
		if (count($emp_info) < 1) {
			echo("<meta http-equiv='Refresh' content='0; URL=../view/login.php?b_id=0'>");
			echo"<script>alert(\"시스템에 등록되지 않는 아이디입니다.\");</script>";
			exit();
		} elseif (count($emp_info) == 1) {
			//if (password_verify($password, $emp_info[0]['password'])) {
			if ($password == $emp_info[0]['password']) {
				// 로그인 성공시 메인 화면으로 이동
				// 報告一覧画面へ遷移
					$base_controller->startSession();
					$base_controller->setSession('empid', $emp_id);
					header('Location: http://' . DOMAIN_NAME . '/view/index.php?target=home');
					// echo"<script>alert(\"ログイン成功。\");</script>";
					// echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=home'>");
					// $to_controller_name = 'index.php';
					// $params['emp_id'] = $emp_id;
					// $loc = "view";
					// $base_controller->portalHeader($to_controller_name, $params, $loc);
			} else {
				// 패스워드가 일치하지 않는 경우
				echo("<meta http-equiv='Refresh' content='0; URL=../view/login.php?join=0&b_pwd=0&my_id=$emp_id'>");
				echo"<script>alert(\"패스워드가 일치하지 않습니다.\");</script>";
				exit();
			}
		}
	} elseif ($process_mode == 'logout') {
		// 로그아웃
		$emp_id = $_GET['emp_id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$controller->updateEmp($ip, time(), $emp_id);
		$base_controller->startSession();
		$base_controller->destroySession();
		header('Location: http://' . DOMAIN_NAME . '/view/login.php');
		// echo("<meta http-equiv='Refresh' content='0; URL=../view/login.php'>");
		// echo"<script>alert(\"ログアウトされました。\");</script>";
	} elseif ($process_mode == 'passwor_forget') {
		echo "password forget";
	} elseif ($process_mode == 'confirm') {
	} else {
		echo $process_mode;
	}

} catch (Exception $e) {
	throw $e->getMessage();
}
?>
