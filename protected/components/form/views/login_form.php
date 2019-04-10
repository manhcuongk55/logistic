<?php $form->open(array(
	"id" => "login-form"
)) ?>
	<div class="form-group">
		<label><?php $form->l_("Email") ?></label>
		<?php $form->inputField("email",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Email")
		)) ?>
	</div>
	<div class="form-group">
		<label><?php $form->l_("Mật khẩu") ?></label>
		<?php $form->inputField("password",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Mật khẩu")
		)) ?>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label class="checkbox-text">
			<input type="checkbox" value="">
			<?php $form->l_("Ghi nhớ tài khoản") ?> </label>
		</div>
		<div>
			<a href="<?php echo $this->createUrl("/site/forgot_password") ?>" class="fz12"><i><?php $form->l_("Quên mật khẩu") ?></i></a>
		</div>
	</div>
	<div class="form-group">
		<?php $form->submitButton("Đăng nhập",array(
			"type" => "button",
			"class" => "custom-button1"
		)) ?>
	</div>
	<hr>
	<div class="form-group" type="button">
		<button id="myBtn" class="fb-button" type="button"> Đăng nhập với Facebook </button> 
	</div>
	<div id="myModal" class="modal">
		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">&times;</span>
				<h2 style="font-size:20px;color:white;">Điều khoản sử dụng</h2>
			</div>

			<div id="term-of-use" class="modal-body" style="color:black">

				<p class="headline">QUY ĐỊNH KÝ GỬI</p>

				<p class="title">1.1   Đối với hàng ký gửi chúng tôi chỉ nhận hàng về kho Bằng Tường.</p>

				<p>- Địa chỉ kho chúng tôi sẽ cung cấp khi đã thống nhất xong cách làm việc, khách hàng tự ý gửi về mà không có thỏa thuận trước chúng tôi sẽ không chịu trách nhiệm nếu hàng hóa bị mất hay hỏng.

				<p class="title">1.2   Khách hàng cần cung cấp thông tin hàng và mã vận đơn khi gửi hàng:

				<p>- Đó là những thông tin căn cứ để chúng tôi làm việc và vận chuyển cũng như đền bù nếu xảy ra vấn đề mất mát hỏng hóc.

				<p class="title">1.3   Với những hàng dễ vỡ nếu khách hàng không yêu cầu đóng kiện gỗ thì Orderhip sẽ không chịu trách nhiệm nếu hàng về bị vỡ hay bóp méo:

				<p>- Chúng tôi có hỗ trợ khách hàng đóng kiện gỗ nếu khách hàng yêu cầu. Tuy nhiên vì nhận rất nhiều hàng 1 ngày nên không thể kiểm và tự động đóng hàng của khách nếu không có yêu cầu. Vì vậy quý khách cần lưu ý khi chuyển hàng dễ vỡ. Phí đóng kiện gỗ tùy thuộc vào yêu cầu và kích thước kiện nên khách hàng vui long liên hệ để được tư vấn cụ thể

				<p class="title">1.4   Chúng tôi không nhận vận chuyển hàng hóa trái phép, hàng hóa cấm vận chuyển buôn bán:

				<p>- Động thực vật, trang thiết bị quân sự, hóa chất, chất nổ, chất kích thích… Khác hàng chịu trách nhiệm trước pháp luật khi tự ý chuyển hàng trái phép.

				<p class="title">1.5   Cước phí trong báo giá chỉ áp dụng về tới kho của Orderhip tại Hà Nội:

				<p>- Khách hàng có thể yêu cầu nhận hàng tại địa điểm của quý khách, chi phí phát sinh sẽ được tính tùy vào vào khối lượng hàng và khoảng cách địa lý.

				<p class="title">1.6   Thời gian vận chuyển và đóng gói từ kho Bằng Tường về từ 1-3 ngày

				<p>- Thời gian vận chuyển không thể chính xác từng ngày vì nhiều yếu tố, mong khách hàng chú ý. Đối với những trường hợp cụ thể chúng tôi sẽ liên hệ và báo với quý khách

				<p class="title">1.7   Hàng cồng kềnh áp dụng công thức tính khối lượng quy đổi. khối lượng = thể tích/6000:

				<p>- Với hàng cồng kềnh, hàng hóa chiếm nhiều diện tích xe hàng chúng tôi nói riêng và nghành vận chuyển nói chung áp dụng công thức quy đổi cân nặng hàng hóa hoặc tính khối được ghi rõ ở bảng giá OrderHip.

				<p class="title">1.8   Chịu chi phí phát sinh nếu trong quá trình nhận hàng và giao hàng có phát sinh

				<p>- Chi phí đi nhận hàng, bốc vác nâng hạ hoặc phát sinh phí vận chuyển trả sau

				<p class="title">1.9   Giải quyết khiếu nại

				<p class="subTitle">1.9.1   Đối với trường hợp mất hoặc thiếu hàng: 

				<p>- Trường hợp 1: Không chịu trách nhiệm khi hàng hóa về đến tay khách hàng nguyên đai,đủ số kiện thể hiện việc thiếu hàng không do lỗi OrderHip.

				<p>- Trường hợp 2: hàng hóa về không biết của ai do thiếu thông tin đơn hàng. OrderHip buộc phải mở hàng để kiểm tra và thông báo. Trường hợp kiện hàng đó khách hàng chứng minh được mất mát do OrderHip. OrderHip sẽ đền 100% giá trị tiền hàng.

				<p>- Trường hợp 3: mất do quá trình vận chuyển của OrderHip như thiếu kiện,rách kiện. OrderHip đền bù 100% giá trị số hàng thiếu

				<p class="subTitle">1.9.2 Trường hợp không cung cấp thông tin cá nhân hoặc thông tin hàng vận chuyển </p>

				<p>· Nếu quý khách không cung cấp thông tin hoặc cung cấp thông tin không đúng sự thật khi xảy ra vấn đề mất mát,hỏng hóc.</p>

				<p>- Trường hợp 1: hàng cấm theo pháp luật việt Nam(thuộc diện hình sự) như súng,đạn,thuốc lắc,thuốc phiện... Quý khách sẽ phải chịu mọi trách nhiệm trước pháp luật. và OrderHip không chịu bất cứ trách nhiệm gì với khách hàng,pháp luật liên quan tới lô hàng.</p>

				<p>- Trường hợp 2: Không thuộc diện hàng cấm. OrderHip sẽ đền 5 lần cước vận chuyển.</p>

				<p class="subTitle">1.9.3 Đối với trường hợp hàng hóa bị xây xước, bóp méo trong quá trình vận chuyển từ TQ về VN:</p>

				<p>- Đền bù từ 10% - 30% giá trị tiền hàng tùy theo mức độ (thỏa thuận).</p>

				<p class="subTitle">1.9.4 Đối với trường hợp hàng hóa bị hỏng hoàn toàn do quá trình vận chuyển từ TQ về VN: </p>

				<p>- TH1: Đổi mới lại cho quý khách và chịu mọi chi phí đổi trả.</p>

				<p>- TH2: Đền bù 100% giá trị tiền hàng</p>

				<p class="subTitle">1.9.5 Hàng hóa bị thất lạc hoặc không có thông tin</p>

				<p>- Hàng về muộn sau 8 ngày kể từ ngày hẹn hàng về VN khách hàng có thể đòi 100% số tiền hàng thất lạc, hoặc không có thông tin.</p>

				<p>Nếu khách hàng vẫn muốn lấy hàng khi công ty đã có lời hẹn lại thì công ty chịu trách nhiệm đền bù số tiền bằng 5% giá trị tiền hàng của số hàng đó.</p>

				<br> <br>

				<p class="headline">QUY ĐỊNH GIAO NHẬN HÀNG </p>

				<p>· Giao nhận hàng là công đoạn cuối cùng của quá trình Order. Để việc giao nhận được thuận tiện và không sảy ra mất mát hàng hóa hoặc nhầm lẫn thì OrderHip có một số quy định như sau:</p>

				<p>- Để đáp ứng nhu cầu của khách hàng chúng tôi có thể giao hàng tại kho hoặc giao hàng tại nơi khách hàng yêu cầu:</p>

				<p class="title">1.1 Giao hàng tại kho:</p>

				<p>- Quý khách hoặc người được ủy quyền phải đến kho của chúng tôi kiểm và nhận hàng theo mã đơn hàng trên Web. Khi quý khách đã kiểm hàng và đồng ý nhận hàng thì yêu cầu quý khách thanh lý đơn hàng trước khi mang hàng về. Nếu hàng hóa sau khi mang ra khỏi kho OrderHip không chịu trách nhiệm về mọi khiếu nại. </p>

				<p class="title">1.2 Giao hàng hoặc gửi hàng đi cho khách:</p>

				<p>- Orderhip hỗ trợ đi gửi hàng hoặc giao hàng đến tận nhà cho quý khách, tuy nhiên khách hàng phải chịu chi phí gửi hàng từ kho OrderHip đến địa điểm quý khách yêu cầu, vì là quý khách không nhận hàng tại kho nên việc kiểm hàng sẽ diễn ra tại địa điểm quý khách nhận hàng.</p>

				<p class="title">1.3 Hàng bị lỗi hoặc thiếu cần phải báo ngay lập tức.</p>

				<p class="title">- Vì là công ty trung gian giao dịch giữa người bán và người mua nên có những vấn đề chúng tôi không thể hoàn toàn chủ động được. Do vậy nên khi khách hàng đã nhận được hàng mà có bất kì sự cố gì thì yêu cầu khách hàng báo ngay lại cho công ty. Sau hai ngày chúng tôi sẽ không hỗ trợ giải quyết những vấn đề đó. </p>

				<p class="title">1.4 Không lưu hàng tại kho quá 2 ngày:</p>

				<p>- Khi chúng tôi đã có thông báo hàng về kho VN thì quý khách vui lòng đến kho nhận hàng hoặc yêu cầu công ty hỗ trợ gửi hàng. Nếu để lưu kho quá hai ngày công ty sẽ tính phí lưu kho 0.5% giá trị đơn hàng/ngày.</p>

				<p class="title">1.5 Thanh lý tiền đơn hàng trước khi chuyển hàng đi.</p>

				<p>- Công ty chỉ chuyển hàng đi khi có xác nhận của kế toán về việc khách hàng đã thanh lý xong hết tiền đơn hàng.</p>

				<p class="title">1.6 Công ty chỉ giao hàng đến địa chỉ khách hàng yêu cầu, không bao gồm bốc xếp, hạ hàng từ xe xuống.</p>

				<p>- Công ty chỉ có trách nhiệm mang hàng đến địa chỉ của khách hàng khi có sự yêu cầu từ quý khách. Ngoài ra công ty sẽ không chịu trách nhiệm về những phát sinh thêm khi không có sự yêu cầu từ quý khách ( bốc xếp hàng hóa tại địa điểm giao hàng, lưu giữ xe hàng, nhân công quá lâu tại địa điểm bàn giao hàng…)</p>

				<p class="title">1.7 Giải quyết khiếu nại</p>

				<p class="subTitle">1.7.1 Thời gian kể từ khi khách hàng có yêu cầu chuyển hàng đến khi hàng bắt đầu được chuyển đi trong khoảng hai ngày.</p>

				<p>- Sau khi khách hàng đã thanh lý đơn hàng và có yêu cầu chuyển hàng đến địa chỉ khách hàng yêu cầu chúng tôi sẽ tiếp nhận và xử lý nhanh nhất có thể. Tuy nhiên, việc sắp xếp chuyển giao có thể lên tới hai ngày. Nếu trường hợp hai ngày mà công ty vẫn chưa chuyển hàng cho quý khách thì kể từ ngày thứ ba trở ra công ty chịu trách nhiệm với những thiệt hại về kinh tế mà quý khách phải chịu.</p>

				<p class="subTitle">1.7.2 Giao hàng bị thiếu, hỏng, mất hoặc bị trầy xước.</p>

				<p>- Công ty sẽ đền bù đúng giá trị tương đương với giá trị hàng hóa mất đi hoặc giá trị cần thiết để phục hồi sửa chữa. Trường hợp trầy xước trong phạm vi cho phép do quá trình vận chuyển không thể tránh khỏi thì mong khách hàng thông cảm và tự khắc phục. Việc đánh giá xem xét mức độ thiệt hại về hàng hóa sẽ phải được cả hai bên đánh giá và  thống nhất.</p>

				<br> <br>

				<p class="headline">QUY ĐỊNH GIAO NHẬN HÀNG </p>

				<p class="title">1.1   Hai hình thức thanh toán là giao tiền mặt và chuyển khoản
				</p>
				<p>- Giao tiền mặt: Khách hàng trực tiếp đến văn phòng giao tiền cho kế toán, trường hợp khách hàng giao tiền mặt cho nhân viên không tại văn phòng cần liên hệ với kế toán để xác minh. Nếu không có sự xác minh từ kế toán của công ty thì công ty sẽ không chịu trách nhiệm trước số tiền quý khách đã bàn giao.
				</p>
				<p>- Chuyển khoản qua số tài khoản ngân hàng: Chỉ được phép chuyển qua các số tài khoản để trên web, ngoài ra công ty cũng sẽ không chịu trách nhiệm giải quyết.
				</p>
				<p class="title">1.2 Khi chuyển tiền cần ghi rõ nội dung thanh toán.
				</p>
				<p>- Quý khách cần ghi rõ nội dung chuyển khoản: nếu đặt cọc quý khách viết với nội dung: ĐẶT CỌC [mã đơn], thanh lý: THANH LÝ [mã đơn] ngoài ra chúng tôi cũng không thể chịu trách nhiệm nếu khoản tiền không có nội dung. 
				</p>
				<p class="title">1.3 Việc hồi tiền hoặc bồi thường tiền cho quý khách có thể giải quyết trong ba ngày.
				</p>
				<p>- Mọi chi phí thanh toán ngược lại cho khách hàng cần được kiểm tra, giải quyết nên thời gian trả lại tiền cho quý khách có thể mất ba ngày. Mong quý khách thông cảm!

				<br> <br>

				<p class="headline"> QUY ĐỊNH ORDER </p>

				<p class="title">1.1 OrderHip không nhận order hàng hóa mà nhà nước Việt Nam cấm trao đổi mua bán và vận chuyển.
				</p>
				<p>- Một số mặt hàng theo quy định nhà nước cấm vận chuyển và trao đổi mua bán: Vũ khí, pháo hoa, trang thiết bị quân sự, chất ma túy, hóa chất, thuốc thú ý, phân bón, động thực vật....
				</p>
				<p class="title">1.2 OrderHip không chịu trách nhiệm về chất lượng và hình dáng hàng hóa nếu như trong quá trình giao dịch không có sự cam kết giữa hai bên.
				</p>
				<p>- Trước khi đặt hàng khách hàng cần nhờ nhân viên mình trực tiếp làm việc để hỏi những vấn đề chưa nắm rõ về sản phẩm, trong quá trình giao dịch khách cần lưu lại những mục đã thỏa thuận giữa hai bên. Trường hợp hàng về có gặp vấn đề gì hai bên có căn cứ giải quyết. Những thông tin mà hai bên không thỏa thuận chúng tôi sẽ không chịu trách nhiệm khi hàng về sai.
				</p>
				<p class="title">1.3 Không chịu trách nhiệm đối với hàng hóa dễ vỡ, hoặc dễ bóp méo trong quá trình vận chuyển nếu như khách hàng không yêu cầu đóng kiện gỗ.
				</p>
				<p>- Vì đặc thù vận chuyển xa, nâng hạ nhiều lần, và gom nhiều sản phẩm trên xe hàng vì vậy nên những hàng hóa dễ vỡ khách hàng cần yêu cầu nhân viên hỗ trợ giao dịch với xưởng đóng kiện gỗ hoặc nhờ Orderhip đóng kiện lại tại kho của Orderhip.
				</p>
				<p class="title">1.4 Không thể cam kết chắc chắn ngày hàng về Việt Nam.
				</p>
				<p>- Trong quá trình mua hàng, hàng hóa có thể về nhanh hoặc chậm hơn so với mức áng chừng nhân viên đưa ra, bởi có thể do xưởng shop phát hàng chậm hoặc ở xa, thậm trí trong quá trình vận chuyển có thể gặp rủi ro. Nếu hàng về chậm quá 10 ngày so với ngày hẹn quý khách có thể lấy lại tiền hàng. Chúng tôi cập nhật tình trạng hàng hóa trên web: orderhip.com				
				</p>
				<p class="title">1.5 GIẢI QUYẾT KHIẾU NẠI
				</p>
				<p>· Lưu ý: OH chỉ nhận khiếu nại khi hàng đến tay khách hàng không quá 1 ngày, Quá thời gian trên OH sẽ không chấp nhận khiếu nại do không biết nguyên nhân do bên nào nên mong quý khách kiểm tra hàng kỹ trước khi nhận.
				</p>
				<p>- Thời gian giải quyết khiếu nại từ 1-3 ngày kể từ ngày khách hàng khiếu nại.
				</p>
				<p class="subTitle">1.5.1 Đối với trường hợp mất hoặc thiếu hàng: 
				</p>
				<p>- Đền bù 100% giá trị tiền hàng bị thiếu hoặc mất.
				</p>
				<p class="subTitle">1.5.2 Đối với trường hợp hàng hóa bị xây xước, bóp méo trong quá trình vận chuyển từ TQ về VN:
				</p>
				<p>- Đền bù từ 10% - 30% giá trị tiền hàng tùy theo mức độ (thỏa thuận).
				</p>
				<p class="subTitle">1.5.3 Đối với trường hợp hàng hóa bị hỏng hoàn toàn do quá trình vận chuyển từ TQ về VN: 
				</p>
				<p>- TH1: Đổi mới lại cho quý khách và chịu mọi chi phí đổi trả.
				</p>
				<p>- TH2: Đền bù 100% giá trị tiền hàng
				</p>
				<p class="subTitle">1.5.4 Hàng hóa về bị sai so với thông tin đã thỏa thuận, lỗi do công ty cam kết sai
				</p>
				<p>- TH1: Trường hợp đổi trả: Chịu trách nhiệm 100% chi phí vận chuyển lại cho người bán.
				</p>
				<p>- TH2: Trường hợp không thể trả lại: Chịu trách nhiệm đền bù cho khách hàng khoản thiệt hại về hàng hóa mà khách hàng phải chịu, Việc xác định thiệt hại hai bên đều phải đánh giá và đi đến thống nhất dựa trên việc thỏa thuận.
				</p>
				<p class="subTitle">1.5.5 Hàng Hóa về bị sai thông tin do lỗi của khách hàng hoặc không có trong thỏa thuận của hai bên.
				</p>
				<p>- Hỗ trợ khách hàng đàm phán với người bán giải quyết khiếu nại
				</p>
				<p>- Hỗ trợ khách hàng vận chuyển nếu xảy ra việc đổi trả. Chi phí vận chuyển khách hàng chịu 100%.
				</p>
				<p>- Trường hợp đền bù bằng giá trị tiền thì công ty sẽ trả lại khách hàng đúng giá trị tiền mà người bán đền bù sau quá trình thỏa thuận và đi đến thống nhất.
				</p>
				<p class="subTitle">1.5.6 Hàng hóa bị thất lạc hoặc không có thông tin
				</p>
				<p>- Hàng về muộn sau 10 ngày kể từ ngày hẹn hàng về VN khách hàng có quyền không nhận hàng và lấy lại 100% số tiền đã đặt cọc.
				</p>
				<p>- Nếu khách hàng vẫn muốn lấy hàng khi công ty đã có lời hẹn lại thì công ty chịu trách nhiệm đền bù số tiền bằng số tiền phí dịch vụ mà khách hàng đã trả cho đơn hàng đó.
				</p>

			</div>

			<div class="modal-footer">
				<!-- Disable the accept button by default -->
				<button id="closeButton" class="btn btn-danger" type="button"> Từ chối </button>
				<!-- <button id="accept-button" class="btn btn-info" type="button" disabled> Accept </button> -->
				<a id="accept-button" href="<?php echo $this->createUrl("/site/login_with_facebook") ?>" class="btn btn-info"><?php $form->l_("Đồng ý") ?></a>
			</div>
		</div>
	</div>
