// 세션 확인
// 페이지 로드 시 실행
document.addEventListener('DOMContentLoaded', function() {
  checkLoginStatus();

  // 기존 코드...
});

function checkLoginStatus() {
  fetch('check_login_status.php')
  .then(response => response.json())
  .then(data => {
      if (data.loggedIn) {
          document.getElementById('loginButton').style.display = 'none';
          document.getElementById('signupButton').style.display = 'none';
          document.getElementById('logoutButton').style.display = 'block';
          document.getElementById('username').textContent = data.username;
          document.getElementById('welcomeMessage').style.display = 'block';
      } else {
          document.getElementById('loginButton').style.display = 'block';
          document.getElementById('signupButton').style.display = 'block';
          document.getElementById('logoutButton').style.display = 'none';
          document.getElementById('welcomeMessage').style.display = 'none';
      }
  })
  .catch(error => console.error('Error:', error));
}
// ----------------------------------------------------------------------------------
// 로그아웃
function logout() {
  fetch('logout.php', {
      method: 'POST'
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          console.log("로그아웃 성공");

          // 로그아웃 버튼 숨기기
          document.getElementById('logoutButton').style.display = 'none';

          // 로그인 및 회원가입 버튼 다시 표시
          document.getElementById('loginButton').style.display = 'block';
          document.getElementById('signupButton').style.display = 'block';

          // 페이지 리디렉션 또는 새로고침
          window.location.href = 'index.html';
      } else {
          alert("로그아웃 실패");
      }
  })
  .catch(error => console.error('Error:', error));
}

// -----------------------------------------------------------------------
// 아이디 체크 비동기
function check_id() {
  window.open("member_check_id.php?id=" + document.member_form.id.value,
      "IDcheck",
       "left=700,top=300,width=350,height=200,scrollbars=no,resizable=yes");
}

// 비밀번호 중복체크
document.addEventListener('DOMContentLoaded', function() {
  var signupForm = document.querySelector('[name="member_form"]'); // 회원가입 폼의 name을 사용하여 선택

  signupForm.addEventListener('submit', function(event) {
      var password = document.querySelector('[name="pass"]').value; // 비밀번호 필드
      var confirmPassword = document.querySelector('[name="pass_confirm"]').value; // 비밀번호 확인 필드

      if (password !== confirmPassword) {
          event.preventDefault(); // 폼 제출을 중단
          alert('비밀번호가 일치하지 않습니다.'); // 사용자에게 경고
      }
  });
});

// signup 비동기 처리
function submitSignupForm(event) {
  event.preventDefault(); // 폼의 기본 제출 동작 방지

  var formData = new FormData(document.getElementsByName('member_form')[0]);

  fetch('member_insert.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          // 회원가입 성공 시
          document.getElementById('id01').style.display = 'none'; // 모달 닫기
          window.location.href = 'http://localhost/spring3/'; // 페이지 리디렉션
      } else {
          // 에러 메시지 표시
          alert(data.error);
      }
  })
  .catch(error => console.error('Error:', error));
}
// ----------------------------------------------------------------------------------------
// 로그인 비동기 
function submitLoginForm(event) {
  event.preventDefault();

  var formData = new FormData(document.getElementsByName('login_form')[0]);

    fetch('login_handler.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
          console.log("로그인 성공");
          // 로그인 및 회원가입 버튼 숨기기
          document.getElementById('loginButton').style.display = 'none';
          document.getElementById('signupButton').style.display = 'none';
          // 모달 닫기
          document.getElementById('id02').style.display = 'none';

          // 사용자 이름과 환영 메시지 표시
          document.getElementById('username').textContent = data.name;
          document.getElementById('welcomeMessage').style.display = 'block';
          document.getElementById('logoutButton').style.display = 'block';

          // // 사용자 이름 표시
          // var usernameDisplay = document.getElementById('usernameDisplay');
          // usernameDisplay.textContent = data.name; // 서버로부터 받은 이름
          // usernameDisplay.style.display = 'block';
          // // window.location.href = 'http://localhost/spring3/';
      } else {
          console.log("로그인 실패");
          alert("로그인 실패: 아이디나 비밀번호를 확인해 주세요.");
      }
  })
  .catch(error => console.error('Error:', error));


 
  //   .then(data => {
  //       if (data.success) {
  //       // 로그인 성공 시의 처리
  //       console.log("로그인 성공");
  //       // 예: 사용자를 메인 페이지로 리디렉션
  //       window.location.href = 'http://localhost:8013/spring3/';
  //     } else {
  //       // 로그인 실패 시의 처리
  //       console.log("로그인 실패");
  //       alert("로그인 실패: 아이디나 비밀번호를 확인해 주세요.");
  //     }
  // })
  // .catch(error => console.error('Error:', error));
  // // 오류걸려서 로그 장깐 받음 
  //     console.log(text);  // 서버 응답 확인
  //     return JSON.parse(text);  // JSON으로 파싱
  // })
  // .then(data => {
  //     // 로그인 처리
  // })
}


