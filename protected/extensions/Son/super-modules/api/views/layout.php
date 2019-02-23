<?php /* @var $this Controller */
    if($this->currentApiConfig!=null){
        $this->pageTitle = $this->currentApiConfig["label"];
    } else {
        $this->pageTitle = "API";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo $this->pageTitle ?></title>
</head>
<body>
	<div id="utter-wrapper" class="color-skin-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3><a href="<?php echo $this->getBaseUrl() ?>" class="btn btn-success btn-large">API</a></h3>
                </div>
            </div>
        	<div class="row" style="margin-bottom:20px;">
                <div class="col-lg-3">
                    <?php $this->getMenu()->render(); ?>
                </div>
        		<div class="col-lg-9">
        			<?php echo $content; ?>
        		</div>
        	</div>
        </div>
    </div>
</body>
</html>
