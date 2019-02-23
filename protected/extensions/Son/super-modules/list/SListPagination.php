<?php
class SListPagination {
	var $defaultConfig = array(
		"first" => true,
		"back" => true,
		"next" => true,
		"last" => true,
		"count" => 3,
		"view" => "ext.Son.super-modules.list.views.list-pagination"
	);
	var $config = array();
	var $list;
	var $_currentItem = null;
	var $_currentIndex = -1;
	var $activePage = -1;
	var $calculatedResult = null;
	var $baseUrl = null;

	public function __construct($config=null,$list=null){
		$this->config = $this->defaultConfig;
		if($config!=null){
			$this->config = array_replace_recursive($this->config, $config);
		}
		if($list!=null){
			$this->list = $list;
		}
	}

	public function hasCalculated(){
		return $this->calculatedResult!==null;
	}

	public function canBack(){
		return $this->config["back"] && $this->calculatedResult["can_back"];
	}

	public function canNext(){
		return $this->config["next"] && $this->calculatedResult["can_next"];
	}

	public function canFirst(){
		return $this->config["first"] && $this->calculatedResult["can_first"];
	}

	public function canLast(){
		return $this->config["last"] && $this->calculatedResult["can_last"];
	}

	public function backPage(){
		return $this->calculatedResult["back_page"];
	}

	public function nextPage(){
		return $this->calculatedResult["next_page"];
	}

	public function firstPage(){
		return $this->calculatedResult["first_page"];
	}

	public function lastPage(){
		return $this->calculatedResult["last_page"];
	}

	public function currentPage(){
		return $this->currentItem();
	}

	public function pageUrl($page=false){
		if($page===false){
			$page = $this->currentPage();
		}
		return Util::urlAppendParams($this->baseUrl,array(
			"page" => $page
		));
	}

	public function backUrl(){
		return $this->pageUrl($this->calculatedResult["back_page"]);
	}

	public function nextUrl(){
		return $this->pageUrl($this->calculatedResult["next_page"]);
	}

	public function firstUrl(){
		return $this->pageUrl($this->calculatedResult["first_page"]);
	}

	public function lastUrl(){
		return $this->pageUrl($this->calculatedResult["last_page"]);
	}

	public function loop(){
		$this->_currentIndex = $this->_currentIndex + 1;
		$this->_currentItem = ArrayHelper::get($this->calculatedResult["pages"],$this->_currentIndex,false);
		return $this->_currentItem;
	}

	public function resetLoop(){
		$this->_currentIndex = -1;
		$this->_currentItem = null;
		return true;
	}

	public function currentItem(){
		return $this->_currentItem;
	}

	public function currentItemActive(){
		return $this->activePage == $this->currentPage();
	}

	public function render($return=false){
		return Util::controller()->renderPartial($this->config["view"],array(
			"pagination" => $this
		),$return);
	}

	public function calculate($baseUrl,$numItemTotal,$activePage,$numItemPerPage){
		$this->baseUrl = $baseUrl;
		$this->activePage = $activePage;
		//
		$numPage = ceil($numItemTotal / $numItemPerPage);
		$numPageToShow = min($numPage,$this->config["count"]);
		$pages = array();
		$overflow = false;
		$startOfPageToShow; 
		$endOfPageToShow;
		if($numPageToShow==$numPage){
			// all page will be shown
			$startOfPageToShow = 1;
			$endOfPageToShow = $numPage;
		} else {
			$endOfPageToShow = $this->activePage + floor($numPageToShow / 2);
			if($endOfPageToShow > $numPage){
				$endOfPageToShow = $numPage;
			}
			$startOfPageToShow = $endOfPageToShow - $numPageToShow + 1;
			if($startOfPageToShow <= 0)
				$startOfPageToShow = 1;
		}
		$pages = range($startOfPageToShow,$endOfPageToShow);

		$this->calculatedResult = array(
			"can_back" => $this->activePage > 1,
			"can_next" => $this->activePage < $numPage,
			"can_first" => $startOfPageToShow > 1,
			"can_last" => $endOfPageToShow < $numPage,
			"back_page" => $this->activePage - 1,
			"next_page" => $this->activePage + 1,
			"first_page" => 1,
			"last_page" => $numPage,
			"active_page" => $this->activePage,
			"pages" => $pages
		);
		// TODO
		// can_next, can_back, can_first, can_last, back_page, next_page, first_page, last_page, active_page, pages
	}

	public function getCalculatedResult(){
		return $this->calculatedResult;
	}
}