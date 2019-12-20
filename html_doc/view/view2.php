<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');
require_once(DOCUMENT_ROOT . '/controller/ViewController.php');

$base_controller = new BaseController();
$view_controller = new ViewController();

try {
  $page = $_REQUEST['page'];
	$rid = $_REQUEST['rid'];
  $emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : '';

  $results = $view_controller->getView2($rid);

  foreach ($results as $row) {
    $my_name = $row["emp_id"];
    $my_subject = $row["subject"];
    $my_comment = $row["comment"];
    $my_comment = htmlspecialchars($my_comment);
    $my_comment = nl2br($my_comment);
    $my_signdate = date("Y年m月d日 H:i:s", $row['signdate']);
    $my_ref = $row['ref'];
    $my_fid = $row['fid'];
    $my_thread = $row['thread'];
  }

  $view_controller->updateRef($my_ref, $rid);
?>

<html>
<head></head>
<body>
<table border = 1 width = 600>
<tr><td colspan = 2 align = center><font color = blue><b><?php echo("$my_subject") ?></b></font></td></tr>
<tr><td align = center width = 20%><b>작성자</b></td><td><?php echo("$my_name") ?></td></tr>
<tr><td align = center width = 20%><b>등록일</b></td><td><?php echo("$my_signdate") ?></td></tr>
<tr><td colspan = 2><?php echo("$my_comment") ?></td></tr>
<tr><td colspan = 2 align = right>
<?php
echo("
  <a href=\"index.php?target=RboardController&page=$page\">목록</a> &nbsp; &nbsp;
	<a href=\"index.php?target=replyForm&rid=$rid&register_mode=reply&fid=$my_fid&thread=$my_thread&page=$page\">답글</a> &nbsp; &nbsp;
	<a href=\"../controller/ViewController.php?rid=$rid&process_mode=view2_modify&page=$page\">수정</a> &nbsp; &nbsp;
	<a href=\"../controller/ViewController.php?rid=$rid&process_mode=view2_delete&page=$page\">삭제</a> &nbsp; &nbsp;
");
?>
</td></tr>
</table>

<?php
  $re_results = $view_controller->getReply($my_fid, $my_thread);

  $row = $view_controller->getReplyCount($my_fid, $my_thread);
	if ($row == 0) {
		echo "<p />";
		echo "<center>등록된 답글이 없습니다.</center>";
		echo "<p />";
		exit();
	}
?>

<p />
<hr>
<p />
<table border= 1 width = 600>
	<tr align=center><td colspan=3>답글</td></tr>
	<tr align=center><td>작성자</td><td>제목</td><td>등록일</td></tr>

<?php
  foreach ($re_results as $row) {
    $re_rid = $row["rid"];
    $re_name = $row["emp_id"];
    $re_subject = $row["subject"];
    $re_signdate = date("y년m월d일h시m분s초", $row['signdate']);
    $re_thread = $row['thread'];

		echo("<tr>");
			echo("<td align=center>$re_name</td>");
			echo("<td>");
				$sp = strlen($re_thread) - 1;
				for($j = 0; $j < $sp; $j++) {
					echo("↳");
				}
			echo("<a href=\"index.php?target=view2&page=$page&rid=$re_rid\">$re_subject</a></td>");
			echo("<td align=center>$re_signdate</td>");
		echo("</tr>");
	}

?>
</table>
</body>
</html>
<?php
} catch (Exception $e) {
	throw $e->getMessage();
}
?>
