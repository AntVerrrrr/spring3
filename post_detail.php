<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>게시글 상세보기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            height: 80%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 200px;
        }

        #imagePreview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>


<body>
    <div class="container">
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
            // 게시글 정보 출력
            echo "<h2>상세글 보기</h2>";
            echo "<br>";
            echo "<div class='form-group'><h3> 제목: ". htmlspecialchars($post['title']) . "</h3></div>";
            echo "<div class='form-group'><p> 내용: " . nl2br(htmlspecialchars($post['content'])) . "</p></div>";
            
            // 이미지가 있다면 이미지 출력
            if ($post['image_path']) {
                echo "<div class='form-group'><img src='" . htmlspecialchars($post['image_path']) . "' alt='게시글 이미지' style='max-width:100%; height:auto;' /></div>";
            }

            // 삭제 버튼
            echo "<form method='POST' action='delete_post.php'>";
            echo "<input type='hidden' name='id' value='" . $post['id'] . "'>";
            echo "<button type='submit' onclick='return confirm(\"정말로 삭제하시겠습니까?\");'>삭제하기</button>";
            echo "</form>";
            
        } else {
            echo "게시글을 찾을 수 없습니다.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>