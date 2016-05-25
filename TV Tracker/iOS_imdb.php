<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Free PHP IMDb Scraper API for new IMDb Template</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="syntaxhighlighter/scripts/shCore.js"></script>
<script type="text/javascript" src="syntaxhighlighter/scripts/shBrushPhp.js"></script>
<link href="syntaxhighlighter/styles/shCore.css" rel="stylesheet" type="text/css" />
<link href="syntaxhighlighter/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
$(function(){
	SyntaxHighlighter.all();
});
</script>
<style type="text/css">
body {
	margin: 2px;
}
body, td, th {
	font-size: 12px;
}
</style>
<!--Google Analytics-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1115506-8']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!--End Google Analytics-->
</head>

<body>
<script type="syntaxhighlighter" class="brush: php"><![CDATA[
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////
// Free PHP IMDb Scraper API for the new IMDb Template.
// Version: 4.0
// Author: Abhinay Rathore
// Website: http://www.AbhinayRathore.com
// Blog: http://web3o.blogspot.com
// Demo: http://lab.abhinayrathore.com/imdb/
// More Info: http://web3o.blogspot.com/2010/10/php-imdb-scraper-for-new-imdb-template.html
// Last Updated: Feb 20, 2013
/////////////////////////////////////////////////////////////////////////////////////////////////////////

class Imdb
{	
	// Get movie information by Movie Title.
	// This method searches the given title on Google, Bing or Ask to get the best possible match.
	public function getMovieInfo($title, $getExtraInfo = true)
	{
		$imdbId = $this->getIMDbIdFromSearch(trim($title));
		if($imdbId === NULL){
			$arr = array();
			$arr['error'] = "No Title found in Search Results!";
			return $arr;
		}
		return $this->getMovieInfoById($imdbId, $getExtraInfo);
	}
	
	// Get movie information by IMDb Id.
	public function getMovieInfoById($imdbId, $getExtraInfo = true)
	{
		$arr = array();
		$imdbUrl = "http://www.imdb.com/title/" . trim($imdbId) . "/";
		return $this->scrapeMovieInfo($imdbUrl, $getExtraInfo);
	}
	
	// Scrape movie information from IMDb page and return results in an array.
	private function scrapeMovieInfo($imdbUrl, $getExtraInfo = true)
	{
		$arr = array();
		$html = $this->geturl("${imdbUrl}combined");
		$title_id = $this->match('/<link rel="canonical" href="http:\/\/www.imdb.com\/title\/(tt\d+)\/combined" \/>/ms', $html, 1);
		if(empty($title_id) || !preg_match("/tt\d+/i", $title_id)) {
			$arr['error'] = "No Title found on IMDb!";
			return $arr;
		}
		
		$arr['poster'] = $this->match('/<div class="photo">.*?<a name="poster".*?><img.*?src="(.*?)".*?<\/div>/ms', $html, 1);
		$arr['poster_large'] = "";
		$arr['poster_full'] = "";
		if ($arr['poster'] != '' && strpos($arr['poster'], "media-imdb.com") > 0) { //Get large and small posters
			$arr['poster'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY200.jpg", $arr['poster']);
			$arr['poster_large'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY500.jpg", $arr['poster']);
			$arr['poster_full'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY0.jpg", $arr['poster']);
		} else {
			$arr['poster'] = "";
		}

		
		return $arr;
	}
	
	// Scan all Release Dates.
	private function getReleaseDates($html){
		$releaseDates = array();
		foreach($this->match_all('/<tr>(.*?)<\/tr>/ms', $this->match('/Date<\/th><\/tr>(.*?)<\/table>/ms', $html, 1), 1) as $r) {
			$country = trim(strip_tags($this->match('/<td><b>(.*?)<\/b><\/td>/ms', $r, 1)));
			$date = trim(strip_tags($this->match('/<td align="right">(.*?)<\/td>/ms', $r, 1)));
			array_push($releaseDates, $country . " = " . $date);
		}
		return $releaseDates;
	}

	// Scan all AKA Titles.
	private function getAkaTitles($html){
		$akaTitles = array();
		foreach($this->match_all('/<tr>(.*?)<\/tr>/msi', $this->match('/Also Known As(.*?)<\/table>/ms', $html, 1), 1) as $m) {
			$akaTitleMatch = $this->match_all('/<td>(.*?)<\/td>/ms', $m, 1);
			$akaTitle = trim($akaTitleMatch[0]);
			$akaCountry = trim($akaTitleMatch[1]);
			array_push($akaTitles, $akaTitle . " = " . $akaCountry);
		}
		return $akaTitles;
	}

	// Collect all Media Images.
	private function getMediaImages($titleId){
		$url  = "http://www.imdb.com/title/" . $titleId . "/mediaindex";
		$html = $this->geturl($url);
		$media = array();
		$media = array_merge($media, $this->scanMediaImages($html));
		foreach($this->match_all('/<a href="\?page=(.*?)">/ms', $this->match('/<span style="padding: 0 1em;">(.*?)<\/span>/ms', $html, 1), 1) as $p) {
			$html = $this->geturl($url . "?page=" . $p);
			$media = array_merge($media, $this->scanMediaImages($html));
		}
		return $media;
	}

	// Scan all media images.
	private function scanMediaImages($html){
		$pics = array();
		foreach($this->match_all('/src="(.*?)"/ms', $this->match('/<div class="thumb_list" style="font-size: 0px;">(.*?)<\/div>/ms', $html, 1), 1) as $i) {
			array_push($pics, preg_replace('/_V1\..*?.jpg/ms', "_V1._SY0.jpg", $i));
		}
		return $pics;
	}
	
	// Get recommended titles by IMDb title id.
	public function getRecommendedTitles($titleId){
		$json = $this->geturl("http://www.imdb.com/widget/recommendations/_ajax/get_more_recs?specs=p13nsims%3A${titleId}");
		$resp = json_decode($json, true);
		$arr = array();
		if(isset($resp["recommendations"])) {
			foreach($resp["recommendations"] as $val) {
				$name = $this->match('/title="(.*?)"/msi', $val['content'], 1);
				$arr[$val['tconst']] = $name;
			}
		}
		return $arr;
	}

	//************************[ Extra Functions ]******************************

	// Movie title search on Google, Bing or Ask. If search fails, return FALSE.
	private function getIMDbIdFromSearch($title, $engine = "google"){
		switch ($engine) {
			case "google":  $nextEngine = "bing";  break;
			case "bing":    $nextEngine = "ask";   break;
			case "ask":     $nextEngine = FALSE;   break;
			case FALSE:     return NULL;
			default:        return NULL;
		}
		$url = "http://www.${engine}.com/search?q=imdb+" . rawurlencode($title);
		$ids = $this->match_all('/<a.*?href="http:\/\/www.imdb.com\/title\/(tt\d+).*?".*?>.*?<\/a>/ms', $this->geturl($url), 1);
		if (!isset($ids[0]) || empty($ids[0])) //if search failed
			return $this->getIMDbIdFromSearch($title, $nextEngine); //move to next search engine
		else
			return $ids[0]; //return first IMDb result
	}
	
	private function geturl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	private function match_all_key_value($regex, $str, $keyIndex = 1, $valueIndex = 2){
		$arr = array();
		preg_match_all($regex, $str, $matches, PREG_SET_ORDER);
		foreach($matches as $m){
			$arr[$m[$keyIndex]] = $m[$valueIndex];
		}
		return $arr;
	}
	
	private function match_all($regex, $str, $i = 0){
		if(preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches[$i];
	}

	private function match($regex, $str, $i = 0){
		if(preg_match($regex, $str, $match) == 1)
			return $match[$i];
		else
			return false;
	}
}
?>
]]></script>
</body>
</html>
