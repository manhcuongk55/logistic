<?php
class GoogleAds {
	public static function generateAdsAt($pos){
		$ads = Util::param2("google_ads",$pos);
		$count = count($ads);
		if($count){
			$index = rand(0,$count-1);
			echo "<div class='text-center'>" . $ads[$index]["code"] . "</div>";
		} else {
			echo "No ads available!";
		}
	}
}