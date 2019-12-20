<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');

class ViewController extends BaseController
{
	protected $db;
	public function __construct()
	{
			$this->db = new DbController();
	}

	// 리스트 게시판의 특정 게시물 조회
	public function getView($report_id)
  {
		$report_view = array();
		$params = array();
		$sql = "SELECT * FROM emp e, report r WHERE e.emp_id = r.emp_id AND report_id = :report_id";
		$params = array(':report_id' => $report_id);
		$report_view = $this->db->select($sql, $params);
		return $report_view;
	}

	// 답글 게시판의 특정 게시물 조회
	public function getView2($rid)
	{
		$results = array();
		$params = array();
		$sql = "SELECT * FROM rboard WHERE rid = :rid";
		$params = array(':rid' => $rid);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 댓글 게시판의 특정 게시물 조회
	public function getView3($fbid)
	{
		$results = array();
		$params = array();
		$sql = "SELECT * FROM fboard WHERE fbid = :fbid";
		$params = array(':fbid' => $fbid);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 답글 게시판 조회수
	public function updateRef($ref, $rid)
	{
		$params = array();
		$sql = "UPDATE rboard SET ref = :ref + 1 WHERE rid = :rid";
		$params = array(
			'ref' => $ref,
			'rid' => $rid
		);
		$this->db->plural($sql, $params);
	}

	// 댓글 게시판 조회수
	public function updateRef2($ref, $fbid)
	{
		$params = array();
		$sql = "UPDATE fboard SET ref = :ref + 1 WHERE fbid = :fbid";
		$params = array(
			'ref' => $ref,
			'fbid' => $fbid
		);
		$this->db->plural($sql, $params);
	}

	// 답글 조회
	public function getReply($fid, $thread)
	{
		$results = array();
		$params = array();
		$sql = "SELECT * FROM rboard where fid = :fid and thread > :thread";
		$params = array(
			':fid' => $fid,
			':thread' => $thread
		);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 답글 개수 조회
	public function getReplyCount($fid, $thread)
	{
		$sql = "SELECT count(*) FROM rboard where fid = :fid and thread > :thread";
		$params = array(
			':fid' => $fid,
			':thread' => $thread
		);
		$count = $this->db->selectCount($sql, $params);
		return $count;
	}

	// 작성자의 다른 게시물 조회
	public function getOtherPost($emp_id, $fbid)
	{
		$results = array();
		$params = array();
		$sql = "SELECT * FROM fboard WHERE emp_id= :emp_id AND fbid != :fbid";
		$params = array(
			':emp_id' => $emp_id,
			':fbid' => $fbid
		);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 등록된 댓글 조회
	public function getComment($fbid)
	{
		$results = array();
		$params = array();
		$sql = "SELECT * FROM fcomment WHERE fbid= :fbid";
		$params = array(
			':fbid' => $fbid
		);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 댓글 등록 개수
	public function getCommentCount($fbid)
	{
		$sql = "SELECT count(*) FROM fcomment WHERE fbid= :fbid";
		$params = array(
			':fbid' => $fbid
		);
		$count = $this->db->selectCount($sql, $params);
		return $count;
	}

	// 댓글 등록
	public function insertCommnet($params)
	{
		$sql = 'INSERT INTO fcomment (emp_id, comment, signdate, fbid)';
		$sql .= ' VALUES (:emp_id, :comment, :signdate, :fbid)';
		 try {
			 $this->db->beginTransaction();
			 $rtn = $this->db->plural($sql, $params);
			 $this->db->commit();
			 return $rtn;
		 } catch (Exception $e) {
			 $this->db->rollBack();
		 }
	}

	// 리스트 게시판 , 게시물 수정
  public function updateView($report_id, $title, $this_week_contents, $next_week_contents, $issue) {
    $params = array();
    $sql = "UPDATE report SET title = :title, this_week_contents = :this_week_contents, next_week_contents = :next_week_contents, issue = :issue WHERE report_id = :report_id";
		$params = array(
			'title' => $title,
			'this_week_contents' => $this_week_contents,
			'next_week_contents' => $next_week_contents,
			'issue' => $issue,
			'report_id' => $report_id
		);
    $this->db->plural($sql, $params);
  }

	// 리스트 게시판 , 게시물 승인
	public function updatePermission($report_id) {
		$params = array();
		$sql = "UPDATE report SET report_type = '1' WHERE report_id = :report_id";
		$params = array('report_id' => $report_id);
		$this->db->plural($sql, $params);
	}

	// 답글 게시판 게시물 작성자 조회
	public function get_rboard_user($rid)
	{
		$results = array();
		$params = array();
		$sql = "SELECT emp_id FROM rboard WHERE rid = :rid";
		$params = array(
			':rid' => $rid
		);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 답글 게시판 게시물 삭제
	public function deleteRboard($rid)
	{
		$params = array();
		$sql = "DELETE FROM rboard WHERE rid = :rid";
		$params = array('rid' => $rid);
		$this->db->plural($sql, $params);
	}

	// 댓글 게시판 게시물 작성자 조회
	public function get_fboard_user($fbid)
	{
		$results = array();
		$params = array();
		$sql = "SELECT emp_id FROM fboard WHERE fbid = :fbid";
		$params = array(
			':fbid' => $fbid
		);
		$results = $this->db->select($sql, $params);
		return $results;
	}

	// 댓글 게시판 게시물 삭제
	public function deletefboard($fbid)
	{
		$params = array();
		$sql = "DELETE FROM fboard WHERE fbid = :fbid";
		$params = array('fbid' => $fbid);
		$this->db->plural($sql, $params);
	}

	// 전체 댓글 삭제
	public function deleteAllComment($fbid)
	{
		$params = array();
		$sql = "DELETE FROM fcomment WHERE fbid = :fbid";
		$params = array('fbid' => $fbid);
		$this->db->plural($sql, $params);
	}

	// 특정 댓글 삭제
	public function deleteComment($fbid, $my_emp_id, $cid)
	{
		$params = array();
		$sql = "DELETE FROM fcomment WHERE fbid = :fbid AND emp_id = :emp_id AND cid = :cid";
		$params = array(
			'fbid' => $fbid,
			'emp_id' => $my_emp_id,
			'cid' => $cid
		);
		$this->db->plural($sql, $params);
	}
}

$controller = new ViewController();
$base_controller = new BaseController();
$process_mode = isset($_REQUEST['process_mode']) ? $_REQUEST['process_mode'] : '';

try {
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';

	// TODO : 内容修正
  if ($process_mode == 'update') {
		// 리스트 게시판 게시물 수정 시
    $report_id = $_GET['report_id'];
    $title = $_POST['title'];
    $this_week_contents = $_POST['this_week_contents'];
    $next_week_contents = $_POST['next_week_contents'];
    $issue = $_POST['issue'];

    $controller->updateView($report_id, $title, $this_week_contents, $next_week_contents, $issue);

		// TODO : ペイジー移動
    $to_controller_name = 'index.php';
    $params['report_id'] = $report_id;
    $params['page'] = $page;
    $params['target'] = "view";
    $loc = "view";
    $base_controller->portalHeader($to_controller_name, $params, $loc);
  } else if ($process_mode == 'permisson')
		{
			// 리스트 게시판 관리자 게시물 승인 시
			if (!isset($_POST['permissonChk']))
			{
				echo"<script>alert(\"체크된 게시물이 없습니다.\");</script>";
			} else
				{
				for ($i=0; $i<count($_POST['permissonChk']); $i++) {
					$controller->updatePermission($_POST['permissonChk'][$i]);
				}
				}
				echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=ListController&page=$page'>");
				// $to_controller_name = 'index.php';
				// $params['page'] = $page;
				// $params['target'] = "ListController";
				// $loc = "view";
				// $base_controller->portalHeader($to_controller_name, $params, $loc);
		}
		else if ($process_mode == 'com')
		{
			// 댓글 게시판 댓글 등록 시
			$comment = $_REQUEST['comment'];
			$fbid = $_REQUEST['fbid'];
			$signdate = time();

			$params['emp_id'] = $emp_id;
			$params['comment'] = $comment;
			$params['signdate'] = $signdate;
			$params['fbid'] = $fbid;

			$controller->insertCommnet($params);

			echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=view3&page=$page&fbid=$fbid'>");
		}
		else if ($process_mode == 'view2_delete')
		{
			// 답글 게시판 게시물 삭제 시
			$rid = $_GET['rid'];
			session_start();
			$my_emp_id = $base_controller->getSession('empid');
			$results = $controller->get_rboard_user($rid);

			foreach ($results as $row) {
				if ($my_emp_id == $row['emp_id'])
				{
					$controller->deleteRboard($rid);
					echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=RboardController'>");
				}
				else {
					echo"<script>alert(\"해당 게시물은 게시물의 작성자만 삭제할 수 있습니다.\");</script>";
					echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=view2&page=$page&rid=$rid'>");
				}
			}
		}
		else if ($process_mode == 'view3_delete')
		{
			// 댓글 게시판 게시물 삭제 시
			$fbid = $_GET['fbid'];
			session_start();
			$my_emp_id = $base_controller->getSession('empid');
			$results = $controller->get_fboard_user($fbid);

			foreach ($results as $row) {
				if ($my_emp_id == $row['emp_id'])
				{
					$controller->deleteFboard($fbid);
					$controller->deleteAllComment($fbid);
					echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=FboardController'>");
				}
				else {
					echo"<script>alert(\"해당 게시물은 게시물의 작성자만 삭제할 수 있습니다.\");</script>";
					echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=view3&page=$page&fbid=$fbid'>");
				}
			}
		}
		else if ($process_mode == 'com_delete')
		{
			// 댓글 게시판 댓글 삭제 시
			$fbid = $_GET['fbid'];
			$cid = $_GET['cid'];
			session_start();
			$my_emp_id = $base_controller->getSession('empid');
			$results = $controller->get_fboard_user($fbid);

			if ($my_emp_id == $emp_id)
			{
				$controller->deleteComment($fbid, $my_emp_id, $cid);
				echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=view3&page=$page&fbid=$fbid'>");
			}
			else
			{
				echo"<script>alert(\"해당 댓글은 댓글의 작성자만 삭제할 수 있습니다.\");</script>";
				echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=view3&page=$page&fbid=$fbid'>");
			}
		}
		else if ($process_mode == 'view2_modify')
		{
			// TODO : 답글 게시물, 게시물 수정
			echo "view2 Modify";
		}
		else if ($process_mode == 'view3_modify')
		{
			// TODO : 댓글 게시판, 게시물 수정
			echo "view3 Modify";
		}
} catch (Exception $e) {
  throw $e->getMessage();
}
?>
