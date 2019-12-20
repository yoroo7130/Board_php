<?php
  $rid = $_GET['rid'];
  $fid = $_GET['fid'];
  $thread = $_GET['thread'];
  $page = $_GET['page'];
?>

<html><head>
<title>PostForm</title>
<link rel="stylesheet" href="css/animation.css" type="text/css">
<link rel="stylesheet" href="css/style.css" type="text/css">
<style type="text/css">
.display_table { display:table; width:100%; height:100%}
.display_table_cell { display:table-cell; text-align:center;vertical-align:middle;horizontal-align:middle}
.tg  {border-collapse:collapse;border-spacing:0;width:550px;height:700px;
	text-align:center;vertical-align:middle;font-weight:bold;margin:auto;border-style:solid;border-width:0px;}
.tg td{font-family:Arial, sans-serif;font-size:18px;padding:10px;5px;overflow:hidden;word-break:normal;border-color:black}
.tg th{font-family:Arial, sans-serif;font-size:18px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;border-color:black}
.tg .tg-0lax{text-align:center;vertical-align:middle}
.tg .tg-1lax{text-align:left;vertical-align:middle}
</style>
</head>
<body>
  <br/>
<div class="slide-in-fwd-center">
	<div class="display_table">
		<div class="display_table_cell">
		<form method="post" action="../controller/RegistController.php?rid=<?php echo $rid ?>&register_mode=reply&fid=<?php echo $fid ?>&thread=<?php echo $thread ?>&page=<?php echo $page ?>">
			<table class="tg">
			  <tr height="5%">
    			<th class="tg-1lax" colspan="2"><font size=4>답글 작성</font></th>
			  </tr>
			  <tr height="10%">
			    <td class="tg-1lax">제목</td>
			    <td class="tg-0lax"><input type="text" name="subject" style="width:450px;height:40px" required="true" autofocus="autofocus"></td>
			  </tr>
			  <tr height="5%">
			    <td class="tg-1lax" colspan="2">내용</td>
			  </tr>
			  <tr height="10%">
			    <td class="tg-0lax" colspan="2"><textarea name="comment" cols="80" rows="30" autofocus required wrap="hard"></textarea></td>
			  </tr>
			  <tr height="5%">
			    <td class="tg-0lax" colspan="2"><input class="postBtn" type="submit" value="등록"></td>
			  </tr>
			</table>
		</form>
		</div>
	</div>
</div>
</body>
</html>
