<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

if($bo_table) {
	$E_bo = sql_fetch("SELECT * FROM g5_board where bo_table ='$bo_table'");
}

// 오늘 새글
function bo_count($bo){
	$cnt = 0;
	foreach (func_get_args() as $bo) {
		$table = "g5_write_".$bo;
		$sql = "select count(*) cnt from $table where wr_datetime >= CURRENT_DATE() and wr_is_comment=0";
		$row = sql_fetch($sql);
		$cnt += $row['cnt'];
	}
	return $cnt;;
}

// 팝업추가
if(defined('_INDEX_')) {
    include G5_BBS_PATH.'/newwin.inc.php';
}

?>


<div id='searchWrapper'>
  <button type="button" class="btnOverlayClose">
        <img src="/gn/theme/ety_wide_theme/img/overlay-close.png" alt="">
   </button>
  <div class="hd_sch_wr">

      <fieldset id="hd_sch">
          <legend>사이트 내 전체검색</legend>
          <form name="fsearchbox" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);">
          <input type="hidden" name="sfl" value="wr_subject||wr_content">
          <input type="hidden" name="sop" value="and">
          <label for="sch_stx" class="sound_only">검색어 필수</label>
          <input type="text" name="stx" id="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
          <button type="submit" id="sch_submit" value="검색"><img src="/gn/theme/ety_wide_theme/img/srch.svg" alt=""><span class="sound_only"></span></button>
          </form>

          <script>
          function fsearchbox_submit(f)
          {
              if (f.stx.value.length < 2) {
                  alert("검색어는 두글자 이상 입력하십시오.");
                  f.stx.select();
                  f.stx.focus();
                  return false;
              }

              // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
              var cnt = 0;
              for (var i=0; i<f.stx.value.length; i++) {
                  if (f.stx.value.charAt(i) == ' ')
                      cnt++;
              }

              if (cnt > 1) {
                  alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
                  f.stx.select();
                  f.stx.focus();
                  return false;
              }

              return true;
          }
          </script>

      </fieldset>


      <ul class="list-unstyled list-inline keywordList">
            <h4 class="keywordParagraph">가장 인기 있는 검색어</h4>
						<li><a href="javascript:;" onclick="$('#q').val('보톡스');totalSearch();return false;" role="button" class="btnKeyword">보톡스</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('필러');totalSearch();return false;" role="button" class="btnKeyword">필러</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('다이어트');totalSearch();return false;" role="button" class="btnKeyword">다이어트</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('여드름');totalSearch();return false;" role="button" class="btnKeyword">여드름</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('주름');totalSearch();return false;" role="button" class="btnKeyword">주름</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('콜라겐');totalSearch();return false;" role="button" class="btnKeyword">콜라겐</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('홍조');totalSearch();return false;" role="button" class="btnKeyword">홍조</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('미백');totalSearch();return false;" role="button" class="btnKeyword">미백</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('리프팅');totalSearch();return false;" role="button" class="btnKeyword">리프팅</a></li>

						<li><a href="javascript:;" onclick="$('#q').val('승모근');totalSearch();return false;" role="button" class="btnKeyword">승모근</a></li>

				</ul>

  </div>
</div>




<!-------------------------- 네비게이션 -------------------------->
<!-- <div class="container-fluid top-line fixed-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="tnb-left">
					<!-- social -->
					<!-- <div class="sns_icon">
					<a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
					</div>
					<div class="sns_icon">
					<a href="#"><i class="fab fa-twitter"></i></a>
					</div>
					<div class="sns_icon">
					<a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
					</div>
				</div>
				<div id="tnb">
					<ul>
					<?php if($is_member) { ?>
						<li><a href="<?php echo G5_URL?>/bbs/logout.php">로그아웃</a></li>
						<li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php">정보수정</a></li>
					<?php }else{ ?>
						<li><a href="<?php echo G5_URL?>/bbs/register.php"><i class="fa fa-user-plus" aria-hidden="true"></i> 회원가입</a></li>
						<li><a href="<?php echo G5_URL?>/bbs/login.php"><i class="fas fa-sign-in-alt"></i> 로그인</a></li>
					<?php }?>
						<li><a href="<?php echo G5_URL?>/bbs/faq.php"><i class="fa fa-question" aria-hidden="true"></i> <span>FAQ</span></a></li>
						<li><a href="<?php echo G5_URL?>/bbs/qalist.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>1:1문의</span></a></li>
						<!-- <li><a href="<?php echo G5_URL?>/bbs/current_connect.php" class="visit"><i class="fa fa-users" aria-hidden="true"></i> <span>접속자</span><strong class="visit-num">
						1</strong></a></li> -->
						<!-- <li><a href="<?php echo G5_URL?>/bbs/new.php"><i class="fa fa-history" aria-hidden="true"></i> <span>새글</span></a></li> -->
						<?php if($is_admin) { ?>
						<li><a href="<?php echo G5_URL?>/adm">관리자</a></li>
						<?php } ?>
					</ul>
				</div> -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div>
