// Get the modal
// 이거 박으로 클릭하면 창이 없어지는 모션
var modal = document.getElementById('id02');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


// document.addEventListener('DOMContentLoaded', function() {
//     var loginForm = document.getElementById('loginForm');

//     loginForm.addEventListener('submit', function(event) {
//         event.preventDefault(); // 폼의 기본 제출 동작 방지

//         var formData = new FormData(loginForm);
//         fetch('/path/to/login_handler.php', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 // 비밀번호가 맞으면 메인 페이지로 리디렉션
//                 window.location.href = '/mainpage.html';
//             } else {
//                 // 비밀번호가 틀리면 새 창에서 오류 메시지 표시
//                 window.open('/error_page.html?error=' + encodeURIComponent(data.error), '_blank');
//             }
//         })
//         .catch(error => console.error('Error:', error));
//     });
// });
