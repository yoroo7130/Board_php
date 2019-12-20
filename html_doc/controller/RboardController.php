<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BaseController.php');
require_once(DOCUMENT_ROOT . '/controller/DbController.php');

class RboardController extends BaseController
{
  protected $db;
  public function __construct()
  {
    $this->db = new DbController();
  }

  // 검색 기능을 위한 WHERE 문 분기
  public function getWhere($keyword, $word)
  {
    switch ($keyword) {
      case 'all':
        $where = "WHERE rid LIKE '%$word%' OR subject LIKE '%$word%' OR emp_id LIKE '%$word%'";
        break;
      default:
        $where = "WHERE $keyword LIKE '%$word%'";
        break;
    }
    return $where;
  }

  // 게시판 리스트 개수를 검색 결과에 따른 분기
  public function getCount($keyword, $word)
  {
    switch ($keyword) {
      case NULL:
        $sql = "SELECT count(rid) FROM rboard";
        $count = $this->db->selectCount($sql);
        break;
      case 'all':
        $sql = "SELECT count(rid) FROM rboard WHERE rid like '%$word%' or subject like '%$word%' or emp_id like '%$word%'";
        $count = $this->db->selectCount($sql);
        break;
      default:
        $sql = "SELECT count(rid) FROM rboard WHERE $keyword like '%$word%'";
        $count = $this->db->selectCount($sql);
        break;
    }
    return $count;
  }

  // 리스트 조회를 위해 검색 결과에 따른 분기
  public function getResults($keyword, $where, $first, $num_per_page)
  {
    $results = array();
    switch ($keyword) {
      case NULL:
        $query = "SELECT * FROM rboard ORDER BY fid DESC, thread ASC LIMIT $first, $num_per_page";
        $results = $this->db->select($query);
        break;

      default:
        $query = "SELECT * FROM rboard $where ORDER BY fid DESC, thread ASC LIMIT $first, $num_per_page";
        $results = $this->db->select($query);
        break;
    }
    return $results;
  }
}

$controller = new RboardController();
$base_controller = new BaseController();

try {
  $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : "";
  $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : "";
  $word = isset($_REQUEST['word']) ? $_REQUEST['word'] : "";

  $num_per_page = 10;
  $page_per_block  = 3;

  if(! $page) { $page = 1; }

  $where = $controller->getWhere($keyword, $word);
  $total_record = $controller->getCount($keyword, $word);

  $total_page = ceil($total_record/$num_per_page);
  $first = $num_per_page*($page-1);

  $results = $controller->getResults($keyword, $where, $first, $num_per_page);

  $article_num = $total_record - $num_per_page * ($page - 1);
?>
<html>
<!doctype html>
<head>
<link rel="stylesheet" href="../view/css/style.css" type="text/css">
<script>
    var popX = (screen.width - 580) / 2;
    var popY = 20;
    function openPost() {
        window.open('postForm2.html', 'Post', 'status=no,location=no, menubar=no, resizable=no, scrollbars=no, height=750, width=620, left='+ popX + ', top='+ popY);
    }
</script>
</head>
<body>

<div class="display_table">
  <br />
  <table class="list-table" id="list">
      <thead>
        <tr>
      	<td align=center><h2>답변형 게시판</h2></td>
        <td align=center colspan=3>
          <form method = "post">
            <select name = "keyword">
              <option value = "all">전체</option>
              <option value = "rid">번호</option>
              <option value = "subject">제목</option>
              <option value = "emp_id">작성자</option>
            </select>
            <input type = "text" name = "word" size = "40">
            <input class="btnPaging" type = "submit" value = "검색">
          </form>
        </td>
        <td align=right colspan=2>
          <input class="btn" type=button value="등록" onclick="openPost()"></td>
      	</tr>
        <tr align=center>
              <th width="50">번호</th>
              <th width="300">제목</th>
              <th width="100">작성자</th>
              <th width="200">등록일</th>
              <th width="80">조회수</th>
              <th width="80">답글</tr>
          </tr>
      </thead>
      <tbody>
        <?php
        if($total_record == 0) {
          	echo("<tr><td colspan=6>등록된 게시물이 없습니다.</td></tr>");
        }
        ?>
        <?php foreach ($results as $row) { ?>
          <tr style = "cursor:pointer;" onClick = "location.href='index.php?target=view2&rid=<?php echo $row['rid'] ?>&page=<?php echo $page ?>'
            "onmouseover="this.style.backgroundColor='#efefef';" onmouseout="this.style.backgroundColor='#ffffff';">
            <td width="50"><?php echo $row['rid'] ?></td>
            <td width="300"><?php
            $sp = strlen($row['thread']) - 1;
            for($j = 0; $j < $sp; $j++) {
              echo("↳");
            }
            echo $base_controller->cutStr($row['subject'], 10);
            ?></td>
            <td width="100"><?php echo $row['emp_id'] ?></td>
            <td width="200"><?php echo date("Y-m-d", $row['signdate']); ?></td>
            <td width="80"><?php echo $row['ref'] ?></td>
            <td width="80"><?php echo $row['thread'] ?></td>
          </tr>
          <?php $article_num--; } ?>
      </tbody>
    </table>
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
  	echo("<a href=\"index.php?target=RboardController&page=1&keyword=$keyword&word=$word\" class=\"btnPaging2\">◀◀</a>");

  	if($block > 1) {
  		$my_page = $first_page;
  		echo "&nbsp;";
  		echo("<a href=\"index.php?target=RboardController&page=$my_page&keyword=$keyword&word=$word\" class=\"btnPaging2\">◀</a>");
  		echo "&nbsp;";
  	}
  	else {	echo "&nbsp;"; echo("<a href=\"#\" class=\"btnPaging2\">◀</a>"); echo "&nbsp;"; }

  	for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
  		if($page == $direct_page) {
  			echo "&nbsp;";
  			echo("<a href=\"#\" class=\"btnPaging\">$direct_page</a>");
  			echo "&nbsp;";
  		} else { echo "&nbsp;"; echo("<a href=\"index.php?target=RboardController&page=$direct_page&keyword=$keyword&word=$word\" class=\"btnPaging\">$direct_page</a>"); echo "&nbsp;"; }
  	}

  	if($block < $total_block) {
  		$my_page = $last_page + 1;
  		echo "&nbsp;";
  		echo("<a href=\"index.php?target=RboardController&page=$my_page&keyword=$keyword&word=$word\" class=\"btnPaging2\">▶</a>");
  		echo "&nbsp;";
  	}
  	else { echo "&nbsp;"; echo("<a href=\"#\" class=\"btnPaging2\">▶</a>"); echo "&nbsp;"; }

  	echo("<a href=\"index.php?target=RboardController&page=$total_page&keyword=$keyword&word=$word\" class=\"btnPaging2\">▶▶</a>");
  	?>
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
