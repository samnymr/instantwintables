<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rightglobalgroup.com/
 * @since      4.1.0
 *
 * @package    Instant_Prize_Tables
 * @subpackage Instant_Prize_Tables/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Instant_Prize_Tables
 * @subpackage Instant_Prize_Tables/public
 * @author     Right Global Group <info@rightglobalbgroup.com>
 */
class Instant_Prize_Tables_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function instant_winners() {
		global $wpdb;
		echo '<div id="winners-tabs"><a id="tab-instant" class="winners-tab-active">Instant Winners</a><a id="tab-winners">Winners</a></div>';
		echo '<div id="prize-winners" style="display: none;">';
		echo '<form class="winners-form"><input type="text" name="q" value="' . $_GET["q"] . '" /><button>Search</button></form>';
		$table_name = $wpdb->prefix . 'prize_winners';
		if($_GET["q"]) {
			$query = $wpdb->prepare("SELECT * FROM $table_name WHERE winner_name LIKE %s", "%" . $_GET["q"] . "%");
		} else {
			$query = "SELECT * FROM $table_name";
		}
		$results = $wpdb->get_results($query);
		$is_first = true;
		$last_date = "";
		if(!$_GET["q"] && is_user_logged_in()) {
			$prizes = array_filter($results, function($result) {
				return $result->winner_id == strval(get_current_user_id());
			});
			if(count($prizes)) {
				echo '<div class="instant-win-box"><h1>Your prizes</h1><ul>';
				foreach ($results as $result) {
					echo "<li><b>" . $result->prize_name . ':</b> ' . $result->winner_name . ' - Ticket #' . $result->ticket_number . "</li>";
				}
				echo '</ul></div>';
			}
		}
		foreach ($results as $result) {
			if($last_date != DateTime::createFromFormat('Y-m-d H:i:s', $result->date)->format('D j M Y')) {
				if($is_first) { 
					$is_first = false;
				} else {
					echo "</ul></div>";
				}
				echo '<div class="instant-win-box"><h1>' . DateTime::createFromFormat('Y-m-d H:i:s', $result->date)->format('D j M Y') . '</h1><ul>';
				$last_date = DateTime::createFromFormat('Y-m-d H:i:s', $result->date)->format('D j M Y');
			}
			echo "<li><b>" . $result->prize_name . ':</b> ' . $result->winner_name . ' - Ticket #' . $result->ticket_number . "</li>";
		}
		echo '</div></div>';
		echo '<div id="instant-wins">';
		echo '<form class="winners-form"><input type="text" name="q" value="' . $_GET["q"] . '" /><input type="hidden" name="instant" value="1" /><button>Search</button></form>';
		$table_name = $wpdb->prefix . 'instant_prize';
		if($_GET["q"]) {
			$query = $wpdb->prepare("SELECT * FROM $table_name WHERE winner_name LIKE %s", "%" . $_GET["q"] . "%");
		} else {
			$query = "SELECT * FROM $table_name";
		}
		$results = $wpdb->get_results($query);
		$is_first = true;
		$last_date = "";
		if(!$_GET["q"] && is_user_logged_in()) {
			$prizes = array_filter($results, function($result) {
				return $result->winner_id == strval(get_current_user_id());
			});
			if(count($prizes)) {
				echo '<div class="instant-win-box"><h1>Your prizes</h1><ul>';
				foreach ($results as $result) {
					echo "<li><b>" . $result->prize_name . ':</b> ' . $result->winner_name . ' - Ticket #' . $result->ticket_number . "</li>";
				}
				echo '</ul></div>';
			}
		}
		foreach ($results as $result) {
			if($last_date != DateTime::createFromFormat('Y-m-d H:i:s', $result->date)->format('D j M Y')) {
				if($is_first) { 
					$is_first = false;
				} else {
					echo "</ul></div>";
				}
				echo '<div class="instant-win-box"><h1>' . DateTime::createFromFormat('Y-m-d H:i:s', $result->date)->format('D j M Y') . '</h1><ul>';
				$last_date = DateTime::createFromFormat('Y-m-d H:i:s', $result->date)->format('D j M Y');
			}
			echo "<li><b>" . $result->prize_name . ':</b> ' . $result->winner_name . ' - Ticket #' . $result->ticket_number . "</li>";
		}
		echo '</div>';
		echo '<style>.instant-win-box { padding: 2rem; border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; margin-bottom: 1rem; } .instant-win-box > h1 { font-size: 1.5rem; color: var(--e-global-color-primary); } .instant-win-box > ul { list-style-image: url(/wp-content/plugins/instant-prize/public/emoji.png); } .instant-win-box > ul > li::marker {     color: var(--e-global-color-primary); } .instant-win-box > ul > li > b { color: var(--e-global-color-primary); } #winners-tabs { display: flex; gap: 1rem; padding: 2rem; justify-content: center; } #winners-tabs > a {     padding: 1rem 2rem; } .winners-tab-active { background: var(--e-global-color-primary); font-weight: bold; border-radius: 0.5rem; } .winners-form { display: flex; max-width: 25rem; gap: 1rem; margin: auto; margin-bottom: 1rem; } .winners-form > input { border-radius: 0.5rem; } .winners-form > button { border-radius: 0.5rem; }</style>';
		echo '<script>const search = Object.fromEntries(window.location.search.substr(1).split("&").map(s => s.split("="))); jQuery(function($) { $("#tab-winners").click(function() { $("#prize-winners").show(); $("#instant-wins").hide(); $(".winners-tab-active").removeClass("winners-tab-active"); $(this).addClass("winners-tab-active"); $("#instant-wins").removeClass("winners-tab-active"); }); $("#tab-instant").click(function() { $("#prize-winners").hide(); $("#instant-wins").show(); $(".winners-tab-active").removeClass("winners-tab-active"); $("#prize-winners").removeClass("winners-tab-active"); $(this).addClass("winners-tab-active"); }); if(search.instant == "1") { $("#tab-instant").click(); } });</script>';
	}
	
	public function instant_prize_league_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'instant_prize';

		$currentMonth = date('m');
		$currentYear = date('Y');
		
		$query = "SELECT COUNT(id), winner_id, winner_name
					  FROM $table_name
					  WHERE MONTH(date) = %d AND YEAR(date) = %d
					  GROUP BY winner_id";

		$instant_results = $wpdb->get_results($wpdb->prepare($query, $currentMonth, $currentYear));
		
		$table_name = $wpdb->prefix . 'prize_winners';
		$query = "SELECT COUNT(id), winner_id, winner_name
					  FROM $table_name
					  WHERE MONTH(date) = %d AND YEAR(date) = %d
					  GROUP BY winner_id";

		$prize_results = $wpdb->get_results($wpdb->prepare($query, $currentMonth, $currentYear));
		
		$totals = array();
		$names = array();
		$options = get_option( 'instant-winners_settings' );
		$instant_points = $options["instant_winners_instant_win_points"];
		$prize_points = $options["instant_winners_competition_win_points"];
		
		foreach($instant_results as $result) {
			$vars = get_object_vars($result);
			$totals[$result->winner_id] += $vars["COUNT(id)"];
			$names[$result->winner_id] = $result->winner_name;
		}
		foreach($prize_results as $result) {
			$vars = get_object_vars($result);
			$totals[$result->winner_id] += $vars["COUNT(id)"];
			$names[$result->winner_id] = $result->winner_name;
		}
		
		arsort($totals);
		
		$totals = array_slice($totals, 0, 20, true);
		
		$rows = "";
		$place = 1;

		foreach ($totals as $user_id => $total) {
			$rows .= "<tr><td>#$place</td><td>$names[$user_id]</td><td>$total</td></tr>";
			$place += 1;
		}
		
		return "<table><thead><tr><th>Place</th><th>Winner</th><th>Points</th></tr></thead><tbody>" . $rows . "</tbody></table>";
	}
	
	public function instant_prize_medals() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'instant_prize';

		$currentMonth = date('m');
		$currentYear = date('Y');
		
		$query = "SELECT COUNT(id), winner_id, winner_name
					  FROM $table_name
					  WHERE MONTH(date) = %d AND YEAR(date) = %d
					  GROUP BY winner_id";

		$instant_results = $wpdb->get_results($wpdb->prepare($query, $currentMonth, $currentYear));
		
		$table_name = $wpdb->prefix . 'prize_winners';
		$query = "SELECT COUNT(id), winner_id, winner_name
					  FROM $table_name
					  WHERE MONTH(date) = %d AND YEAR(date) = %d
					  GROUP BY winner_id";

		$prize_results = $wpdb->get_results($wpdb->prepare($query, $currentMonth, $currentYear));
		
		$totals = array();
		$names = array();
		$options = get_option( 'instant-winners_settings' );
		$instant_points = $options["instant_winners_instant_win_points"];
		$prize_points = $options["instant_winners_competition_win_points"];
		
		foreach($instant_results as $result) {
			$vars = get_object_vars($result);
			$totals[$result->winner_id] += $vars["COUNT(id)"];
			$names[$result->winner_id] = $result->winner_name;
		}
		foreach($prize_results as $result) {
			$vars = get_object_vars($result);
			$totals[$result->winner_id] += $vars["COUNT(id)"];
			$names[$result->winner_id] = $result->winner_name;
		}
		
		arsort($totals);
		
		$totals = array_slice($totals, 0, 3, true);
		$winners = "";
		
		if(!empty(array_values($totals)[0])) {
			$count = array_values($totals)[0];
			$winner = $names[array_keys($totals)[0]];
			$winners .= '<div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center; border-radius: 1rem; border: 2px solid var(--e-global-color-primary);"><svg style="margin-bottom: 1rem;" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_1_2)">
<path d="M0.7 4.775C0.3625 4.275 0.1875 3.675 0.1875 3.075C0.1875 1.375 1.5625 0 3.2625 0H16.925C18.325 0 19.6375 0.7375 20.35 1.9375L28.9125 16.2C22.8875 16.9625 17.5 19.775 13.4875 23.9375L0.7 4.775Z" fill="white"/>
<path d="M63.6625 4.775L50.8875 23.9375C46.875 19.775 41.4875 16.9625 35.4625 16.2L44.025 1.9375C44.75 0.7375 46.05 0 47.45 0H61.1125C62.8125 0 64.1875 1.375 64.1875 3.075C64.1875 3.675 64.0125 4.275 63.675 4.775H63.6625Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M16.6312 26.4437C12.5054 30.5695 10.1875 36.1652 10.1875 42C10.1875 47.8348 12.5054 53.4306 16.6312 57.5564C20.757 61.6822 26.3527 64 32.1875 64C38.0223 64 43.6181 61.6822 47.7439 57.5564C51.8697 53.4306 54.1875 47.8348 54.1875 42C54.1875 36.1652 51.8697 30.5695 47.7439 26.4437C43.6181 22.3179 38.0223 20 32.1875 20C26.3527 20 20.757 22.3179 16.6312 26.4437ZM32.7078 31.6781C33.1953 31.9406 33.5 32.4469 33.5 33V49.5H36.5C37.3297 49.5 38 50.1703 38 51C38 51.8297 37.3297 52.5 36.5 52.5H32H27.5C26.6703 52.5 26 51.8297 26 51C26 50.1703 26.6703 49.5 27.5 49.5H30.5V35.8031L28.3344 37.2516C27.6453 37.7109 26.7125 37.5281 26.2531 36.8344C25.7937 36.1406 25.9766 35.2125 26.6703 34.7531L31.1703 31.7531C31.6297 31.4438 32.2203 31.4156 32.7078 31.6781Z" fill="white"/>
</g>
<defs>
<clipPath id="clip0_1_2">
<rect width="64" height="64" fill="white"/>
</clipPath>
</defs>
</svg>
<p style="margin: 0;"><b>' . $winner . '</b></p><p style="margin: 0;">' . $count . ' points</p></div>';
		}
		if(!empty(array_values($totals)[1])) {
			$count = array_values($totals)[1];
			$winner = $names[array_keys($totals)[1]];
			$winners .= '<div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center; border-radius: 1rem; border: 2px solid var(--e-global-color-primary);"><svg style="margin-bottom: 1rem; width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_1_27)">
<path d="M0.7 4.775C0.3625 4.275 0.1875 3.675 0.1875 3.075C0.1875 1.375 1.5625 0 3.2625 0H16.925C18.325 0 19.6375 0.7375 20.35 1.9375L28.9125 16.2C22.8875 16.9625 17.5 19.775 13.4875 23.9375L0.7 4.775Z" fill="white"/>
<path d="M63.6625 4.775L50.8875 23.9375C46.875 19.775 41.4875 16.9625 35.4625 16.2L44.025 1.9375C44.75 0.7375 46.05 0 47.45 0H61.1125C62.8125 0 64.1875 1.375 64.1875 3.075C64.1875 3.675 64.0125 4.275 63.675 4.775H63.6625Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M16.6312 26.4437C12.5054 30.5695 10.1875 36.1652 10.1875 42C10.1875 47.8348 12.5054 53.4306 16.6312 57.5564C20.757 61.6822 26.3527 64 32.1875 64C38.0223 64 43.6181 61.6822 47.7439 57.5564C51.8697 53.4306 54.1875 47.8348 54.1875 42C54.1875 36.1652 51.8697 30.5695 47.7439 26.4437C43.6181 22.3179 38.0223 20 32.1875 20C26.3527 20 20.757 22.3179 16.6312 26.4437ZM28.6328 35.6156C29.3453 34.8984 30.3156 34.5 31.3234 34.5C33.4234 34.5 35.125 36.2016 35.125 38.3016C35.125 39.3094 34.7219 40.2797 34.0094 40.9922L25.0609 49.9359C24.6344 50.3672 24.5031 51.0094 24.7375 51.5719C24.9719 52.1344 25.5203 52.5 26.125 52.5H38.125C38.9547 52.5 39.625 51.8297 39.625 51C39.625 50.1703 38.9547 49.5 38.125 49.5H29.7484L36.1328 43.1109C37.4078 41.8359 38.125 40.1063 38.125 38.3016C38.125 34.5469 35.0781 31.5 31.3234 31.5C29.5187 31.5 27.7891 32.2172 26.5094 33.4922L25.0609 34.9359C24.475 35.5219 24.475 36.4734 25.0609 37.0594C25.6469 37.6453 26.5984 37.6453 27.1844 37.0594L28.6328 35.6156Z" fill="white"/>
</g>
<defs>
<clipPath id="clip0_1_27">
<rect width="64" height="64" fill="white"/>
</clipPath>
</defs>
</svg><p style="margin: 0;"><b>' . $winner . '</b></p><p style="margin: 0;">' . $count . ' points</p></div>';
		}
		if(!empty(array_values($totals)[2])) {
			$count = array_values($totals)[2];
			$winner = $names[array_keys($totals)[2]];
			$winners .= '<div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center; border-radius: 1rem; border: 2px solid var(--e-global-color-primary);"><svg style="margin-bottom: 1rem;" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_1_32)">
<path d="M0.7 4.775C0.3625 4.275 0.1875 3.675 0.1875 3.075C0.1875 1.375 1.5625 0 3.2625 0H16.925C18.325 0 19.6375 0.7375 20.35 1.9375L28.9125 16.2C22.8875 16.9625 17.5 19.775 13.4875 23.9375L0.7 4.775Z" fill="white"/>
<path d="M63.6625 4.775L50.8875 23.9375C46.875 19.775 41.4875 16.9625 35.4625 16.2L44.025 1.9375C44.75 0.7375 46.05 0 47.45 0H61.1125C62.8125 0 64.1875 1.375 64.1875 3.075C64.1875 3.675 64.0125 4.275 63.675 4.775H63.6625Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M16.6312 26.4437C12.5054 30.5695 10.1875 36.1652 10.1875 42C10.1875 47.8348 12.5054 53.4306 16.6312 57.5564C20.757 61.6822 26.3527 64 32.1875 64C38.0223 64 43.6181 61.6822 47.7439 57.5564C51.8697 53.4306 54.1875 47.8348 54.1875 42C54.1875 36.1652 51.8697 30.5695 47.7439 26.4437C43.6181 22.3179 38.0223 20 32.1875 20C26.3527 20 20.757 22.3179 16.6312 26.4437ZM26.125 31.5C25.2953 31.5 24.625 32.1703 24.625 33C24.625 33.8297 25.2953 34.5 26.125 34.5H33.5172L27.3625 40.1437C26.9078 40.5609 26.7531 41.2172 26.9781 41.7938C27.2031 42.3703 27.7563 42.75 28.375 42.75H33.25C35.1156 42.75 36.625 44.2594 36.625 46.125C36.625 47.9906 35.1156 49.5 33.25 49.5H29.5656C28.7172 49.5 27.9344 49.0219 27.5547 48.2578L27.4656 48.0797C27.0953 47.3391 26.1953 47.0391 25.4547 47.4094C24.7141 47.7797 24.4141 48.6797 24.7844 49.4203L24.8734 49.5984C25.7594 51.375 27.5781 52.5 29.5656 52.5H33.25C36.7703 52.5 39.625 49.6453 39.625 46.125C39.625 42.6047 36.7703 39.75 33.25 39.75H32.2328L38.3875 34.1062C38.8422 33.6891 38.9969 33.0328 38.7719 32.4563C38.5469 31.8797 37.9937 31.5 37.375 31.5H26.125Z" fill="white"/>
</g>
<defs>
<clipPath id="clip0_1_32">
<rect width="64" height="64" fill="white"/>
</clipPath>
</defs>
</svg>
<p style="margin: 0;"><b>' . $winner . '</b></p><p style="margin: 0;">' . $count . ' points</p></div>';
		}
		
		return '<div style="width: 100%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;" id="instant-medals">
		' . $winners . '</div>
		<script>
			function getMedals() {
				fetch("/wp-admin/admin-ajax.php?action=instant_medals&nonce=' . wp_create_nonce("instant_medals_nonce") . '", {method: "POST"}).then(r => {
					r.text().then(t => {
						jQuery("#instant-medals").parent().html(t);
						console.log("set");
					});
				});
			}
			setTimeout(getMedals, 30000);
		</script>';
	}
	
	public function instant_prize_user_medal() {
		if(is_user_logged_in()) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'instant_prize';

			$currentMonth = date('m');
			$currentYear = date('Y');

			$query = "SELECT COUNT(id), winner_id, winner_name
						  FROM $table_name
						  WHERE MONTH(date) = %d AND YEAR(date) = %d
						  GROUP BY winner_id";

			$instant_results = $wpdb->get_results($wpdb->prepare($query, $currentMonth, $currentYear));

			$table_name = $wpdb->prefix . 'prize_winners';
			$query = "SELECT COUNT(id), winner_id, winner_name
						  FROM $table_name
						  WHERE MONTH(date) = %d AND YEAR(date) = %d
						  GROUP BY winner_id";

			$prize_results = $wpdb->get_results($wpdb->prepare($query, $currentMonth, $currentYear));

			$totals = array();
			$names = array();
			$options = get_option( 'instant-winners_settings' );
			$instant_points = $options["instant_winners_instant_win_points"];
			$prize_points = $options["instant_winners_competition_win_points"];

			foreach($instant_results as $result) {
				$vars = get_object_vars($result);
				$totals[$result->winner_id] += $vars["COUNT(id)"];
				$names[$result->winner_id] = $result->winner_name;
			}
			foreach($prize_results as $result) {
				$vars = get_object_vars($result);
				$totals[$result->winner_id] += $vars["COUNT(id)"];
				$names[$result->winner_id] = $result->winner_name;
			}

			arsort($totals);

			$totals = array_slice($totals, 0, 3, true);
			$winners = "";
			$found = false;

			if(!empty(array_values($totals)[0])) {
				if(intval(array_keys($totals)[0]) == get_current_user_id()) {
					$found = true;
					$count = array_values($totals)[0];
					$winner = $names[array_keys($totals)[0]];
					$winners = '<div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center;"><svg style="margin-bottom: 1rem;" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
		<g clip-path="url(#clip0_1_2)">
		<path d="M0.7 4.775C0.3625 4.275 0.1875 3.675 0.1875 3.075C0.1875 1.375 1.5625 0 3.2625 0H16.925C18.325 0 19.6375 0.7375 20.35 1.9375L28.9125 16.2C22.8875 16.9625 17.5 19.775 13.4875 23.9375L0.7 4.775Z" fill="white"/>
		<path d="M63.6625 4.775L50.8875 23.9375C46.875 19.775 41.4875 16.9625 35.4625 16.2L44.025 1.9375C44.75 0.7375 46.05 0 47.45 0H61.1125C62.8125 0 64.1875 1.375 64.1875 3.075C64.1875 3.675 64.0125 4.275 63.675 4.775H63.6625Z" fill="white"/>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M16.6312 26.4437C12.5054 30.5695 10.1875 36.1652 10.1875 42C10.1875 47.8348 12.5054 53.4306 16.6312 57.5564C20.757 61.6822 26.3527 64 32.1875 64C38.0223 64 43.6181 61.6822 47.7439 57.5564C51.8697 53.4306 54.1875 47.8348 54.1875 42C54.1875 36.1652 51.8697 30.5695 47.7439 26.4437C43.6181 22.3179 38.0223 20 32.1875 20C26.3527 20 20.757 22.3179 16.6312 26.4437ZM32.7078 31.6781C33.1953 31.9406 33.5 32.4469 33.5 33V49.5H36.5C37.3297 49.5 38 50.1703 38 51C38 51.8297 37.3297 52.5 36.5 52.5H32H27.5C26.6703 52.5 26 51.8297 26 51C26 50.1703 26.6703 49.5 27.5 49.5H30.5V35.8031L28.3344 37.2516C27.6453 37.7109 26.7125 37.5281 26.2531 36.8344C25.7937 36.1406 25.9766 35.2125 26.6703 34.7531L31.1703 31.7531C31.6297 31.4438 32.2203 31.4156 32.7078 31.6781Z" fill="white"/>
		</g>
		<defs>
		<clipPath id="clip0_1_2">
		<rect width="64" height="64" fill="white"/>
		</clipPath>
		</defs>
		</svg>
		<p style="margin: 0;"><b>' . $winner . '</b></p><p style="margin: 0;">' . $count . ' points</p></div>';
				}

			}
			if(!empty(array_values($totals)[1])) {
				if(intval(array_keys($totals)[1]) == get_current_user_id() && $found == false) {
					$found = true;
					$count = array_values($totals)[1];
					$winner = $names[array_keys($totals)[1]];
					$winners .= '<div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center;"><svg style="margin-bottom: 1rem; width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
		<g clip-path="url(#clip0_1_27)">
		<path d="M0.7 4.775C0.3625 4.275 0.1875 3.675 0.1875 3.075C0.1875 1.375 1.5625 0 3.2625 0H16.925C18.325 0 19.6375 0.7375 20.35 1.9375L28.9125 16.2C22.8875 16.9625 17.5 19.775 13.4875 23.9375L0.7 4.775Z" fill="white"/>
		<path d="M63.6625 4.775L50.8875 23.9375C46.875 19.775 41.4875 16.9625 35.4625 16.2L44.025 1.9375C44.75 0.7375 46.05 0 47.45 0H61.1125C62.8125 0 64.1875 1.375 64.1875 3.075C64.1875 3.675 64.0125 4.275 63.675 4.775H63.6625Z" fill="white"/>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M16.6312 26.4437C12.5054 30.5695 10.1875 36.1652 10.1875 42C10.1875 47.8348 12.5054 53.4306 16.6312 57.5564C20.757 61.6822 26.3527 64 32.1875 64C38.0223 64 43.6181 61.6822 47.7439 57.5564C51.8697 53.4306 54.1875 47.8348 54.1875 42C54.1875 36.1652 51.8697 30.5695 47.7439 26.4437C43.6181 22.3179 38.0223 20 32.1875 20C26.3527 20 20.757 22.3179 16.6312 26.4437ZM28.6328 35.6156C29.3453 34.8984 30.3156 34.5 31.3234 34.5C33.4234 34.5 35.125 36.2016 35.125 38.3016C35.125 39.3094 34.7219 40.2797 34.0094 40.9922L25.0609 49.9359C24.6344 50.3672 24.5031 51.0094 24.7375 51.5719C24.9719 52.1344 25.5203 52.5 26.125 52.5H38.125C38.9547 52.5 39.625 51.8297 39.625 51C39.625 50.1703 38.9547 49.5 38.125 49.5H29.7484L36.1328 43.1109C37.4078 41.8359 38.125 40.1063 38.125 38.3016C38.125 34.5469 35.0781 31.5 31.3234 31.5C29.5187 31.5 27.7891 32.2172 26.5094 33.4922L25.0609 34.9359C24.475 35.5219 24.475 36.4734 25.0609 37.0594C25.6469 37.6453 26.5984 37.6453 27.1844 37.0594L28.6328 35.6156Z" fill="white"/>
		</g>
		<defs>
		<clipPath id="clip0_1_27">
		<rect width="64" height="64" fill="white"/>
		</clipPath>
		</defs>
		</svg><p style="margin: 0;"><b>' . $winner . '</b></p><p style="margin: 0;">' . $count . ' points</p></div>';
				}
		}
		if(!empty(array_values($totals)[2])) {
				if(intval(array_keys($totals)[2]) == get_current_user_id() && $found == false) {
					$found = true;
					$count = array_values($totals)[2];
					$winner = $names[array_keys($totals)[2]];
				$winners .= '<div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center;"><svg style="margin-bottom: 1rem;" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
	<g clip-path="url(#clip0_1_32)">
	<path d="M0.7 4.775C0.3625 4.275 0.1875 3.675 0.1875 3.075C0.1875 1.375 1.5625 0 3.2625 0H16.925C18.325 0 19.6375 0.7375 20.35 1.9375L28.9125 16.2C22.8875 16.9625 17.5 19.775 13.4875 23.9375L0.7 4.775Z" fill="white"/>
	<path d="M63.6625 4.775L50.8875 23.9375C46.875 19.775 41.4875 16.9625 35.4625 16.2L44.025 1.9375C44.75 0.7375 46.05 0 47.45 0H61.1125C62.8125 0 64.1875 1.375 64.1875 3.075C64.1875 3.675 64.0125 4.275 63.675 4.775H63.6625Z" fill="white"/>
	<path fill-rule="evenodd" clip-rule="evenodd" d="M16.6312 26.4437C12.5054 30.5695 10.1875 36.1652 10.1875 42C10.1875 47.8348 12.5054 53.4306 16.6312 57.5564C20.757 61.6822 26.3527 64 32.1875 64C38.0223 64 43.6181 61.6822 47.7439 57.5564C51.8697 53.4306 54.1875 47.8348 54.1875 42C54.1875 36.1652 51.8697 30.5695 47.7439 26.4437C43.6181 22.3179 38.0223 20 32.1875 20C26.3527 20 20.757 22.3179 16.6312 26.4437ZM26.125 31.5C25.2953 31.5 24.625 32.1703 24.625 33C24.625 33.8297 25.2953 34.5 26.125 34.5H33.5172L27.3625 40.1437C26.9078 40.5609 26.7531 41.2172 26.9781 41.7938C27.2031 42.3703 27.7563 42.75 28.375 42.75H33.25C35.1156 42.75 36.625 44.2594 36.625 46.125C36.625 47.9906 35.1156 49.5 33.25 49.5H29.5656C28.7172 49.5 27.9344 49.0219 27.5547 48.2578L27.4656 48.0797C27.0953 47.3391 26.1953 47.0391 25.4547 47.4094C24.7141 47.7797 24.4141 48.6797 24.7844 49.4203L24.8734 49.5984C25.7594 51.375 27.5781 52.5 29.5656 52.5H33.25C36.7703 52.5 39.625 49.6453 39.625 46.125C39.625 42.6047 36.7703 39.75 33.25 39.75H32.2328L38.3875 34.1062C38.8422 33.6891 38.9969 33.0328 38.7719 32.4563C38.5469 31.8797 37.9937 31.5 37.375 31.5H26.125Z" fill="white"/>
	</g>
	<defs>
	<clipPath id="clip0_1_32">
	<rect width="64" height="64" fill="white"/>
	</clipPath>
	</defs>
	</svg>
	<p style="margin: 0;"><b>' . $winner . '</b></p><p style="margin: 0;">' . $count . ' points</p></div>';
			}
		}
			
			if($found == true) {
				return '<div style="width: 100%; display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; border-radius: 1rem; border: 2px solid var(--e-global-color-primary);"><div style="display: flex; flex-direction: column; padding: 2rem; justify-content: center; align-items: center; text-align: center;"><p><b>You are currently holding a medal</b></p></div>' . $winners . '</div>';
			} else {
				return "";
			}
		}
	}
	
	public function instant_prize_medals_ajax() {
		if(!wp_verify_nonce($_REQUEST['nonce'], "instant_medals_nonce")) {
			exit();
		}
		
		$medals = $this->instant_prize_medals();
		
		echo $medals;
		die();
	}
}