<?php $form->close(); ?>

<style>
.textbox {
	border-radius: 15px;
    border: 2px solid #00BFFF;
    padding: 20px; 
}
.custom-button1 {
	background-color: #4CAF50;
	border: none;
	color: white;
	height: 50px;
	width: 100%;
	text-align: center;
	text-decoration: none;
	display: block;
	font-size: 16px;
	margin: 4px 2px;
	border-radius: 12px;
}
.fb-button {
	background-color: #3b5998;
	border: none;
	color: white;
	height: 50px;
	width: 100%;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	margin: 4px 2px;
	border-radius: 12px;
}
.checkbox-text {
	background-color: #ffffff;
	border: none;
	color: #00BFFF;
}
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

/* ToS styling - thienld 3/4*/
.title {
	font-weight: bold;
	font-size: 115%;
}

.subTitle {
	font-style: italic;
	font-size: 110%;
}

.headline {
	font-weight: bold;
	font-size: 150%;
}
</style>

<script>
	//Login script
	$(function(){
			$("#login-form").on("form-success",function(){
				location.href = "<?php echo LoginHelper::getLoginCallbackUrl() ?>";
			});
		});

	// Get the modal
	var modal = document.getElementById('myModal');

	// Get the button that opens the modal
	var btn = document.getElementById("myBtn");

	// Get the button that close the modal
	var clsBtn = document.getElementById("closeButton");

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
	// When the user clicks on <span> (x), close the modal
	clsBtn.onclick = function() {
		modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
	}}
</script>