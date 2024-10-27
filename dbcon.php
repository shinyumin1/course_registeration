<?php
$host = 'localhost';
$user = 'root';
$pw = '0328';
$db_name = 'sugang';

$mysqli = new mysqli($host, $user, $pw, $db_name); //db 연결

// 연결 상태 확인
if ($mysqli->connect_error) {
    die("데이터베이스 연결에 실패했습니다: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");

function sql_fetch_array($result) {
    return $result->fetch_array(MYSQLI_ASSOC);
}

function sql_query($sql){
    global $mysqli;

   $sql = trim($sql);
    return $mysqli->query($sql);
}

// SQL 쿼리를 준비하고 실행하는 함수 정의
function sql_prepare_query($query, $params, $mysqli) {
    // 쿼리 준비
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die("SQL 쿼리 준비에 실패했습니다: " . $mysqli->error);
    }

    // 파라미터를 바인딩
    if (!empty($params)) {
        $types = '';
        $bindParams = [];

        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i'; // 정수형
            } elseif (is_double($param)) {
                $types .= 'd'; // 부동 소수점형
            } else {
                $types .= 's'; // 문자열형
            }
        }

        $bindParams[] = $types;
        foreach ($params as &$param) {
            $bindParams[] = &$param;
        }

        // 바인딩된 파라미터와 쿼리 실행
        if (!empty($bindParams)) {
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
        }
    }

    // 쿼리 실행
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result;
    } else {
        die("SQL 쿼리 실행에 실패했습니다: " . $stmt->error);
    }
}

// 배열의 값에 대한 참조를 반환하는 함수
function refValues($arr) {
    if (strnatcmp(phpversion(), '5.3') >= 0) { // PHP 버전이 5.3 이상인 경우
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }
    return $arr;
}

?>
