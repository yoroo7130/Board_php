<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/const.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');

class BaseController
{
	protected $db;
	public function __construct()
	{
		$this->db = new DbController();
	}

	// 스트링, 길이, 변환 값
	// 스트링을 입력받고 입력된 길이만큼 자르고 나머지를 특정 값으로 변환하는 함수
	// 게시물의 제목을 특정 길이보다 크면 제목의 뒤를 자르기 위해 사용
	public function cutStr($str, $len, $tail = "...", $Encording = "UTF-8") {
		$strlen = iconv_strlen($str, $Encording);
		if($strlen <= $len) {
			return $str;
		}
		else {
			$str = mb_substr($str, 0, $len);
			$str .= $tail;
			return $str;
		}
	}

	public function portalHeader($to_controller_name, $params = array(), $loc)
	{
		$get_params = '';
		if (count($params) > 0) {
			$get_params = '?';
			foreach ($params as $key => $value) {
				$get_params .= $key . '=' . $value . '&';
			}
		}
		header('Location: http://' . DOMAIN_NAME . '/' . $loc . '/' . $to_controller_name . $get_params);
		exit;
	}

	// 회원 정보 조회
	public function getEmpInfo($emp_id = null)
	{
		$emp_list = array();
		$params = array();
		$sql = "SELECT emp_id, emp_name, password, gender, division_code, joined_yyyymmdd, tel, email_id_company, authority, status, lastconnect_ip, lastconnect_date FROM emp WHERE delete_flag = '0'";
		if (!is_null($emp_id)) {
			$sql .= ' AND emp_id = :emp_id';
			$params = array(':emp_id' => $emp_id);
		}
		$emp_list = $this->db->select($sql, $params);
		return $emp_list;
	}

	public function getBeginningWeekDate()
	{
		$today = $_SERVER['REQUEST_TIME'];
		$w     = (date('w', $today) + 6) % 7;
		$from  = date('Y/m/d', $today - 86400 * $w);
		$to    = date('Y/m/d', $today + 86400 * (6 - $w));

		return array($from, $to);
	}

	// 세션 시작
	public function startSession()
	{
		//if (session_status() === PHP_SESSION_NONE)
		//session_save_path(DIRECTORY_SEPARATOR . 'tmp');
		ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
		ini_set('session.gc_maxlifetime', 1800);
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 100);
		session_start();
	}

	// logout時
	// 로그아웃
	public function destroySession()
	{
		session_unset();
		session_destroy();
	}

	// 세션 설정
	public function setSession($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	// 세션 반환
	public function getSession($key)
	{
		if (isset($_SESSION[$key])) return $_SESSION[$key];
	}

	// 세션 삭제
	public function unSession($keys)
	{
		if (is_array($keys)) {
			foreach ($keys as $key => $value) {
				unset($_SESSION[$key]);
			}
		} else {
			unset($_SESSION[$keys]);
		}
	}
}
?>
