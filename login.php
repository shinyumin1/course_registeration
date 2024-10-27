<?php
require_once('cors.php');
require_once('dbcon.php');

// GET 요청으로부터 아이디와 비밀번호 가져오기
$userName = $_GET['userName'];
$userPassword = $_GET['userPassword'];

// student 테이블에서 아이디 확인
$sql = "SELECT * FROM student WHERE student_number='$userName'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 해당 아이디가 존재하는 경우
    $row = $result->fetch_assoc();
    $storedPasswordHash = $row['password']; // 데이터베이스에 저장된 해싱된 비밀번호

    // 비밀번호 일치 여부 확인
    if (password_verify($userPassword, $storedPasswordHash)) {
        // 로그인 성공
        echo json_encode(array("success" => true));
    } else {
        // 비밀번호 불일치
        echo json_encode(array("success" => false));
    }
} else {
    // 해당 아이디가 존재하지 않는 경우
    echo json_encode(array("success" => false));
}

// MySQL 연결 종료
$conn->close();
?>
