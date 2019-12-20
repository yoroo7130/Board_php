<html><head>
<title>Login</title>
<link rel="stylesheet" href="css/animation.css" type="text/css">
<link rel="stylesheet" href="css/style.css" type="text/css">
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;width:400;height:250px;
	text-align:center;vertical-align:middle;align:center;font-weight:bold;margin:auto}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:none;border-width:1px;overflow:hidden;word-break:normal;border-color:black}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:none;borderwidth:1px;overflow:hidden;word-break:normal;border-color:black}
.tg .tg-0lax{text-align:center;vertical-align:middle;font-size:15px;}
.input0{width:300px;height:40px}
.input1{
	width:300px;height:40px;
	outline: none !important;
	border:1px solid red;
	box-shadow: 0 0 10px #719ECE;
}
.display_table { display:table; width:100%; height:100%}
.display_table_cell { display:table-cell; text-align:center; vertical-align:middle;horizontal-align:middle}
</style>
</head>
<body class="slide-in-top">
	<?php
		require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
		require_once(DOCUMENT_ROOT . '/controller/DbController.php');
		$base_controller = new BaseController();
		$base_controller->startSession();
		$emp_id = $base_controller->getSession('empid');

		if(isset($emp_id))
			echo("<meta http-equiv='Refresh' content='0; URL=../view/index.php?target=home'>");

		$join= isset($_REQUEST['join']) ? $_REQUEST['join'] : "";
		$my_id = isset($_REQUEST['$my_id']) ? $_REQUEST['$my_id'] : "";
		$b_id= isset($_REQUEST['$b_id']) ? $_REQUEST['$b_id'] : "";
		$b_pwd= isset($_REQUEST['$b_pwd']) ? $_REQUEST['$b_pwd'] : "";
	?>
	<div class="display_table">
		<div class="display_table_cell">
	<form method="post" action="../controller/LoginController.php">
	<table class="tg" border=0>
 	 <tr>
  	  <th class="tg-0lax" colspan="2"><font color="blue" size="8px" ><p class="text-shadow-pop-tr" style="font-size:50px;font-weight:bold;">YUHAN POTAL</p></font></th>
 	 </tr>
	  <tr height=50>
	    <td class="tg-0lax">
		<?php if ($b_id == NULL) { ?>
            	<input class="input0" type=text name="emp_id" placeholder="아이디" required="true" autofocus="autofocus" value="<?php echo $my_id ?>">
		<?php } else { ?>
		<input class="input1" type=text name="emp_id" placeholder="아이디" required="true" autofocus="autofocus">
		<?php } ?>
	    <td class="tg-0lax" rowspan="2" width=100><input class="loginBtn" type="submit" value="로그인"></td>
	  </tr>
	  <tr height=50>
		<td class="tg-0lax">
		<?php if ($b_pwd == NULL) { ?>
	    	<input class="input0" type=password name="password" placeholder="비밀번호" required="true" autofocus="autofocus">
		<?php } else { ?>
		<input class="input1" type=password name="password" placeholder="비밀번호" required="true" autofocus="autofocus">
		<?php } ?>
		</td>
	  </tr>
	  <tr>
	    <td class="tg-0lax" colspan="2">
	    <?php if($join != NULL){ ?>
				<font color="red"><p />비밀번호를 잊으셨습니까?</font> <a href="../controller/LoginController.php?process_mode=passwor_forget">&nbsp;<font color=blue>비밀번호 찾기</font></a>
	    <?php } ?>
	    </td>
	  </tr>
	</table>
	<input type="hidden" name="process_mode" value="login">
	</form>
		</div>
	</div>
</body>
</html>
