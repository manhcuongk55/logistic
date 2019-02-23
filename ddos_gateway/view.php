<html>
<head>
	<meta charset="utf-8" />
	<title>Xác nhận truy cập | EZ4Home</title>
	<style>
		*{
			margin: 0; 
			padding: 0;
			font-family: Arial, san-serif;
			text-align: center;
		}
		.btn{
			color: #fff;
			background-color: #558B2F;
			border-color: transparent;
			display: inline-block;
			margin: 0 2px 3px 0;
			padding: 7px 15px;
			line-height: 20px;
			font-size: 16px;
			text-align: center;
			vertical-align: middle;
			cursor: pointer;
			background-image: none;
			white-space: nowrap;
			border: 1px solid transparent;
			border-radius: 2px;
			-moz-user-select: -moz-none;
			-ms-user-select: none;
			-webkit-user-select: none;
			user-select: none;
			-moz-transition: background-color 0.2s ease, border-color, ease 0.2s, color 0.2s ease;
			-o-transition: background-color 0.2s ease, border-color, ease 0.2s, color 0.2s ease;
			-webkit-transition: background-color 0.2s ease, border-color, ease 0.2s, color 0.2s ease;
			transition: background-color 0.2s ease, border-color, ease 0.2s, color 0.2s ease;
			text-decoration: none;
		}
		.btn:hover{
			background-color: #33691E;
		}
		.btn:active{
			background-color: #558B2F;
		}
	</style>
</head>
<body>
	<div style="text-align: center">
		<form id="form" method="POST">
			<input type="hidden" name="url" value="<?php echo $currentRequestUrl ?>" />
			<input type="hidden" name="session_uuid" value="<?php echo $uuid ?>" />
			<div style="margin-top: 70px"><img src="ddos_gateway/logo.png" alt="logo ez4home" style="width: 96px; height: 96px;"></div>
			<div style="margin-top: 40px; font-size: 16px;">Xác nhận truy cập website</div>
			<div style="margin-top: 20px; font-size: 16px;">
				<a href="#" id="submit" onclick="doSubmit(event)" class="btn">Vào website</a>
			</div>
		</form>
	</div>
</body>
<script type="text/javascript">
	var form = null;
	window.onload = function(){ 
		form = document.getElementById("form");
		/*form.onsubmit = function(){
			
		}*/
	}
	function doSubmit(e){
		form.innerHTML += '<input type="hidden" name="clicked" value="1" />';
		form.submit();
		e.preventDefault();
		e.stopPropagation();
		return false;
	}
</script>
</html>