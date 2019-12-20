<page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8">

<!-- jQuery -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 -->

<script src="${pageContext.request.contextPath}/resources/common/js/jquery-3.3.1.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.min.css" rel="stylesheet"></link>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>

<!-- Bootstrap theme -->
<link rel="stylesheet" href="${pageContext.request.contextPath}/resources/bootstrap/css/bootstrap-theme.min.css">

<script>
  var popupX = 1000;
  var popupY = 20;
  function openMypage() {
      window.open('../view/mypage.php?emp_id=<?php echo $emp_id ?>', 'Mypage', 'status=no,location=no, menubar=no, resizable=no, scrollbars=no, height=100, width=350, left='+ popupX + ', top='+ popupY);
  }
</script>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample08" aria-controls="navbarsExample08" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample08">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php?target=home">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?target=ListController&emp_id=<?php echo $emp_id ?>">리스트 게시판</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?target=RboardController">답변형 게시판</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?target=FboardController">댓글 게시판</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown08" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $emp_id ?></a>
        <div class="dropdown-menu" aria-labelledby="dropdown08">
          <a class="dropdown-item" onclick=openMypage();>MyPage</a>
          <a class="dropdown-item" href="../controller/LoginController.php?process_mode=logout&emp_id=<?php echo $emp_id ?>">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
