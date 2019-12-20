<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
    require_once(DOCUMENT_ROOT . '/controller/DbController.php');

		$base_controller = new BaseController();
    $emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id'] : "";

    $emp_info = $base_controller->getEmpInfo($emp_id);
    foreach ($emp_info as $row) {
      $name = $row['emp_name'];
      $lastconnect_ip = $row['lastconnect_ip'];
      $lastconnect_date = date("Y-m-d H:i:s",$row['lastconnect_date']);
    }
?>

<html><head><title>마이페이지</title>
</head>
<body>
  <table border=0 align=center>
    <tr><td align=left>이름</td><td align=right><?php echo $name ?><td></tr>
    <tr><td align=left>최근 접속한 아이피</td><td align=right><?php echo $lastconnect_ip ?><td></tr>
    <tr><td align=left>최근 접속한 시간</td><td align=right><?php echo $lastconnect_date ?><td></tr>
  </table>
</body>
</html>
