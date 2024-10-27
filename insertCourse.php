<?php
require_once('cors.php');
require_once('dbcon.php');


$request_data = json_decode(file_get_contents('php://input'));

if (!isset($request_data->course_id)) {
    http_response_code(400);
    $response_json = array(
        'statusCode' => 400,
        'error' => '필수 입력 값이 누락되었습니다.'
    );
    echo json_encode($response_json, JSON_UNESCAPED_UNICODE);
    return;
}

$course_id = $request_data->course_id;

$course_select_query = sql_query("SELECT * FROM courses WHERE id = $course_id");

if ($course_select_query->num_rows === 0) {
    http_response_code(404);
    $response_json = array(
        'statusCode' => 404,
        'error' => '해당 course가 존재하지 않습니다.'
    );
    echo json_encode($response_json, JSON_UNESCAPED_UNICODE);
    return;
}

// 학생 ID가 1인 학생의 course_id 컬럼에 해당하는 모든 코스 정보를 가져옴
$student_id = 1;
$insert_course_query = sql_query("INSERT INTO student_courses (student_id, course_id) VALUES ($student_id, $course_id)");

if (!$insert_course_query) {
    http_response_code(500);
    $response_json = array(
        'statusCode' => 500,
        'error' => '코스를 학생에게 추가하는 도중 오류가 발생했습니다.'
    );
    echo json_encode($response_json, JSON_UNESCAPED_UNICODE);
    return;
}

http_response_code(200);
$response_json = array(
    'statusCode' => 200,
    'message' => '코스가 학생에게 성공적으로 추가되었습니다.'
);
echo json_encode($response_json, JSON_UNESCAPED_UNICODE);
return;
?>
