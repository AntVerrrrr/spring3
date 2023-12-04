<?php
session_start();
session_destroy(); // 모든 세션 변수 삭제
echo json_encode(['success' => true]);
?>