<style>
.collapse.in{
    -webkit-transition-delay: 4s;
    transition-delay: 5s;
    visibility: visible;
}
</style> -->
<nav class="navbar fixed-top navbar-expand-lg navbar-white fixed-top">
  <div class="container">
	<a class="navbar-brand" href="<?php echo G5_URL?>" class="logo"><img src="<?php echo G5_THEME_URL?>/img/iblogo.png"></a>
	<button class="navbar-toggler navbar-dark navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarResponsive" data-hover="dropdown" data-animations="fadeIn fadeIn fadeInUp fadeInRight">
	  <ul class="navbar-nav ">
		<?php
		$sql = " select *
					from {$g5['menu_table']}
					where me_use = '1'
					  and length(me_code) = '2'
					order by me_order, me_id ";
		$result = sql_query($sql, false);
		$gnb_zindex = 999; // gnb_1dli z-index 값 설정용
		$menu_datas = array();
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$menu_datas[$i] = $row;

			$sql2 = " select *
						from {$g5['menu_table']}
						where me_use = '1'
						  and length(me_code) = '4'
						  and substring(me_code, 1, 2) = '{$row['me_code']}'
						order by me_order, me_id ";
			$result2 = sql_query($sql2);
			for ($k=0; $row2=sql_fetch_array($result2); $k++) {
				$menu_datas[$i]['sub'][$k] = $row2;
			}
		}
		$i = 0;
		foreach( $menu_datas as $row ){
			if( empty($row) ) continue;
		?>
			<?php if($row['sub']['0']) { ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle ks4 f16" href="<?php echo $row['me_link']; ?>" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target="_<?php echo $row['me_target']; ?>">
					<?php echo $row['me_name'] ?>
					</a>
						<!-- 서브 -->
						<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
							<?php
							// 하위 분류
							$k = 0;
							foreach( (array) $row['sub'] as $row2 ){

							if( empty($row2) ) continue;

							?>
							<a class="dropdown-item ks4 f15 fw4" href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a>

							<?php
							$k++;
							}   //end foreach $row2

							if($k > 0)
							echo '</ul>'.PHP_EOL;
							?>
			<?php }else{?>
				<li class="nav-item">
				<a class="nav-link en2 f16" href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>"><?php echo $row['me_name'] ?></a>
				</li>
			<?php }?>
		</li>

		<?php
		$i++;
		}   //end foreach $row

		if ($i == 0) {  ?>
			<li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
		<?php } ?>
		<li class="nav-item dropdown login">
		  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			LOGIN
		  </a>
		  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">

			<?php if($is_admin) { ?><a class="dropdown-item" href="<?php echo G5_URL?>/adm">관리자</a><?php } ?>
			<a class="dropdown-item" href="<?php echo G5_URL?>/bbs/new.php">새글</a>
			<a class="dropdown-item" href="<?php echo G5_URL?>/bbs/qalist.php">1:1문의</a>
			<?php if($is_member) { ?>
			<a class="dropdown-item" href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php">정보수정</a>
			<a class="dropdown-item" href="<?php echo G5_URL?>/bbs/logout.php">로그아웃</a>
			<?php }else{ ?>
			<a class="dropdown-item" href="<?php echo G5_URL?>/bbs/login.php">로그인</a>
			<a class="dropdown-item" href="<?php echo G5_URL?>/bbs/register.php">회원가입</a>
			<?php } ?>
		  </div>
		</li>
	  </ul>
    <!--검색자리-->
    <div class='utilMenu'>
        <button id="searchBtn_trigger">
          <img src="/gn/theme/ety_wide_theme/img/srch_b.svg" alt="">
          </button>
    </div>
    <!-- 로그인 자리 -->
    <div class="login_">
      <a href="https://parkgom13.cafe24.com/gn/bbs/login.php"><img src="/gn/theme/ety_wide_theme/img/logo_.svg" alt=""></a>
    </div>


	</div>
  </div>
</nav>

<style>
/* mobile */
@media (min-width: 1px) and (max-width: 1089px) {
	.ety-main{margin-bottom:63px;}
}

/* desktop */
@media (min-width: 1090px) {
	.ety-main{margin-bottom:110px;}
}
</style>
<div class="ety-main"></div>

<?php include_once(G5_PATH.'/fixed_quick.html')?>

<!-------------------------- 게시판 상단 배경 수정하는 곳 -------------------------->
<?php
	if($bo_table){
		include_once(G5_THEME_PATH.'/top_banner.php');
	}
?>
<!-------------------------- ./게시판 상단 배경 수정하는 곳 -------------------------->
