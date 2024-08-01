<?php
session_start(); // 세션 시작

// 데이터베이스 연결
$servername = "4e31b09b-acff-47d0-927a-244edce05073.internal.kr1.mysql.rds.nhncloudservice.com";
$username = "user"; // MySQL 사용자명
$password = "1234"; // MySQL 비밀번호
$dbname = "ticket";

// 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // 사용자 이름(이메일 주소)
    $password = $_POST['password'];

    // 사용자 정보 조회
    $sql = "SELECT id, name, password FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        // 비밀번호 검증
        if (password_verify($password, $hashed_password)) {
            // 로그인 성공 시 세션에 사용자 정보 저장
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: welcome.php"); // 로그인 후 리디렉션
            exit();
        } else {
            echo "잘못된 비밀번호입니다.";
        }
    } else {
        echo "등록된 이메일이 없습니다.";
    }

    $stmt->close();
}

$conn->close();
?>
