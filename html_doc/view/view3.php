<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');
require_once(DOCUMENT_ROOT . '/controller/ViewController.php');

$base_controller = new BaseController();
$view_controller = new ViewController();

try {
	$page = $_REQUEST['page'];
	$fbid = $_REQUEST['fbid'];

	$results = $view_controller->getView3($fbid);

	foreach ($results as $row) {
		$userid = $row["emp_id"];
		$my_subject = $row["subject"];
		$my_comment = $row["comment"];
		$my_comment = htmlspecialchars($my_comment);
		$my_comment = nl2br($my_comment);
		$my_signdate = date("Y年m月d日 H:i:s", $row['signdate']);
		$my_ref = $row['ref'];
	}
	$view_controller->updateRef2($my_ref, $fbid);
?>

<html>
<head></head>
<body>
<table border = 1 width = 650>
<tr><td colspan = 2 align = center><font color = blue><b><?php echo("$my_subject") ?></b></font></td></tr>
<tr><td align = center width = 20%><b>작성자</b></td><td><?php echo("$userid") ?></td></tr>
<tr><td align = center width = 20%><b>등록일</b></td><td><?php echo("$my_signdate") ?></td></tr>
<tr><td colspan = 2><?php echo("$my_comment") ?></td></tr>
<tr><td colspan = 2 align = right>
<?php
echo("
	<a href=\"index.php?target=FboardController&page=$page\">목록</a> &nbsp; &nbsp;
	<a href=\"../controller/ViewController.php?fbid=$fbid&process_mode=view3_modify&page=$page\">수정</a> &nbsp; &nbsp;
	<a href=\"../controller/ViewController.php?fbid=$fbid&process_mode=view3_delete&page=$page\">삭제</a> &nbsp; &nbsp;
");
?>
</td></tr>
</table>

<?php
	$re_results = $view_controller->getOtherPost($userid, $fbid);
?>

<p />
<hr>
<p />
<table border= 1 width = 650>
	<tr align=center><td colspan=3>작성자가 등록한 게시물</td></tr>
	<tr align=center><td>작성자</td><td>제목</td><td>등록일</td></tr>
<?php
foreach ($re_results as $row) {
	$re_fbid = $row["fbid"];
	$re_userid = $row["emp_id"];
	$re_subject = $row["subject"];
	$re_signdate = date("y년m월d일h", $row['signdate']);

	echo("<tr>");
		echo("<td align=center>$re_userid</td>");
		echo("<td align=center><a href=\"index.php?target=view3&page=$page&fbid=$re_fbid\">$re_subject</a></td>");
		echo("<td align=center>$re_signdate</td>");
	echo("</tr>");
}
?>
</table>

<p />
<hr>
<p />

<?php
	$c_results = $view_controller->getComment($fbid);
	$c_num = $view_controller->getCommentCount($fbid);
?>

<form name=commentform method=post action="../controller/ViewController.php?fbid=<?php echo $fbid ?>&process_mode=com&emp_id=<?php echo $_SESSION['empid']; ?>">
<table border=0 width = 650>
	<tr><td align=left colspan=2><?php echo $c_num ?>개의 댓글이 있습니다.</td></tr>
	<tr><td align=left colspan=2><?php echo $_SESSION['empid']; ?> </td></td></tr>
	<tr><td width = 550 align=center><textarea name="comment" cols=80 rows=7></textarea></td>
	<td><input type=submit class=btn value="등록" style="width:50pt;height:110pt"></td>
	</tr>
</table>

<?php
	if($c_num > 0) {
?>
	<table width=650 border=0>
<?php
	foreach ($c_results as $c_row) {
		$c_userid = $c_row['emp_id'];
		$c_comment = $c_row['comment'];
		$c_no = $c_row['cid'];
		$c_signdate = date("y년m월d일 H:i:s", $c_row['signdate']);
?>
			<p />
			<tr><td align=left style="border-style:solid;border-width:1px;"><?php echo "$c_userid" ?></td></tr>
			<tr><td align=right style="border-style:solid;border-width:1px;border-bottom:none;border-top:none;">
				<input type=button value="X" class=btnPaging onclick="location.href='../controller/ViewController.php?fbid=<?php echo "$fbid" ?>&emp_id=<?php echo "$c_userid" ?>&cid=<?php echo "$c_no" ?>&process_mode=com_delete'">
				&nbsp;<?php echo "$c_comment" ?></td></tr>
			<tr><td align=right style="border-style:solid;border-width:1px;border-top:none;"><?php echo "$c_signdate" ?></td></tr>
			<tr><td><p/></td></tr>
<?php
		}
?>
	</table>
<?php } else {
	echo "<p />";
	echo "<center>등록된 댓글이 없습니다.</center>";
	echo "<p />";
}?>
</form>
</body>
</html>
<?php
} catch (Exception $e) {
	throw $e->getMessage();
}
?>