// ---------------------------------------------------------------------------------------------
function displayPosts(posts) {
  var postList = document.getElementById("postList");
  postList.innerHTML = ''; // 기존 목록을 비웁니다.

  posts.forEach(function(post) {
      var listItem = document.createElement("li");
      var date = new Date(post.created_at).toLocaleDateString(); // 날짜 형식 변환
      var contentPreview = post.content.substring(0, 100) + '...'; // 내용의 첫 100자

      listItem.innerHTML = 
            "<a href='post_detail.php?id=" + post.id + "'><strong>" + post.title + "</strong>" +
            " - " + contentPreview +
            " - <small>" + date + "</small></a>";
        postList.appendChild(listItem);
  });
}
// --------------------------------------------------------------------
// 프로필 페이지
function loadUserProfile() {
  // 서버로부터 사용자 정보를 가져오는 AJAX 요청
  fetch('get_user_profile.php') // 실제 서버 경로로 대체
      .then(response => response.json())
      .then(data => {
          // 서버로부터 받은 사용자 정보를 모달창에 표시
          const userInfoContent = document.getElementById('userInfoContent');
          userInfoContent.innerHTML = `
              <strong>ID:</strong> ${data.id}<br>
              <strong>Name:</strong> ${data.name}<br>
              <strong>Email:</strong> ${data.email}`;
      })
      .catch(error => console.error('Error:', error));
}

// 사용자 이름 버튼 클릭 시 이벤트 핸들러
document.getElementById('usernameButton').addEventListener('click', function() {
  document.getElementById('id03').style.display = 'block';
  loadUserProfile(); // 사용자 프로필 정보 로드
});

// Get the modal
// 이거 박으로 클릭하면 창이 없어지는 모션
var modal = document.getElementById('id03');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
// 근본 ---------------------------------------------------------------------------------------
function main() {

(function () {
   'use strict';

	// Hide .navbar first
	$(".navbar").hide();
	
	// Fade in .navbar
	$(function () {
		$(window).scroll(function () {
            // set distance user needs to scroll before we fadeIn navbar
			if ($(this).scrollTop() > 200) {
				$('.navbar').fadeIn();
			} else {
				$('.navbar').fadeOut();
			}
		});

	
	});
	
	// Preloader */
	  	$(window).load(function() {

   	// will first fade out the loading animation 
    	$("#status").fadeOut("slow"); 

    	// will fade out the whole DIV that covers the website. 
    	$("#preloader").delay(500).fadeOut("slow").remove();      

  	}) 

   // Page scroll
  	$('a.page-scroll').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
            $('html,body').animate({
              scrollTop: target.offset().top - 40
            }, 900);
            return false;
          }
        }
      });

    // Show Menu on Book
    $(window).bind('scroll', function() {
        var navHeight = $(window).height() - 100;
        if ($(window).scrollTop() > navHeight) {
            $('.navbar-default').addClass('on');
        } else {
            $('.navbar-default').removeClass('on');
        }
    });

    $('body').scrollspy({ 
        target: '.navbar-default',
        offset: 80
    })

  	$(document).ready(function() {
  	    $("#testimonial").owlCarousel({
        navigation : false, // Show next and prev buttons
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem:true
        });

  	});

  	// Portfolio Isotope Filter
    $(window).load(function() {
        var $container = $('.portfolio-items');
        $container.isotope({
            filter: '*',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
        $('.cat a').click(function() {
            $('.cat .active').removeClass('active');
            $(this).addClass('active');
            var selector = $(this).attr('data-filter');
            $container.isotope({
                filter: selector,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            return false;
        });

    });
	
	

  // jQuery Parallax
  function initParallax() {
    $('#intro').parallax("100%", 0.3);
    $('#services').parallax("100%", 0.3);
    $('#aboutimg').parallax("100%", 0.3);	
    $('#testimonials').parallax("100%", 0.1);

  }
  initParallax();

  	// Pretty Photo
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools: false
	});	

}());


}
main();