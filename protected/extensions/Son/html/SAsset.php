<?php
class SAsset {
	const BASE_THEME = "son";
	
	var $baseTheme = self::BASE_THEME;
	var $config = array();
	var $jsDefaultPosition = "bottom";
	var $jsDefaultPositionBackup = null;
	var $jsCodeTempPosition = null;
	
	var $addedExtensions = array();

	public function __construct(){
		$this->config = Son::loadFile("application.config.assets");
	}

	public function setJsDefaultPosition($jsDefaultPosition){
		$this->jsDefaultPositionBackup = $this->jsDefaultPosition;
		$this->jsDefaultPosition = $jsDefaultPosition;
	}

	public function restoreJsDefaultPosition(){
		$this->jsDefaultPosition = $this->jsDefaultPositionBackup;
	}

	public function getFilePosition($position=null){
		if($position==null)
			$position = $this->jsDefaultPosition;
		$pos =null;
		switch($position){
			case "head":
				$pos = CClientScript::POS_HEAD;
				break;
			case "top":
				$pos = CClientScript::POS_BEGIN;
				break;
			default: // bottom
				$pos = CClientScript::POS_END;
				break;
		}
		return $pos;
	}

	public function addJsFile($jsFile,$position=null){
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($jsFile,$this->getFilePosition($position));
	}
	
	public function addCssFile($cssFile){
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile($cssFile);
	}
	
	public function addJsFiles($jsFiles,$position="bottom"){
		foreach($jsFiles as $jsFile){
			$this->addJsFile($jsFile,$position);
		}
	}
	
	public function addCssFiles($cssFiles){
		foreach($cssFiles as $cssFile){
			$this->addCssFile($cssFile);
		}
	}
	
	var $jsCodeIndex = 0;
	public function startJsCode($position=null){
		$this->jsCodeTempPosition = $position;
		ob_start();
	}
	public function endJsCode(){
		$jsCode = ob_get_clean();
		$jsCode = str_replace("<script>", "", $jsCode);
		$jsCode = str_replace("</script>", "", $jsCode);
		$cs = Yii::app()->getClientScript();
		$cs->registerScript("js_code_".($this->jsCodeIndex++),$jsCode,$this->getFilePosition($this->jsCodeTempPosition));
		//$cs->renderBodyEnd($js_code);
	}

	public function arrayToJs($arr,$jsVar){
		$jsonString = json_encode($arr);
		$jsCode = "var $jsVar = $jsonString";
		$cs = Yii::app()->getClientScript();
		$cs->registerScript("js_code_".($this->jsCodeIndex++),$jsCode,CClientScript::POS_END);
	}

	var $cssCodeIndex = 0;
	public function startCssCode(){
		ob_start();
	}
	public function endCssCode(){
		$cssCode = ob_get_clean();
		$cssCode = str_replace("<style>", "", $cssCode);
		$cssCode = str_replace("</style>", "", $cssCode);
		$cs = Yii::app()->getClientScript();
		$cs->registerCss("css_code_".($this->cssCodeIndex++),$cssCode);
	}
	
	public function getUrl($theme,$name){
		return Yii::app()->createUrl("/themes/$theme/assets/$name");
	}
	
