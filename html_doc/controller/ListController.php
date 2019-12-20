<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');

class ListController extends BaseController
{
	protected $db;
	public function __construct()
	{
		$this->db = new DbController();
	}

	// 게시물 개수 반환
	public function getCount($emp_id) {
		$sql = "SELECT count(report_id) FROM report";
		if($emp_id != "admin") {
			$sql .=  " WHERE report_type = 1 AND emp_id = :emp_id";
			$params = array(':emp_id' => $emp_id);
			$count = $this->db->selectCount($sql, $params);
		} else {
			$count = $this->db->selectCount($sql);
		}
		return $count;
	}

	// 리스트 게시판의 전체 게시물 조회
	public function getReport($emp_id, $first, $num_per_page)
	{
		$report_list = array();
		$params = array();
		$sql = "SELECT r.report_id, e.emp_id, e.emp_name, r.title, r.this_week_contents, r.next_week_contents, r.issue, r.report_date, r.report_type FROM emp e, report r WHERE e.emp_id = r.emp_id";
		if ($emp_id != "admin") {
			$sql .= " AND e.emp_id = :emp_id AND r.report_type = 1 ORDER BY r.report_id DESC LIMIT $first, $num_per_page";
			$params = array(':emp_id' => $emp_id);
		} else {
			$sql .= " ORDER BY report_type ASC, report_id DESC LIMIT $first, $num_per_page";
		}
		$report_list = $this->db->select($sql, $params);
		return $report_list;
	}
}

$controller = new ListController();
$base_controller = new BaseController();

try {
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : "";
	$num_per_page = 10;
	$page_per_block  = 3;
	if(! $page) { $page = 1; }
	$first = $num_per_page*($page-1);

	$total_record = $controller->getCount($emp_id);

	$total_page = ceil($total_record/$num_per_page);

	$report_list = $controller->getReport($emp_id, $first, $num_per_page);

	$article_num = $total_record - $num_per_page * ($page - 1);
// PDO-PHP 方式で使用方 : SELECT　
// https://phpdelusions.net/pdo_examples/select
// print_r($report_list);
?>
<html>
<!doctype html>
<head>
<link rel="stylesheet" href="../view/css/style.css" type="text/css">
    <script>
        var popX = (screen.width - 580) / 2;
        var popY = 20;
        function openPost() {
						window.open('postForm.html', 'Post', 'status=no,location=no, menubar=no, resizable=no, scrollbars=no, height=900, width=620, left='+ popX + ', top='+ popY);
        }
    </script>
</head>
<body>
<div class="display_table">
<br />
	<form method="post" action="../controller/ViewController.php?process_mode=permisson&page=<?php echo $page ?>">
    <table class="list-table" id="list">
      	<thead>
	<tr>
	<td align=center><h2>리스트</h2></td>
	<td colspan=5 align=right>
	<?php if($emp_id == "admin") { ?> <input class="btn" name="btnPermisson" type="submit" value="승인"> <?php } ?> <input class="btn" type=button value="등록" onclick="openPost()"></td>
	</tr>
          <tr align=center>
		<?php if($emp_id == "admin") { ?>
              <th width="50">승인</th><?php } ?>
                <th width="150" >아이디</th>
                <th width="150">이름</th>
                <th width="250">제목</th>
                <th width="150">등록일</th>
								<th width="100">승인여부</tr>
            </tr>
        </thead>
      <tbody>
				<?php if($total_record == 0) {
					if($emp_id == "admin") {
						echo "<tr><td colspan=6>등록된 게시물이 없습니다.</td></tr>";
					}
					else {
						echo "<tr><td colspan=5>등록된 게시물이 없습니다.</td></tr>";
					}
				}
				else { ?>
				<?php foreach ($report_list as $row) { ?>
        <tr style = "cursor:pointer;" onClick = "location.href='index.php?target=view&report_id=<?php echo $row['report_id'] ?>&page=<?php echo $page ?>'
					"onmouseover="this.style.backgroundColor='#efefef';" onmouseout="this.style.backgroundColor='#ffffff';">
          <?php if($emp_id == "admin") { ?><td width="50" onclick="event.cancelBubble=true"><input type="checkbox" name="permissonChk[]"
						value="<?php echo $row['report_id']; ?>"<?php if($row['report_type'] == 0) { echo ""; } else { echo " disabled"; } ?>></td><?php } ?>
          <td width="150"><?php echo $row['emp_id'] ?></td>
          <td width="150"><?php echo $row['emp_name'] ?></td>
          <td width="250">
					<?php
					$title = $row['title'];
					$title = $base_controller->cutStr($title, 10);
					echo $title;
					?>
					</td>
          <td width="150"><?php echo date("Y-m-d",$row['report_date']); ?></td>
					<td width="100"><?php if($row['report_type'] == 0) { echo "<font color=red>미승인</font>"; } else { echo "<font color=blue>승인</font>"; } ?></td>
        </tr>
			<?php $article_num--; } ?>
      </tbody>
    </table>
	</form>
<br />
<table border=0 align=center>
	<?php
	$total_block = ceil($total_page/$page_per_block);
	$block = ceil($page/$page_per_block);

	$first_page = ($block-1)*$page_per_block;
	$last_page = $block*$page_per_block;

	if($block >= $total_block) $last_page = $total_page;
	?>
	<tr>
	<td>
	<?php
	echo("<a href=\"index.php?target=ListController&page=1&emp_id=$emp_id\" class=\"btnPaging2\">◀◀</a>");

	if($block > 1) {
		$my_page = $first_page;
		echo "&nbsp;";
		echo("<a href=\"index.php?target=ListController&page=$my_page&emp_id=$emp_id\" class=\"btnPaging2\">◀</a>");
		echo "&nbsp;";
	}
	else {	echo "&nbsp;"; echo("<a href=\"#\" class=\"btnPaging2\">◀</a>"); echo "&nbsp;"; }

	for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
		if($page == $direct_page) {
			echo "&nbsp;";
			echo("<a href=\"#\" class=\"btnPaging\">$direct_page</a>");
			echo "&nbsp;";
		} else { echo "&nbsp;"; echo("<a href=\"index.php?target=ListController&page=$direct_page&emp_id=$emp_id\" class=\"btnPaging\">$direct_page</a>"); echo "&nbsp;"; }
	}

	if($block < $total_block) {
		$my_page = $last_page + 1;
		echo "&nbsp;";
		echo("<a href=\"index.php?target=ListController&page=$my_page&emp_id=$emp_id\" class=\"btnPaging2\">▶</a>");
		echo "&nbsp;";
	}
	else { echo "&nbsp;"; echo("<a href=\"#\" class=\"btnPaging2\">▶</a>"); echo "&nbsp;"; }

	echo("<a href=\"index.php?target=ListController&page=$total_page&emp_id=$emp_id\" class=\"btnPaging2\">▶▶</a>");
	?>
	<?php } ?>
	</td>
	</tr>
	</table>
</div>
</body>
</html>

<?php
} catch (Exception $e) {
	throw $e->getMessage();
}
?>
