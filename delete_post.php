<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'spring';

// 데이터베이스 연결
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // 게시글 삭제 쿼리
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('게시글이 삭제되었습니다.'); window.location.href='http://localhost/spring3/';</script>";
    } else {
        echo "<script>alert('삭제에 실패했습니다.'); window.history.back();</script>";
    }
}

$conn->close();
?>