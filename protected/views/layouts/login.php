<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
</head>
<body>
	<?php $this->renderPartial("application.views.layouts.subviews.header",array(
		"preventIncludeBelowBar" => true
	)); ?>
	<div class="login-main-area">
		<div class="container">
			<?php echo $content ?>
		</div>
		<button id="myBtn" class="btn btn-info">Open Modal</button>

		<!-- The Modal -->
		<div id="myModal" class="modal">

			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<span class="close">&times;</span>
					<h2 style="font-size:20px;color:white;">Điều khoản sử dụng</h2>
				</div>

				<div id="term-of-use" class="modal-body" onscroll='checkBottom()'>
					<p>Some text in the Modal Body</p>

					<p>1.1   Đối với hàng ký gửi chúng tôi chỉ nhận hàng về kho Bằng Tường.</p>

	        <p>- Địa chỉ kho chúng tôi sẽ cung cấp khi đã thống nhất xong cách làm việc, khách hàng tự ý gửi về mà không có thỏa thuận trước chúng tôi sẽ không chịu trách nhiệm nếu hàng hóa bị mất hay hỏng.

					<p>1.2   Khách hàng cần cung cấp thông tin hàng và mã vận đơn khi gửi hàng:

					<p>- Đó là những thông tin căn cứ để chúng tôi làm việc và vận chuyển cũng như đền bù nếu xảy ra vấn đề mất mát hỏng hóc.

 					<p>1.3   Với những hàng dễ vỡ nếu khách hàng không yêu cầu đóng kiện gỗ thì Orderhip sẽ không chịu trách nhiệm nếu hàng về bị vỡ hay bóp méo:

 					<p>- Chúng tôi có hỗ trợ khách hàng đóng kiện gỗ nếu khách hàng yêu cầu. Tuy nhiên vì nhận rất nhiều hàng 1 ngày nên không thể kiểm và tự động đóng hàng của khách nếu không có yêu cầu. Vì vậy quý khách cần lưu ý khi chuyển hàng dễ vỡ. Phí đóng kiện gỗ tùy thuộc vào yêu cầu và kích thước kiện nên khách hàng vui long liên hệ để được tư vấn cụ thể

					<p>1.4   Chúng tôi không nhận vận chuyển hàng hóa trái phép, hàng hóa cấm vận chuyển buôn bán:

					<p>- Động thực vật, trang thiết bị quân sự, hóa chất, chất nổ, chất kích thích… Khác hàng chịu trách nhiệm trước pháp luật khi tự ý chuyển hàng trái phép.

					<p>1.5   Cước phí trong báo giá chỉ áp dụng về tới kho của Orderhip tại Hà Nội:

					<p>- Khách hàng có thể yêu cầu nhận hàng tại địa điểm của quý khách, chi phí phát sinh sẽ được tính tùy vào vào khối lượng hàng và khoảng cách địa lý.

					<p>1.6   Thời gian vận chuyển và đóng gói từ kho Bằng Tường về từ 1-3 ngày

					<p>- Thời gian vận chuyển không thể chính xác từng ngày vì nhiều yếu tố, mong khách hàng chú ý. Đối với những trường hợp cụ thể chúng tôi sẽ liên hệ và báo với quý khách

					<p>1.7   Hàng cồng kềnh áp dụng công thức tính khối lượng quy đổi. khối lượng = thể tích/6000:

					<p>- Với hàng cồng kềnh, hàng hóa chiếm nhiều diện tích xe hàng chúng tôi nói riêng và nghành vận chuyển nói chung áp dụng công thức quy đổi cân nặng hàng hóa hoặc tính khối được ghi rõ ở bảng giá OrderHip.

					<p>1.8   Chịu chi phí phát sinh nếu trong quá trình nhận hàng và giao hàng có phát sinh

					<p>- Chi phí đi nhận hàng, bốc vác nâng hạ hoặc phát sinh phí vận chuyển trả sau

					<p>1.9   Giải Quyết khiếu nại

					<p>1.9.1   Đối với trường hợp mất hoặc thiếu hàng: 

					<p>- Trường hợp 1: Không chịu trách nhiệm khi hàng hóa về đến tay khách hàng nguyên đai,đủ số kiện thể hiện việc thiếu hàng không do lỗi OrderHip.

					<p>- Trường hợp 2: hàng hóa về không biết của ai do thiếu thông tin đơn hàng. OrderHip buộc phải mở hàng để kiểm tra và thông báo. Trường hợp kiện hàng đó khách hàng chứng minh được mất mát do OrderHip. OrderHip sẽ đền 100% giá trị tiền hàng.

					<p>- Trường hợp 3: mất do quá trình vận chuyển của OrderHip như thiếu kiện,rách kiện. OrderHip đền bù 100% giá trị số hàng thiếu

					<p>1.9.2 Trường hợp không cung cấp thông tin cá nhân hoặc thông tin hàng vận chuyển

					</p>
				</div>

				<div class="modal-footer">
				  <!-- Disable the accept button by default -->
					<button id="accept-button" class="btn btn-info" disabled> Dummy button </button>

					<button class="btn btn-danger"> Dummy button </button>
				</div>
			</div>

		</div>
	</div>
</body>
</html>

<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  /* overflow: auto;  */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
/* Modal Header */
.modal-header {
  padding: 2px 16px;
  background-color: #73AD21;
  color: white;
  height: 6vh;		/*Height of the actual header*/
  text-align: center;
}

/* Modal Body */
.modal-body {
	padding: 2px 16px;
	height: 65vh;			/*Height of the actual paragraph*/
	overflow-y: scroll;
}

/* Modal Footer */
.modal-footer {
  padding: 2px 16px;
  background-color: #73AD21;
  color: white;
  height: 6vh;			/*Height of the actual footer*/
}

/* Modal Content/Box */
.modal-content {
	background-color: #fefefe;
	position: absolute;
	margin-left: auto;
	margin-right: auto;
	left: 0;
	right: 0;
	margin: 5% auto; /* 15% from the top and centered */
	padding: 20px;
	border: 1px solid #888;
	width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

<script>
	// Get the modal
	var modal = document.getElementById('myModal');

	// Get the button that opens the modal
	var btn = document.getElementById("myBtn");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	var textElement;
	// When the user clicks on the button, open the modal 
	btn.onclick = function() {
		// Get the <modal-body> element that contains the terms of use
		textElement = document.getElementById("term-of-use")
		modal.style.display = "block";
	}

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
	}}

	// When user scrolled the entire term of use, enable the accept button
	function checkBottom(){
		if ((textElement.scrollTop + textElement.offsetHeight) >= textElement.scrollHeight){
			document.getElementById("accept-button").disabled = false;
		}
	}
</script>