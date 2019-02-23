<style>
	.team-img {
		width: 250px;
		height: 250px;
	}

	.team-img .mask {
		opacity: 1 !important;
	}

	.youtube-iframe-container iframe {
		width: 500px !important;
		height: 300px !important;
	}
</style>

<?php $this->renderPartial("application.views.home.subviews.search") ?>

<!--Slider Area Start-->
<div class="slider-area-home-four">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<div class="preview-2">
					<div id="nivoslider" class="slides">
						<a href="#"><img src="/img/slider/slider-4.jpg" alt="" title="#slider-1-caption1"/></a>
						<a href="#"><img src="/img/slider/slider-5.jpg" alt="" title="#slider-1-caption1"/></a>
					</div>
					<div id="slider-1-caption1" class="nivo-html-caption nivo-caption">
						<div class="timethai" style=" position:absolute; bottom:0; left:0; background-color:rgba(224, 53, 80, 0.6); height:3px; -webkit-animation: myfirst 10000ms ease-in-out; -moz-animation: myfirst 10000ms ease-in-out; -ms-animation: myfirst 10000ms ease-in-out; animation: myfirst 10000ms ease-in-out; ">
						</div>
						<!--<div class="banner-content slider-1 hidden-xs">
							<div class="text-content">
								<h1 class="title1"><span>collections</span></h1>
								<h2 class="title2"><span>2015 new design</span></h2>
								<div class="banner-readmore">
									<a href="#" title="Read more">Read more</a>
								</div>
							</div>
						</div>-->
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6">
				<div class="banner-area-home-four">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="banner-container">
								<a href="/page_5_order-online.html">
									<img src="/img/slider/slider-3.jpg" alt="">
								</a>
								<!--<div class="banner-text">
									<h3>SHORT DUNGAREES</h3>
									<p>
										Nam libero tempore, cum soluta nobis est eligendi optio cumque omnis voluptas assumenda est, omnis dolor repellendus.
									</p>
									<a href="#">View all products</a>
								</div>-->
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="banner-container">
								<a href="/page_6_ky-gui-hang.html">
									<img src="/img/slider/slider-2.jpg" alt="">
								</a>
								<!--<div class="banner-text">
									<h3>SHORT DUNGAREES</h3>
									<p>
										Nam libero tempore, cum soluta nobis est eligendi optio cumque omnis voluptas assumenda est, omnis dolor repellendus.
									</p>
									<a href="#">View all products</a>
								</div>-->
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="banner-container">
								<a href="/page_7_chuyen-hang-noi-dia.html">
									<img src="/img/slider/slider-1.jpg" alt="">
								</a>
								<!--<div class="banner-text">
									<h3>SHORT DUNGAREES</h3>
									<p>
										Nam libero tempore, cum soluta nobis est eligendi optio cumque omnis voluptas assumenda est, omnis dolor repellendus.
									</p>
									<a href="#">View all products</a>
								</div>-->
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="banner-container">
								<a href="#">
									<img src="/img/slider/slider-6.jpg" alt="">
								</a>
								<!--<div class="banner-text">
									<h3>SHORT DUNGAREES</h3>
									<p>
										Nam libero tempore, cum soluta nobis est eligendi optio cumque omnis voluptas assumenda est, omnis dolor repellendus.
									</p>
									<a href="#">View all products</a>
								</div>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End of Slider Area-->
<hr/>


<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="f-title text-center">
				<h3 class="title text-uppercase">Một số hướng dẫn</h3>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 pd-a10 text-center youtube-iframe-container">
			<?php echo Util::param2("front_page_content","video") ?>
		</div>
		<div class="col-md-6 pd-a10">
			<?php echo Util::param2("front_page_content","content") ?>
		</div>
	</div>
</div>
<hr/>

