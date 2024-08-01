<?php
// Database connection
$servername = "4e31b09b-acff-47d0-927a-244edce05073.internal.kr1.mysql.rds.nhncloudservice.com";
$username = "user"; // MySQL 사용자명
$password = "1234"; // MySQL 비밀번호
$dbname = "ticket";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// SQL query
$sql = "INSERT INTO Users (name, username, password) VALUES (?, ?, ?)";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);

// Execute the statement
if ($stmt->execute()) {
    // 회원가입 성공 시 리디렉션
    header("Location: login.html");
    exit();
} else {
    // 회원가입 실패 시 에러 메시지 출력
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
