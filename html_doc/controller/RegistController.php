<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/DbController.php');

class RegistController extends BaseController
{
	protected $db;
	public function __construct()
	{
		$this->db = new DbController();
	}

	// 리스트 게시판의 게시물 등록
	public function setReport($params)
	{
		$sql = 'INSERT INTO report(emp_id, title, this_week_contents, next_week_contents, issue, report_date)';
		$sql .= ' VALUES(:emp_id, :title, :this_week_contents, :next_week_contents, :issue, :report_date)';

		try {
			$this->db->beginTransaction();
			$rtn = $this->db->plural($sql, $params);
			$this->db->commit();
			return $rtn;
		} catch (Exception $e) {
			$this->db->rollback();
		}
	}

	// 답글 게시판의 게시물 등록
	public function insertRboard($params)
	{
		$sql = 'INSERT INTO rboard(fid, emp_id, subject, comment, signdate, ref, thread)';
		$sql .= ' VALUES(:fid, :emp_id, :subject, :comment, :signdate, :ref, :thread)';

		 try {
			 $this->db->beginTransaction();
			 $rtn = $this->db->plural($sql, $params);
			 $this->db->commit();
			 return $rtn;
 		 } catch (Exception $e) {
			 $this->db->rollBack();
		 }
	}

	// 댓글 게시판의 게시물 등록
	public function insertFboard($params)
	{
		$sql = 'INSERT INTO fboard (emp_id, subject, comment, signdate, ref)';
		$sql .= ' VALUES (:emp_id, :subject, :comment, :signdate, :ref)';
		 try {
			 $this->db->beginTransaction();
			 $rtn = $this->db->plural($sql, $params);
			 $this->db->commit();
			 return $rtn;
		 } catch (Exception $e) {
			 $this->db->rollBack();
		 }
	}

	// 답글 등록
	public function insertReply($params)
	{
		$sql = 'INSERT INTO rboard (fid, emp_id, subject, comment, signdate, ref, thread)';
		$sql .= ' VALUES (:fid, :emp_id, :subject, :comment, :signdate, :ref, :thread)';
		try {
			$this->db->beginTransaction();
			$rtn = $this->db->plural($sql, $params);
			$this->db->commit();
			return $rtn;
		} catch (Exception $e) {
			$this->db->rollBack();
		}
	}

	// 답글 게시판, index 값을 리턴
	public function getFid()
	{
		$query = "SELECT max(fid) fid FROM rboard";
		$result = $this->db->select($query);
		return $result;
	}

	public function getLastInsertId()
	{
		return $this->db->getLastInsertId();
	}

	// 답글 게시판, Thread 값 리턴
	public function getThread($fid, $thread)
	{
		$results = array();
		$sql = "SELECT thread, right(thread, 1) FROM rboard WHERE fid = :fid AND length(thread) = length(:thread) + 1 AND locate(:thread, thread) = 1 order by thread DESC LIMIT 1";

		$params = array (
			'fid' => $fid,
			'thread' => $thread
		);

		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 답글 게시판, Thread 개수 리턴
	public function getThreadCount($fid, $thread)
	{
		$sql = "SELECT thread, right(thread, 1) FROM rboard WHERE fid = :fid AND length(thread) = length(:thread) + 1 AND locate(:thread, thread) = 1 order by thread DESC LIMIT 1";
		$params = array (
			':fid' => $fid,
			':thread' => $thread
		);

		$count = $this->db->selectCount($sql, $params);
		return $count;
	}
}

$controller = new RegistController();
$base_controller = new BaseController();

try {
	$base_controller->startSession();
	$emp_id = $base_controller->getSession('empid');
	$register_mode = isset($_REQUEST['register_mode']) ? $_REQUEST['register_mode'] : '';

	if ($register_mode == 'view1'){
		// 値設定
		$title = $_POST['title'];
		$this_week_contents = $_POST['this_week_contents'];
		$next_week_contents = $_POST['next_week_contents'];
		$issue = $_POST['issue'];
		$signdate = time();

		// report登録
		$params['emp_id'] = $emp_id;
		$params['title'] = $title;
		$params['this_week_contents'] = $this_week_contents;
		$params['next_week_contents'] = $next_week_contents;
		$params['issue'] = $issue;
		$params['report_date'] = $signdate;
		$controller->setReport($params);

		$idx = $controller->getLastInsertId();
		error_log('ide[' . $idx . ']');

		// TODO : メイルを送る関数
		// $to = "yoroo7130@naver.com";
		// $subject = "PHP メイル 発送";
		// $contents = "PHP メイル 発送 テスト";
		// $headers = "From: admin@ais-info.co.jp\r\n";
		// $headers .= "Cc: admin@ais-info.co.jp\r\n";
		//
		// mail($to, $subject, $contents, $headers);

		echo "<script> opener.location.reload(); </script>";
		echo "<script> self.close(); </script>";
	}
	else if ($register_mode == 'view2')
	{
		$results = $controller->getFid();
		foreach ($results as $row) {
			$max_fid = $row['fid'];
		}

		if($max_fid) $new_fid = $max_fid + 1; else $new_fid = 1;

		$subject = $_POST['subject'];
		$comment = $_POST['comment'];
		$signdate = time();
		$ref = 0;
		$thread = 'A';

		$params['fid'] = $new_fid;
		$params['emp_id'] = $emp_id;
		$params['subject'] = $subject;
		$params['comment'] = $comment;
		$params['signdate'] = $signdate;
		$params['ref'] = $ref;
		$params['thread'] = $thread;

		$controller->insertRboard($params);

		echo "<script> opener.location.reload(); </script>";
		echo "<script> self.close(); </script>";
	}
	else if ($register_mode == 'view3')
	{
		$subject = $_POST['subject'];
		$comment = $_POST['comment'];
		$ref = 0;
		$signdate = time();

		$params['emp_id'] = $emp_id;
		$params['subject'] = $subject;
		$params['comment'] = $comment;
		$params['ref'] = $ref;
		$params['signdate'] = $signdate;

		$controller->insertFboard($params);

		echo "<script> opener.location.reload(); </script>";
		echo "<script> self.close(); </script>";
	}
	else if ($register_mode == 'reply')
	{
		$rid = $_GET['rid'];
		$fid = $_GET['fid'];
		$thread = $_GET['thread'];
		$page = $_GET['page'];

		$subject = $_POST['subject'];
		$comment = $_POST['comment'];

		$count = $controller->getThreadCount($fid, $thread);
		$results = $controller->getThread($fid, $thread);

		// 답글 등록을 위한 Thread 값 설정 알고리즘
		if($count) {
			foreach ($results as $row) {
				$my_thread = $row[0];
				$right = $row[1];
			}
			$t_head = substr($my_thread, 0, -1);
			$t_foot = ++$right;
			$new_thread = $t_head . $t_foot;
		}
		else
		{
			$new_thread = $thread . "A";
		}

		$signdate = time();
		$ref = 0;

		$params['fid'] = $fid;
		$params['emp_id'] = $emp_id;
		$params['subject'] = $subject;
		$params['comment'] = $comment;
		$params['signdate'] = $signdate;
		$params['ref'] = $ref;
		$params['thread'] = $new_thread;

		$controller->insertReply($params);

		echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=view2&page=$page&rid=$rid'>");
	}
} catch (Exception $e) {
	throw $e->getMessage();
}
?>
