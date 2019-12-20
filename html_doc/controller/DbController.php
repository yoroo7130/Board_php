<?php
class DbController
{
	protected $_connection = null;
	protected $last_idx;

	public function __construct()
	{

	}

	public function __destruct()
	{
		$this->close();
	}

	/*
	 * データベースに接続する
	 */

	 // 데이터 베이스에 연결
	public function connect()
	{
		// 既に接続している場合はリターン
		// 이미 연결되 있는 경우 리턴
		if ($this->isConnected()) {
			return;
		}

		// DSNの作成
		// DSN 작성
		$db_name = 'yuhan';
		$db_host = 'localhost';
		$db_port = 3306;
		$db_user = 'root';
		$db_password = '1429';
		$dsn = 'mysql:dbname=' . $db_name . ';host=' . $db_host . ';port=' . $db_port;

		try {
			$this->_connection = new PDO($dsn, $db_user, $db_password);
		} catch (PDOException $e) {
			throw $e->getMessage();
		}
	}

	/**
	 * 既に接続しているか確認する
	 */
	 // 이미 접속되있는지 확인
	public function isConnected()
	{
		return ((bool)($this->_connection instanceof PDO));
	}

	/**
	 * データベールから切断する
	 */
	 // 데이터 베이스 연결 해제
	public function close()
	{
		$this->_connection = null;
	}

	/**
	 * トランザクションを開始する
	 */
	 // 트랜잭션 시작
	public function beginTransaction()
	{
		$this->connect();
		$this->_connection->beginTransaction();
		return $this;
	}

	/**
	 * コミットする
	 */
	 // 데이터 커밋
	public function commit()
	{
		$this->connect();
		$this->_connection->commit();
		return $this;
	}

	/**
	 * ロールバックする
	 */
	 // 데이터 롤백
	public function rollback()
	{
		$this->connect();
		$this->_connection->rollBack();
		return $this;
	}

	/**
	 * MySQL 接続リソースを返す
	 */
	 // MySQL 접속 리소스 반환
	public function getConnection()
	{
		$this->connect();
		return $this->_connection;
	}

	/**
	 * SELECTの時
	 */
	 // SELECT 문 사용 시
	public function select($sql, $params = array())
	{
		$this->connect();
		$stmt = $this->_connection->prepare($sql);

		$cnt = is_null($params) ? 0 : count($params);

		if ($cnt > 0) {
			$_temp_array = array();
			foreach ($params as $key => $value) {
				$_temp_array[$key] = $value;
			}
			$stmt->execute($_temp_array);
		} else {
			$stmt->execute();
		}
		$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $items;
	}

	// COLUMN COUNTを求める場合
	// SELECT 시, 컬럼의 개수를 구할 때
	public function selectCount($sql, $params = array())
	{
		$this->connect();
		$stmt = $this->_connection->prepare($sql);

		$cnt = is_null($params) ? 0 : count($params);

		if ($cnt > 0) {
			$_temp_array = array();
			foreach ($params as $key => $value) {
				$_temp_array[$key] = $value;
			}
			$stmt->execute($_temp_array);
		} else {
			$stmt->execute();
		}
		$count = $stmt->fetchColumn();
		return $count;
	}

	/**
	 * INSERT, UPDATE, DELETEの時
	 */
	 // INSERT, UPDATE, DELETE 문 사용 시
	public function plural($sql, $params = array())
	{
		$this->connect();
		$stmt = $this->_connection->prepare($sql);
//error_log('param_size[' . count($params) . ']');
		if (count($params) > 0) {
			foreach ($params as $key => $value) {
				if (is_int($value)) {
					$data_type = PDO::PARAM_INT;
				} elseif (is_bool($value)) {
					$data_type = PDO::PARAM_BOOL;
				} elseif (is_null($value)) {
					$data_type = PDO::PARAM_NULL;
				} elseif (is_string($value)) {
					$data_type = PDO::PARAM_STR;
				} else {
					$data_type = FALSE;
				}

				if ($data_type) {
					$stmt->bindValue(":$key", $value, $data_type);
				}
//error_log('param[' . $value . '] data_type[' . $data_type . ']');
			}
		}

		$stmt->execute();
		return $stmt;
	}


	/**
	 * 最後に挿入された行の ID あるいはシーケンスの値を返す
	 */
	 // 마지막에 삽입된 행의 아이디 혹은 시퀀스 값 반환
	public function getLastInsertId()
	{
		$this->connect();
		$this->last_idx = $this->_connection->lastInsertId();
		return $this->last_idx;
	}
}

?>