	public function addExtension($extensionName,$theme=null,$extensionFolder = "extensions/",$isSpecialExtension=false,$position=null){
		if(in_array($extensionName, $this->addedExtensions))
			return;
		$this->addedExtensions[] = $extensionName;

		$jsFiles = array();
		$cssFiles = array();
		if($theme==null){
			$theme = Yii::app()->theme->getName();
		}
		
		$themeJs = null;
		$themeCss = null;
		$baseThemeJs = null;
		$baseThemeCss = null;

		$themeDependency = null;
		$baseThemeDependency = null;
		$overloadDependency = false;
		
		$baseTheme = $this->baseTheme;

		$themeConfig = $this->config[$theme];
		if(!$isSpecialExtension){
			$themeConfig = ArrayHelper::get($themeConfig,"extensions");
		}

		$themeConfigExtension = null;

		if($themeConfig){
			$themeConfigExtension = ArrayHelper::get($themeConfig,$extensionName);
			if($themeConfigExtension){
				$themeJs = ArrayHelper::get($themeConfigExtension,"js");
				$themeCss = ArrayHelper::get($themeConfigExtension,"css");
				$themeDependency = ArrayHelper::get($themeConfigExtension,"dependency");
				$overloadDependency = ArrayHelper::get($themeConfigExtension,"__overloadDependency",false);
				if(!$position)
					$position = ArrayHelper::get($themeConfigExtension,"position");
			}
		}

		if(!$themeConfigExtension || ($theme!=$baseTheme && !ArrayHelper::get($themeConfig,"__overload",true))){
			$baseThemeConfig = $this->config[$baseTheme];
			if(!$isSpecialExtension){
				$baseThemeConfig = ArrayHelper::get($baseThemeConfig,"extensions");
			}
			if($baseThemeConfig){
				$baseThemeConfigExtension = ArrayHelper::get($baseThemeConfig,$extensionName,null);
				if($baseThemeConfigExtension){
					$baseThemeJs = ArrayHelper::get($baseThemeConfigExtension,"js");
					$baseThemeCss = ArrayHelper::get($baseThemeConfigExtension,"css");
					$baseThemeDependency = ArrayHelper::get($baseThemeConfigExtension,"dependency");
					if(!$position)
						$position = ArrayHelper::get($baseThemeConfigExtension,"position");
				}
			}
		}

		$dependency = null;
		if($themeDependency && $baseThemeDependency){
			if($overloadDependency){
				$dependency = $themeDependency;
			} else {
				$dependency = array_merge(ArrayHelper::asArray($baseThemeDependency), ArrayHelper::asArray($themeDependency));
			}
		} else if($themeDependency) {
			$dependency = $themeDependency;
		} else if($baseThemeDependency){
			$dependency = $baseThemeDependency;
		}

		if($dependency){
			foreach(ArrayHelper::asArray($dependency) as $dependencyItem){
				$this->addExtension($dependencyItem,null,$extensionFolder,false,$position);
			}
		}
		
		if($baseThemeJs){
			foreach(ArrayHelper::asArray($baseThemeJs) as $key => $jsFile){
				if($jsFile==1){
					$jsFiles[$key] = $key;
					continue;
				}
				$jsFiles[$jsFile] = $this->getUrl($baseTheme,"$extensionFolder$extensionName/js/$jsFile");
			}
		}
		if($baseThemeCss){
			foreach(ArrayHelper::asArray($baseThemeCss) as $key => $cssFile){
				if($cssFile==1){
					$cssFiles[$key] = $key;
					continue;
				}
				$cssFiles[$cssFile] = $this->getUrl($baseTheme,"$extensionFolder$extensionName/css/$cssFile");
			}
		}
		if($themeJs){
			foreach(ArrayHelper::asArray($themeJs) as $key => $jsFile){
				if($jsFile==1){
					$jsFiles[$key] = $key;
					continue;
				}
				$jsFiles[$jsFile] = $this->getUrl($theme,"$extensionFolder$extensionName/js/$jsFile");
			}
		}
		if($themeCss){
			foreach(ArrayHelper::asArray($themeCss) as $key => $cssFile){
				if($cssFile==1){
					$cssFiles[$key] = $key;
					continue;
				}
				$cssFiles[$cssFile] = $this->getUrl($theme,"$extensionFolder$extensionName/css/$cssFile");
			}
		}
		
		$this->addJsFiles($jsFiles,$position);
		$this->addCssFiles($cssFiles);
	}
	
	public function addCustomJs($fileName,$theme=null){
		if($theme==null){
			$theme = Yii::app()->theme->getName();
		}
		if(is_array($fileName)){
			foreach($fileName as $file){
				$this->addJsFile($this->getUrl($theme,"custom/js/$file"));
			}
			return;
		}
		$this->addJsFile($this->getUrl($theme,"custom/js/$fileName"));
	}
	
	public function addCustomCss($fileName,$theme=null){
		if($theme==null){
			$theme = Yii::app()->theme->getName();
		}
		if(is_array($fileName)){
			foreach($fileName as $file){
				$this->addCssFile($this->getUrl($theme,"custom/css/$file"));
			}
			return;
		}
		$this->addCssFile($this->getUrl($theme,"custom/css/$fileName"));
	}
	
	public function run(){
		// autoload
		$theme = Yii::app()->theme->getName();
		$autoloadExtensions = array();
		$autoloadCustom = array();
		
		$autoloadExtensions = ArrayHelper::get($this->config[$theme],"__autoloadExtensions",array());
		$autoloadCustom[$theme] = ArrayHelper::get($this->config[$theme],"__autoloadCustom",array());
		
		if(ArrayHelper::get($this->config[$theme],"__autoloadBase",true)){
			$baseAutoloadExtensions = ArrayHelper::get($this->config[$this->baseTheme],"__autoloadExtensions",array());
			$autoloadExtensions = array_merge($baseAutoloadExtensions,$autoloadExtensions);
			
			$autoloadCustom[$this->baseTheme] = ArrayHelper::get($this->config[$this->baseTheme],"__autoloadCustom",array());
		}
		
		$this->addExtension("core",$this->baseTheme,"",true,"head");
		foreach($autoloadExtensions as $extension){
			$this->addExtension($extension);
		}
		
		foreach($autoloadCustom as $themeName => $config){
			$customJs = ArrayHelper::get($config,"js");
			if($customJs){
				$this->addCustomJs($customJs,$themeName);
			}
			$customCss = ArrayHelper::get($config,"css");
			if($customCss){
				$this->addCustomCss($customCss,$themeName);
			}
		}
	}
}