<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');
require_once(DOCUMENT_ROOT . '/controller/ViewController.php');

$base_controller = new BaseController();
$view_controller = new ViewController();

try {
	$page = $_REQUEST['page'];
	$report_id = $_REQUEST['report_id'];

	$report_view = $view_controller->getView($report_id);
	foreach ($report_view as $row) {
		$my_id = $row['emp_id'];
		$my_name = $row['emp_name'];
		$my_title = $row['title'];
		$report_date = $row['report_date'];
		$report_type = $row['report_type'];
		$this_week_contents = $row['this_week_contents'];
		$next_week_contents = $row['next_week_contents'];
		$issue = $row['issue'];
	}
?>
﻿<html><head>
<title>View</title>
<link rel="stylesheet" href="css/animation.css" type="text/css">
<link rel="stylesheet" href="css/style.css" type="text/css">
<style type="text/css">
.display_table { display:table; width:100%; height:100%}
.display_table_cell { display:table-cell; text-align:center;vertical-align:middle;horizontal-align:middle}
.tg  {border-collapse:collapse;border-spacing:0;width:550px;height:700px;
	text-align:center;vertical-align:middle;font-weight:bold;margin:auto;border-style:solid;border-width:1px;}
.tg td{font-family:Arial, sans-serif;font-size:18px;padding:10px;5px;overflow:hidden;word-break:normal;border-color:black}
.tg th{font-family:Arial, sans-serif;font-size:18px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;border-color:black}
.tg .tg-0lax{text-align:center;vertical-align:middle}
.tg .tg-1lax{text-align:left;vertical-align:middle}
</style>
</head>
<body>
	<br/>
<div id="rotate-scale-up-hor">
	<div class="display_table">
		<div class="display_table_cell">
		<form method="post" action="../controller/ViewController.php?report_id=<?php echo $report_id ?>&process_mode=update&page=<?php echo $page ?>">
			<table class="tg">
			  <tr height="5%">
    			<th class="tg-1lax" colspan="2"><font size=4>&nbsp; 업무 내용</font></th>
			  </tr>
			  <tr height="5%">
			    <td class="tg-1lax">제목</td>
			    <td class="tg-0lax"><input type="text" name="title" style="width:600px;height:40px" required="true" autofocus="autofocus" value="<?php echo $my_title ?>"></td>
			  </tr>
			  <tr height="5%">
			    <td class="tg-1lax" colspan="2">금주의 업무 기록</td>
			  </tr>
			  <tr height="10%">
			    <td class="tg-0lax" colspan="2"><textarea name="this_week_contents" cols="80" rows="10" autofocus required wrap="hard"><?php echo $this_week_contents ?></textarea></td>
			  </tr>
			  <tr height="5%">
			    <td class="tg-1lax" colspan="2">차주의 업무 계획</td>
			  </tr>
			  <tr height="10%">
			    <td class="tg-0lax" colspan="2"><textarea name="next_week_contents" cols="80" rows="10" autofocus required wrap="hard"><?php echo $next_week_contents ?></textarea></td>
			  </tr>
				<tr height="5%">
			    <td class="tg-1lax" colspan="2">건의사항</td>
			  </tr>
			  <tr height="10%">
					<td class="tg-0lax" colspan="2"><textarea name="issue" cols="80" rows="10" autofocus required wrap="hard"><?php echo $issue ?></textarea></td>
			  </tr>
			  <tr height="5%">
			    <td class="tg-0lax" colspan="2"><input class="cancleBtn" type="submit" value="수정">
				&nbsp; <input class="postBtn" type="button" value="목록" onClick = "location.href='index.php?target=ListController&page=<?php echo $page ?>'"></td>
			  </tr>
			</table>
		</form>
		</div>
	</div>
</div>
</body>
</html>
<?php
} catch (Exception $e) {
	throw $e->getMessage();
}
?>
