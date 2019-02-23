<?php
class SHtml {
	var $registeredHtml = array();
	var $tempPosition = null;

	public function htmlAt($position,$html){
		if(!isset($this->registeredHtml[$position]))
			$this->registeredHtml[$position] = array();
		$this->registeredHtml[$position][] = $html;
	}

	public function beginHtmlAt($position){
		$this->tempPosition = $position;
		ob_start();
	}

	public function endHtml(){
		$html = ob_get_clean();
		$this->htmlAt($this->tempPosition,$html);
		$this->tempPosition = null;
	}

	public function renderHtmlAt($position){
		if(!isset($this->registeredHtml[$position]))
			return;
		foreach($this->registeredHtml[$position] as $html){
			if(is_callable($html)){
				$html();
				continue;
			}
			echo $html;
		}
	}
}