<div class="home-our-team" style="margin-top: 60px;">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="f-title text-center">
					<h3 class="title text-uppercase">Giới thiệu về các website mua hàng</h3>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-4">
				<div class="item-team text-center">
					<div class="team-info">
						<div class="team-img">
							<img width="250" height="250" alt="team1" class="img-responsive" src="/img/banner/vendors/taobao.gif">
							<div class="mask">
								<div class="mask-inner">
									<a href="http://taobao.com" target="_blank"><i class="fa fa-link"></i></a>
								</div>
							</div>
						</div>
					</div>
					<h4>TAOBAO.COM</h4>
					<h5>Trang bán lẻ</h5>
				</div>
			</div>
			<div class="col-md-3 col-sm-4">
				<div class="item-team text-center">
					<div class="team-info">
						<div class="team-img">
							<img width="250" height="250" alt="team2" class="img-responsive" src="/img/banner/vendors/1688.jpg">
							<div class="mask">
								<div class="mask-inner">
									<a href="http://1688.com" target="_blank"><i class="fa fa-link"></i></a>
								</div>
							</div>
						</div>
					</div>
					<h4>1688.COM</h4>
					<h5>Trang bán buôn</h5>
				</div>
			</div>
			<div class="col-md-3 col-sm-4">
				<div class="item-team text-center">
					<div class="team-info">
						<div class="team-img">
							<img width="250" height="250" alt="team3" class="img-responsive" src="/img/banner/vendors/tmall.png">
							<div class="mask">
								<div class="mask-inner">
									<a href="http://tmall.com" target="_blank"><i class="fa fa-link"></i></a>
								</div>
							</div>
						</div>
					</div>
					<h4>TMALL.COM</h4>
					<h5>Trang bán lẻ hàng chất lượng cao</h5>
				</div>
			</div>
			<div class="col-md-3 hidden-sm">
				<div class="item-team text-center">
					<div class="team-info">
						<div class="team-img">
							<img width="250" height="250" alt="team4" class="img-responsive" src="/img/banner/vendors/other.jpg">
							<div class="mask">
								<div class="mask-inner">
									<a href="#"><i class="fa fa-link"></i></a>
								</div>
							</div>
						</div>
					</div>
					<h4>CÁC WEBSITE KHÁC</h4>
					<h5>Các website ngoài hệ thống Alibaba</h5>
				</div>
			</div>
		</div>
	</div>
</div>
<hr/>
<?php $contantPhones = Util::param2("front_page_content","contact_phones") ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="f-title text-center">
				<h3 class="title text-uppercase">Liên hệ với chúng tôi</h3>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 pd-a10 text-center">
			<h1>Nhân viên kinh doanh</h1>
			<div id="receivers-container">
				<div id="receivers">
				</div>
			</div>
		</div>
		<div class="col-md-6 pd-a10">
			<h1>Các bộ phận khác</h1>
			<div>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>
								Bộ phận
							</td>
							<td>
								SĐT
							</td>
						</tr>
						<tr>
							<td>
								CSKH
							</td>
							<td>
								<?php echo $contantPhones["sale"] ?>
							</td>
						</tr>
						<tr>
							<td>
								Kê toán
							</td>
							<td>
							<?php echo $contantPhones["accountant"] ?>
							</td>
						</tr>
						<tr>
							<td>
								Kho
							</td>
							<td>
							<?php echo $contantPhones["store"] ?>
							</td>
						</tr>
						<tr>
							<td>
								Đặt hàng
							</td>
							<td>
								<?php echo $contantPhones["order"] ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
	#receivers-container {
		padding: 0px 20px;
		margin-top: 10px;
	}
	#receivers {

	}
	.slick-next:before,.slick-prev:before {
		color: blue;
	}
	.slider-item {
		padding: 10px;
		text-align: center;
	}
	.slider-item > * {
		margin-bottom: 3px;
	}
	.slider-item img {
		width: 100px;
		margin: 0px auto;
	}
	.slick-center .slider-item {
		border: 3px solid #e67e22;
	}
	.slider-name {
		font-weight: bold;
	}
	.slider-phone {
		font-weight: bold;
	}
</style>

<?php if(Yii::app()->user->isGuest): ?>
	<?php
		$criteria = new CDbCriteria();
		$criteria->select = array("id","name","image","email","phone");
		$criteria->compare("type",Collaborator::TYPE_SALE);
		$collaborators = Collaborator::model()->findAll($criteria);
	?>
	<script>
		var allCollaborators = <?php echo CJSON::encode($collaborators); ?>
	</script>
<?php endif; ?>
<script>
	function initCollaboratorsSlider(collaborators,clickCallback){
		$.each(collaborators,function(k,item){
			var image = item.image
			if(!image){
				image = "/img/default/collaborator/image.png"
			}
			var $elem = $(
				'<div class="slider-item">\
					<img src="' + image + '" class="img-rounded" />\
					<div class="slider-name">' + item.name + '<div>\
					<div class="slider-phone">' + item.phone + '<div>\
				</div>'
			);
			if(clickCallback){
				$elem.click(function(e){
					$elem.css({"cursor":"pointer"})
					clickCallback(item)
					return $__$.prevent(e);
				})
			}
			$("#receivers").append($elem)
		})
		
		$("#receivers").addClass("slider").slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			centerMode: collaborators.length > 3,
  			centerPadding: '60px',
			autoplay: true,
  			autoplaySpeed: 2000,
			variableWidth: true,
		});
	}

	if(window.allCollaborators){
		$(function(){
			initCollaboratorsSlider(window.allCollaborators)
		})
	} else {
		$(window).on("chat-client-ready",function(e,chatClientDataContainer){
			var masterData = chatClientDataContainer.getMasterData()
			var collaborators = []
			$.each(masterData.receivers,function(receiverID,receiver){
				if(receiver.userType!="collaborator"){
					return
				}
				var collaborator = receiver
				collaborators.push(receiver)
			})
			initCollaboratorsSlider(collaborators,function(collaborator){
				chatClientDataContainer.showConversation(collaborator.userID)
			})
		})
	}
</script>