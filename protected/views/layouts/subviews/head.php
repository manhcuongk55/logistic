<meta charset="UTF-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?php echo $this->pageTitle ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
<?php $fb = Util::param2("accounts","facebook"); ?>
<meta property="fb:app_id" content="<?php echo $fb["app_id"] ?>" />
<?php if(!ModelSeoHelper::$metaGenerated): ?>
	<meta name="description" content="<?php echo Util::param2("front_page","meta_description") ?>"> 
	<meta name="keyword" content="<?php echo Util::param2("front_page","meta_keywords") ?>">
<?php endif; ?>

<!-- Global site tag (gtag.js) - AdWords: 802322498 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-802322498"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-802322498');
</script>
