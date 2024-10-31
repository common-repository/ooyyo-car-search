<?php 
/*
Plugin Name: OOYYO Car Search
Description: Search for car ads in OOYYO.com database
Version: 1.0
Author URI: https://www.ooyyo.com
*/

class OOYYO_Car_Search extends WP_Widget{

	public $LANG = '47';
	public $CNTR = '10';
	public $CURR = '3';

	function __construct(){
		parent::__construct(
			'ooyyo_widget',
			__( 'OOYYO Car Search' , 'ooyyo_widget'),
			array( 'description' => __( 'Browse used cars' , 'ooyyo_widget') )
		);
	}
	function form($instance){

		$langs = $this->getLanguages();
		$countries = $this->getCountries();
		$currs = $this->getCurrencies();

		$lang = $this->LANG;
		$country = $this->CNTR;
		$curr = $this->CURR;

		if(isset($instance['language'])){
			$lang = $instance['language'];
		}
		if(isset($instance['country'])){
			$country = $instance['country'];
		}
		if(isset($instance['currency'])){
			$curr = $instance['currency'];
		}

		echo '<p>';
		echo '<label for="' . $this->get_field_id('language') . '"> Language: ';
		echo '<select class="widefat" name="'.$this->get_field_name('language').'" id="'.$this->get_field_id('language').'">';
		foreach ($langs as $k => $v) {
			$selected = '';
			if($v['id'] == $lang){
				$selected = 'selected';
			}
			echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['title'].'</option>';
		}
		echo '</select>';
		echo '</label>';
		echo '</p>';
		
		echo '<p>';
		echo '<label for="' . $this->get_field_id('country') . '"> Country: ';
		echo '<select class="widefat" name="'.$this->get_field_name('country').'" id="'.$this->get_field_id('country').'">';
		foreach ($countries as $k => $v) {
			$selected = '';
			if($v['id'] == $country){
				$selected = 'selected';
			}
			echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['title'].'</option>';
		}
		echo '</select>';
		echo '</label>';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id('currency') . '"> Currency: ';
		echo '<select class="widefat" name="'.$this->get_field_name('currency').'" id="'.$this->get_field_id('currency').'">';
		foreach ($currs as $k => $v) {
			$selected = '';
			if($v['id'] == $curr){
				$selected = 'selected';
			}
			echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['title'].'</option>';
		}
		echo '</select>';
		echo '</label>';
		echo '</p>';
		
	}
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['language'] = strip_tags($new_instance['language']);
		$instance['country'] = strip_tags($new_instance['country']);
		$instance['currency'] = strip_tags($new_instance['currency']);
		return $instance;
	}
	function widget($args, $instance){

		$lang = $this->LANG;
		$country = $this->CNTR;
		$curr = $this->CURR;

		if(isset($instance['language'])){
			$lang = $instance['language'];
		}
		if(isset($instance['country'])){
			$country = $instance['country'];
		}
		if(isset($instance['currency'])){
			$curr = $instance['currency'];
		}

		?>
		<div 
			id='ooyyo-widget-qs' 
			data-country='<?php echo $country; ?>'
			data-language='<?php echo $lang; ?>' 
			data-currency='<?php echo $curr; ?>'>
		</div>
		<script type="text/javascript">
			var ooyyo={idFrameQS:"oyowfqs",idFrameResults:"oyowfrs",origin:window.location.origin,addEvent:function(c,b,a){if(c.attachEvent){c.attachEvent("on"+b,a)}else{c.addEventListener(b,a)}},showResults:function(b){var a=this;var c=document.getElementById(a.idFrameResults);if(c){a.updateResults(b)}else{a.initResults(b)}},prepareParams:function(c){var a=["&"];for(var b in c){if(c.hasOwnProperty(b)){a.push(b);a.push("=");a.push(c[b]);a.push("&")}}return a.join("")},initQS:function(){var a=this;var c=document.createElement("iframe");var e=document.getElementById("ooyyo-widget-qs");var g=e.getAttribute("data-country");var d=e.getAttribute("data-language");var f=e.getAttribute("data-currency");var b="https://www.ooyyo.com/widget-qs?origin="+a.origin;if(g!==null){b+="&idCountry="+g}if(d!==null){b+="&idLanguage="+d}if(f!==null){b+="&idCurrency="+f}c.setAttribute("src",b);c.setAttribute("id",a.idFrameQS);c.setAttribute("scrolling","no");c.style.overflow="hidden";c.style.border="none";c.style.width="100%";c.style.height="300px";e.parentNode.insertBefore(c,e.nextSibling);return a},initResults:function(c){var a=this;var b=document.createElement("iframe");b.setAttribute("src","https://www.ooyyo.com/widget-results?origin="+a.origin+a.prepareParams(c));b.setAttribute("id",a.idFrameResults);b.style.overflow="hidden";b.style.border="none";b.style.position="fixed";b.style.top="0";b.style.width="100%";b.style.left="0";b.style.height="100%";b.style.zIndex="100000";document.body.appendChild(b)},updateResults:function(c){var a=this;var b={action:"refresh",params:c};document.getElementById(a.idFrameResults).contentWindow.postMessage(JSON.stringify(b),"https://www.ooyyo.com");document.getElementById(a.idFrameResults).style.display="block"},onMessage:function(c){var a=this;var b=JSON.parse(c.data);switch(b.action){case"size":document.getElementById(a.idFrameQS).style.height=b.height+"px";break;case"results":a.showResults(b.params);break;case"detail":window.open(b.url,"_blank");break;case"close":document.getElementById(a.idFrameResults).style.display="none";break}},init:function(){var a=this;a.initQS().addEvent(window,"message",function(b){a.onMessage(b)})}}.init();
		</script>
		<?php
	}
	function getLanguages(){
		$ret = [];

		$json = $this->callService("https://www.ooyyo.com/ooyyo-services/resources/indexpage/languageweburls");

		if($json != null){
			$langs = $json['all'];

			foreach ($langs as $k => $v) {
    			array_push($ret, array(
    				'id' => $k,
    				'title' => $v['title']
    			));
			}	
		}

		return $ret;
	}
	function getCountries(){
		$ret = [];

		$json = $this->callService("https://www.ooyyo.com/ooyyo-services/resources/indexpage/countryweburls");

		if($json != null){
			foreach ($json as $k => $v) {
    			array_push($ret, array(
    				'id' => $k,
    				'title' => $v['title']
    			));
			}	
		}

		return $ret;
	}
	function getCurrencies(){
		$ret = [];

		$json = $this->callService("https://www.ooyyo.com/ooyyo-services/resources/common/currenciesweb");

		if($json != null){
			if($json['popular'] != null){
				$pop = $json['popular'];
				foreach ($pop as $k => $v) {
    				array_push($ret, array(
    					'id' => $v['idCurrency'],
    					'title' => $v['iso'] . " - " .  $v['description']
    				));
				}
			}
			if($json['all'] != null){
				$all = $json['all'];
				foreach ($all as $k => $v) {
    				array_push($ret, array(
    					'id' => $v['idCurrency'],
    					'title' => $v['iso'] . " - " .  $v['description']
    				));
				}		
			}
		}
		return $ret;
	}
	function callService($endpoint){
		$params = array( 
			'idCountry' => '7', 
			'idLanguage' => '47', 
			'idCurrency' => '3', 
			'idDomain' => '1', 
			'isNew' => '0' 
			);
		$response = wp_remote_post( $endpoint, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body' => json_encode($params)
		    )
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
		   	echo "Something went wrong: $error_message";
		   	return null;
		} else {
			return json_decode(wp_remote_retrieve_body($response), true);
		}
	}
}

add_action('widgets_init', function(){
	register_widget('OOYYO_Car_Search');
})

?>
