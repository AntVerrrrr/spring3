<?php
session_start(); // 세션 시작

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userId = $_SESSION['user_id']; // 현재 로그인한 사용자의 ID

    // 파일 업로드 처리
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        $imagePath = $uploadFile;

        // 데이터베이스 연결
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'spring';

        $conn = new mysqli($servername, $username, $password, $dbname);

        // 연결 확인
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL 쿼리 작성 및 실행 (Prepared Statement 사용)
        $sql = "INSERT INTO posts (title, content, image_path, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $imagePath, $userId);

        if ($stmt->execute()) {
            echo "게시글이 성공적으로 작성되었습니다.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // 데이터베이스 연결 종료
        $stmt->close();
        $conn->close();

        // 페이지 리다이렉트
        header('Location: http://localhost/spring3/');
        exit;
    } else {
        echo '파일 업로드에 실패했습니다.';
    }
} else {
    echo '잘못된 접근입니다.';
}
?>