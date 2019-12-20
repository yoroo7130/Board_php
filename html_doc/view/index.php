<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');

$base_controller = new BaseController();

try {
	$base_controller->startSession();
	$emp_id = $base_controller->getSession('empid');

	if(!isset($emp_id)){
			echo("<meta http-equiv='Refresh' content='0; URL=../view/login.php'>");
			echo"<script>alert(\"잘못된 접근입니다.\");</script>";
			exit();
	}
?>
<html><head>
<title>Home</title>
<link rel="stylesheet" href="css/animation.css" type="text/css">
<link rel="stylesheet" href="css/style.css" type="text/css">
<style>
* {
	padding: 0;
	font-family: 'Malgun gothic','Sans-Serif','Arial';
}
@media only screen and (min-width: 0px)
.footer2 {
	width: 960px;
	padding: 0;
	margin: 0 auto 0;
	white-space: nowrap;
}
.footer2 address {
    margin-left: 37%;
    float: left;
    clear: both;
    padding: 20px 0;
    text-align: center;
    font-style: normal;
    font-size: 12px;
    color: #555;
}
.footer2 .mark {
    widht: 50px;
    display: inline;
    float: right;
}
table {
  border-collapse: separate;
  border-spacing: 0 10px;
  margin:auto;
}
.fixed-top {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1030;
}
</style>
</head>
<body>
	<div class="fixed-top">
	<?php include "../js/header.js" ?>
	</div>
	<?php
	$target= isset($_REQUEST['target']) ? $_REQUEST['target'] : "home";
	$report_id = isset($_REQUEST['report_id']) ? $_REQUEST['report_id'] : "";
	if($target == "ListController")
	{
		$target = "../controller/" . $target . ".php";
	}
	else if ($target == "RboardController")
	{
		$target = "../controller/" . $target . ".php";
	}
	else if ($target == "FboardController")
	{
		$target = "../controller/" . $target . ".php";
	}
	else if ($target == "view" || $target == "view2" || $target == "view3" || $target == "replyForm")
	{
		$target .= ".php";
	} else {
		$target .= ".html";
	}
	?>
	<p><br/><p/>
	<table border = 0 height=100% width=100%>
		<tr><td class="scale-up-ver-top">
		<?php	include $target; ?>
		</td></tr>
		<tr height=10%><td align=center>
		<div class="footer2">
			<address>Copyright(c) 2014 Asia Information System Co., Ltd. All Rights Reserved.</address>
		<div class="mark">
		<table width="80" border="0" cellpadding="2" cellspacing="0" title="このマークは、ウェブサイトを安心してご利用いただける安全の証です。">
			<tbody><tr>
				<td width="80" align="center" valign="top">
					<a href="http://www.isms.jipdec.or.jp/isms.html" target="_blank" title="ISMS"><img src="images/isms_jipdec.gif" alt="Pmark Logo" width="80" height="40"></a><script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=www.ais-info.co.jp&amp;size=XS&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=ja"></script><br>
				</td>
				<td>
					<a href="http://privacymark.jp" target="_blank" title="privacymark"><img src="images/pmark.gif" alt="Pmark Logo" width="40" height="40"></a>
				</td>
				<td>
					<img src="images/iso.png" alt="QMS Logo" width="80" height="40">
				</td>
			</tr>
			</tbody></table>
		</div>
		</div>
		</td></tr>
	</table>
</body>
</html>

<?php
} catch (Exception $e) {
	throw $e->getMessage();
}
?>
