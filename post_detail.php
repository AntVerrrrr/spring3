<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'spring';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// URL 파라미터에서 게시글 ID 가져오기
$id = isset($_GET['id']) ? $_GET['id'] : '';

// 게시글 상세 조회 쿼리
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($post = $result->fetch_assoc()) {
    // 게시글 정보 출력 (HTML 형식으로)
    echo "<h1>" . $post['title'] . "</h1>";
    echo "<p>" . $post['content'] . "</p>";
    // 추가적인 정보 출력 가능
} else {
    echo "게시글을 찾을 수 없습니다.";
}

$conn->close();
?>