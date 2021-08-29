<?php
// Some convenient functions

function graphColors(){
	$aColors = array('#7cb5ec','#434348','#90ed7d','#f7a35c','#8085e9','#f15c80','#e4d354','#8085e8','#8d4653','#91e8e1');
	return $aColors;	
}

//-----
// Is passed value odd?
function isOdd($num){
	return (is_numeric($num)&($num&1));
}

// Is passed value even?
function isEven($num){
	return (is_numeric($num)&(!($num&1)));
}

//-----
// Determine if day is a weekday
// Pass time
function isWeekday($timestamp) {
	return (date('N', $timestamp) < 6); // ISO DoW (7 = Sunday)
}

//-----
// Determine if day is a market holiday
// Pass time & DB link
// Returns false if not, "Y" if it is, "E" if it's an early closing day
function isMarketHoliday($timestamp, $mLink) {
	if (isset($_SESSION['market_holiday'])){
		return $_SESSION['market_holiday'];
	}

	$query = "
		SELECT *
		FROM ".$_SESSION['system_holidays_table']."
		WHERE date = :date
	";
	try {
		$rsHoliday = $mLink->prepare($query);
		$aValues = array(
			':date'		=> date('Y-m-d', $timestamp)
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		//return $preparedQuery;
		$rsHoliday->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}

	if ($rsHoliday->rowCount() > 0){  // It's a holiday
		$holiday = $rsHoliday->fetch(PDO::FETCH_ASSOC);
		$_SESSION['market_holiday'] = $holiday['closed'];
		$_SESSION['market_holiday_occasion'] = $holiday['occasion'];
		return $holiday['closed']; // "Y" if it is a holiday, "E" if it closes early
	}
	$_SESSION['market_holiday'] = false;
	return false;
}

//-----
// Determine if market is open
// Pass time, DB link (for holiday lookup), and whether to pad start and end times
// Returns true or false
function isMarketOpen($timestamp, $mLink, $fudge='none') {
	// Is it a weekday?
	if (isWeekday($timestamp)){
		switch($fudge){
			case 'none': // ACTUAL market hours (9:30 to 4:00 ET, 1:00 if it's an early close day)
				$begin = "9:30 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:01 PM" : "4:01 PM");
				break;

			case 'before':  // Start 30 minutes early, end on time
				$begin = "9:00 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:01 PM" : "4:01 PM");
				break;

			case 'after': // Start on time, end 30 minutes late
				$begin = "9:30 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:30 PM" : "4:30 PM");
				break;

			case 'both':  // Start 30 minutes early, end 30 minutes late
				$begin = "9:00 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:30 PM" : "4:30 PM");
				break;

			default: // Use actual market hours if not properly specified
				$begin = "9:30 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:01 PM" : "4:01 PM");
		}
		if (isMarketHoliday($timestamp, $mLink) == "Y"){  // Closed all day
			return false;
		}else{ // Open today
			if ($timestamp > strtotime(date('j-n-Y', $timestamp).' '.$begin.' America/New_York') && $timestamp < strtotime(date('j-n-Y', $timestamp).' '.$end.' America/New_York')) {
				return true;
			}
		}
	}
	return false;
}

//-----
// Calculate time past since timestamp
function get_day_name($timestamp) {
    $date = date('d/m/Y', $timestamp);
    if($date == date('d/m/Y')) {
      $day_name = '<strong>Today</strong>,';
    }else{
		$day_name = '';
	}
    return $day_name;
}

function time_past($timestamp, $type){
	$seconds	= time() - $timestamp;
	$minutes	= $seconds / 60;
	$hours		= $minutes / 60;
	$days		= $hours / 24;

	if($seconds <= 60) {
		$timePast = "".round($seconds, 0)." seconds ago";
	}elseif($minutes <= 60 ){
		$timePast = "".round($minutes, 0)." minutes ago";
	}elseif($hours <= 24 ){
		$timePast = "".round($hours, 0)." hours ago";
	}/*elseif($days < 2 ){
		$timePast = "Yesterday at ".date('g:ia', $timestamp)."";
	}*/elseif($days >= 2 && date('Y', $timestamp) == date('Y')){
		$timePast = "".date('F j', $timestamp)." at ".date('g:ia', $timestamp)."";
	}else{
		$timePast = "".date('n/j/y', $timestamp)." at ".date('g:ia', $timestamp)."";
	}

	if($type == "time"){
		$timePast = date('g:ia');
		$output = $timePast;
	}else{
		$output = $timePast;
	}
	
	if($type == "day"){
		$output = ''.get_day_name($timestamp).' '.$timePast.'';
		
	}
	
	return $output;
}

//-----
// Add Notification to Database
function add_notification($mLink, $notificationID, $memberID, $notification, $link){
	//Check to see if notifcation is LOCKED (If it is locked the user settings are ignored)
	$query = "
		SELECT locked
		FROM ".$_SESSION['members_notification_types_table']."
		WHERE notification_id=:notification_id
	";
	try{
		$rsLocked = $mLink->prepare($query);
		$aValues = array(
			':notification_id'	=> $notificationID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsLocked->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}

	$locked = $rsLocked->fetch(PDO::FETCH_ASSOC);
	
	//If the notification is Unlocked query member settings to check to see if it is ignored
	if($locked['locked'] == "0"){
		
		$query = "
			SELECT ignore_notifications
			FROM ".$_SESSION['members_settings_table']."
			WHERE member_id=:member_id
		";
		try{
			$rsSettings = $mLink->prepare($query);
			$aValues = array(
				':member_id'	=> $memberID
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsSettings->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
				file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}

		$exclude = $rsSettings->fetch(PDO::FETCH_ASSOC);
		
		if(strpos($exclude['ignore_notifications'], $notificationID) !== false){
			//Do nothing Notification Type Ignored	
		}else{
			//Add notification
		
			$query = "
				INSERT INTO ".$_SESSION['members_notification_table']." (
					notification_id,
					member_id,
					notification,
					link,
					timestamp
				) VALUE (
					:notification_id,
					:member_id,
					:notification,
					:link,
					UNIX_TIMESTAMP()
				)
			";
			
			try{
				$rsNote = $mLink->prepare($query);
				$aValues = array(
					':notification_id'	=> $notificationID,
					':member_id'		=> $memberID,
					':notification'		=> $notification,
					':link'				=> $link
				);
				$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
				$rsNote->execute($aValues);
			}
		
			catch(PDOException $error){
				// Log any error
				file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
			}
			
		}//end exclude statement
		
	}else{
		//If notification is LOCKED, add notification
		$query = "
			INSERT INTO ".$_SESSION['members_notification_table']." (
				notification_id,
				member_id,
				notification,
				link,
				timestamp
			) VALUE (
				:notification_id,
				:member_id,
				:notification,
				:link,
				UNIX_TIMESTAMP()
			)
		";
		
		try{
			$rsNote = $mLink->prepare($query);
			$aValues = array(
				':notification_id'	=> $notificationID,
				':member_id'		=> $memberID,
				':notification'		=> $notification,
				':link'				=> $link			
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsNote->execute($aValues);
		}
	
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
	}//end locked statement
	
}//END FUNCTION

//USAGE
/*

	$notificationID	= "11-001"; <- SET the type of notifcation. These types can be found in the "members_notifications_types" table column: notification_id
	$memberID		= $_SESSION['member_id']; <- Pass the member ID
	//Custom notification
	$notification	= ""; <- If the notification requires custom text for the notification, put it here
	$link			= ""; <- If the notification requires a custom link, put it here
	
	//Run function
	add_notification($mLink, $notificationID, $memberID, $notification, $link);
	
	NOTE: YOU MUST PASS THE PDO OBJECT "$mLink"  IN ORDER FOR THIS FUNCTION TO WORK

*/
//END NOTIFICATION FUNCTION



//START GET MEMBER INFO
function get_member($mLink, $memberID, $info){
	/*$query = "
		SELECT *
		FROM ".$_SESSION['members_table']."
		WHERE member_id=:member_id
	";*/
	
	$query = "
		SELECT * 
		FROM ".$_SESSION['members_table']." as m 
		INNER JOIN ".$_SESSION['members_profile_table']." as mp ON m.member_id=mp.member_id 
		WHERE m.member_id=:member_id 
		ORDER BY version DESC LIMIT 1
	";
	try{
		$rsUser = $mLink->prepare($query);
		$aValues = array(
			':member_id'	=> $memberID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsUser->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$user = $rsUser->fetch(PDO::FETCH_ASSOC);
	
	if($user['profile_image'] == ""){
		$profile = "images/profile/default-profile.png";	
	}else{
		$profile = $user['profile_image'];	
	}
	
	if($user['profile_image_tb'] == ""){
		$profileTB = "images/profile/default-profile-tb.png";	
	}else{
		$profileTB = $user['profile_image_tb'];	
	}
	
	switch($info){
		//Members Table
		case "username"				: $info = $user['username'];break;
		case "first name"			: $info = $user['name_first'];break;
		case "last name"			: $info = $user['name_last'];break;
		case "full name"			: $info = "".$user['name_first']." ".$user['name_last']."";break;
		case "admin"				: $info = $user['admin'];break;
		case "email"				: $info = $user['email'];break;
		case "joinDate"				: $info = $user['joined_timestamp'];break;
		case "city"					: $info = $user['city'];break;
		case "state"				: $info = $user['state'];break;
		
		//Members Profile Table
		case "profileImage"			: $info = $profile;break;
		case "profileImageTb"		: $info = $profileTB;break;
		case "img-profileImage"		: $info = '<img src="'.$_SESSION['site_root'].''.$profile.'" border="0" />';break;
		case "img-profileImageTb"	: $info = '<img src="'.$_SESSION['site_root'].''.$profile.'" width="40" height="40" border="0" />';break;
		case "profile_desc"			: $info = $user['about_me'];break;
		case "occupation"			: $info = $user['occupation'];break;
		
		//Customs
		case "profileLink"			: $info = '?page=04-00-00-001&member='.$memberID.'';break;
		case "usernameLink"			: $info = '<a href="?page=04-00-00-001&member='.$memberID.'">'.$user['username'].'</a>';break;
		case "all"					: $info = array(
										//Member Info
										'username'			=> $user['username'],
										'firstName'			=> $user['name_first'],
										'lastName'			=> $user['name_last'],
										'fullName'			=> "".$user['name_first']." ".$user['name_last']."",
										'admin'				=> $user['admin'],
										'email'				=> $user['email'],
										'joinDate'			=> $user['joined_timestamp'],
										'city'				=> $user['city'],
										'state'				=> $user['state'],
										//Profile
										'profileImageURL'	=> $profile,
										'profileImageTbURL'	=> $profileTB,
										'profileImage'		=> '<img src="'.$_SESSION['site_root'].''.$profile.'" border="0" />',
										'profileImageTb'	=> '<img src="'.$_SESSION['site_root'].''.$profile.'" width="40" height="40" border="0" />',
										'profile_desc'		=> $user['about_me'],
										'occupation'		=> $user['occupation'],
										//Custom
										'profileLink'		=> '?page=04-00-00-001&member='.$memberID.'',
										'usernameLink'		=> '<a href="?page=04-00-00-001&member='.$memberID.'">'.$user['username'].'</a>'
										
									);
		break;
		case "help": $info = "<ul>
								<li>username: displays passed member_id's username</li>
								<li>first name: displays passed member_id's first name</li>
								<li>last name: displays passed member_id's last name</li>
								<li>full name: displays passed member_id's first and last name</li>
								<li>admin: displays if user is an admin or not 1 = yes; 0 - no;</li>
								<li>email: displays users email address</li>
								<li>profileImage: displays url path to profile image</li>
								<li>profileImageTb: displays url path to profile image thumbnail</li>
								<li>img-profileImage: displays profile image</li>
								<li>img-profileImageTb: displays profile image thumbnail</li>
								<li>joinDate: Displays timestamp when user joined</li>
								<li>profileLink: Displays the users public profile url</li>
							  <ul>";break;
		
		
		
		default: $info = "Pass \"help\" as the last variable to see options";break;
	}
	
	return $info;
}
//END GET MEMBER INFO


//START GET TICKET INFO
function get_ticket($mLink, $ticketID, $info){
	$query = "
		SELECT *
		FROM ".$_SESSION['support_ticket_table']."
		WHERE ticket_id=:ticket_id
	";
	try{
		$rsTicket = $mLink->prepare($query);
		$aValues = array(
			':ticket_id'	=> $ticketID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsTicket->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$ticket = $rsTicket->fetch(PDO::FETCH_ASSOC);
	
	//GET LABEL FOR PRIORITY
	if($info == "priority"){
		$query = "
			SELECT label
			FROM ".$_SESSION['lists_options_table']."
			WHERE list_id='4' AND value=:value
		";
		
		try{
			$rsPriorityLabel = $mLink->prepare($query);
			$aValues = array(
				':value' 	=> $ticket['priority']
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsPriorityLabel->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$priority = $rsPriorityLabel->fetch(PDO::FETCH_ASSOC);
	}
	
	//GET LABEL BOR TICKET STATUS
	if($info == "status"){
		$query = "
			SELECT *
			FROM ".$_SESSION['lists_options_table']."
			WHERE list_id='5' AND value=:value
		";
		try{
			$rsStatus = $mLink->prepare($query);
			$aValues = array(
				':value'	=> $ticket['ticket_status']
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsStatus->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
				file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$status = $rsStatus->fetch(PDO::FETCH_ASSOC);
	}
	//END GET LABEL FOR TICKET STATUS
	
	//GET LABEL FOR TICKET TYPE
	if($info == "type"){
		$query = "
			SELECT *
			FROM ".$_SESSION['lists_options_table']."
			WHERE list_id='1' AND value=:value
		";
		try{
			$rsType = $mLink->prepare($query);
			$aValues = array(
				':value'	=> $ticket['ticket_type']
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsType->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
				file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$type = $rsType->fetch(PDO::FETCH_ASSOC);
	}
	//END GET LABEL FOR TICKET TYPE
	
	//GET LABEL FOR Problem Type
	if($info == "problem"){
		$query = "
			SELECT *
			FROM ".$_SESSION['lists_options_table']."
			WHERE list_id=:list_id AND value=:value
		";
		try{
			$rsProblem = $mLink->prepare($query);
			$aValues = array(
				':list_id'	=> $ticket['ticket_type'],
				':value'	=> $ticket['problem_type']
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsProblem->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
				file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$problem = $rsProblem->fetch(PDO::FETCH_ASSOC);
	}
	//END GET LABEL FOR TICKET TYPE
	
	//START GET FUNDS
	if($info == "funds"){
		$funds = explode("|", $ticket['fund_tickers']);
		$affectedFunds = "";
		foreach($funds as $fund){
			$member = explode("-", $fund);
			
			//echo '<li>'.$fund.'</li>';
			
			$query = "
				SELECT mf.fund_symbol, m.username
				FROM members_fund as mf
				INNER JOIN members as m ON m.member_id=:member_id
				WHERE mf.fund_id=:fund_id
			";
			try{
				$rsFund = $mLink->prepare($query);
				$aValues = array(
					':member_id'	=> $member[0],
					':fund_id'	=> $fund
				);
				$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
				$rsFund->execute($aValues);
			}
			catch(PDOException $error){
				// Log any error
					file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
			}
			$fundName = $rsFund->fetch(PDO::FETCH_ASSOC);
			
			
			$affectedFunds .= ''.$fundName['username'].':'.$fundName['fund_symbol'].'|';
				
		}
	}
	//END GET FUNDS
	
	//swithc info
	switch($info){
		case "member": $info = get_member($mLink, $ticket['member_id'], 'full name');break;
		case "assigned": $info = get_member($mLink, $ticket['assigned_to'], 'full name');break;
		case "type": $info = $type['label'];break;
		case "typeID": $info = $ticket['ticket_type'];break;
		case "status": $info = $status['label'];break;
		case "subject": $info = $ticket['ticket_subject'];break;
		case "problem": $info = $problem['label'];break;
		case "description": $info = $ticket['description'];break;
		case "ticker": $info = $ticket['stock_ticker'];break;
		case "funds": $info = $affectedFunds;break;
		case "priority": $info = $priority['label'];break;
		case "help": $info = "<ul>
								<li>member: name of who created the ticket</li>
								<li>assigned: name of who the ticket is assigned to</li>
								<li>type: Ticket Type</li>
								<li>status: Ticket Status</li>
								<li>subject: Ticket Subject</li>
								<li>problem: Problem Type</li>
								<li>description: Ticket Description</li>
								<li>ticker: Stock ticker</li>
								<li>funds: Affected funds</li>
								<li>priority: Ticket's priority</li>
							  <ul>";break;
		default: $info = "type \"help\" to view options";break;
	}
	
	return $info;
}
//END GET TICKET INFO


//START GET TICKET STATUS
function get_status($mLink, $status, $item){
	$query = "
		SELECT *
		FROM ".$_SESSION['lists_options_table']."
		WHERE list_id='5' AND value=:value
	";
	try{
		$rsStatus = $mLink->prepare($query);
		$aValues = array(
			':value'	=> $status
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsStatus->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$status = $rsStatus->fetch(PDO::FETCH_ASSOC);
	
	switch($item){
		case "status": $item = $status['label'];break;
		case "color": $item = $status['special'];break;
	}
	
	return $item;
}
//END GET TICKET STATUS

//START GET TICKET TYPE
function get_ticket_type($mLink, $ticketID){
	$query = "
		SELECT label
		FROM ".$_SESSION['lists_options_table']."
		WHERE list_id='1' AND value=:value
	";
	try{
		$rsType = $mLink->prepare($query);
		$aValues = array(
			':value'	=> $ticketID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsType->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$type = $rsType->fetch(PDO::FETCH_ASSOC);
	
	$ticketType = $type['label'];
	
	return $ticketType;
}
//END GET TICKET TYPE

//START ARRAY SERACH FUNCTION FOR COMMUNITY CA'S
function searchForId($id, $array) {
   foreach ($array as $key => $val) {
	   if ($val[0] === $id) {
		   return $key;
	   }
   }
   return null;
}
//END ARRAY SERACH FUNCTION FOR COMMUNITY CA'S

//START GET FORUM INFO
function get_forum($mLink, $forumID, $item){
	$query = "
		SELECT *
		FROM ".$_SESSION['forums_table']."
		WHERE forum_id=:forum_id
	";
	try{
		$rsForum = $mLink->prepare($query);
		$aValues = array(
			':forum_id'	=> $forumID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsForum->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$forum = $rsForum->fetch(PDO::FETCH_ASSOC);
	
	switch($item){
		case "title": $item = $forum['forum_title'];break;
		case "desc": $item = $forum['forum_description'];break;
	}
	
	return $item;
}
//END GET FORUM INFO

//START GET FORUM CATEGORY INFO
function get_category($mLink, $catID, $item){
	$query = "
		SELECT *
		FROM ".$_SESSION['forum_categories_table']."
		WHERE cat_id=:cat_id
	";
	try{
		$rsForumCat = $mLink->prepare($query);
		$aValues = array(
			':cat_id'	=> $catID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsForumCat->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$cat = $rsForumCat->fetch(PDO::FETCH_ASSOC);
	
	switch($item){
		case "title": $item = $cat['cat_title'];break;
		case "desc": $item = $cat['cat_description'];break;
	}
	
	return $item;
}
//END GET FORUM INFO

//START GET FORUM CATEGORY INFO
function get_topic($mLink, $topicID, $item){
	$query = "
		SELECT *
		FROM ".$_SESSION['forum_topics_table']."
		WHERE topic_id=:topic_id
	";
	try{
		$rsTopic = $mLink->prepare($query);
		$aValues = array(
			':topic_id'	=> $topicID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsTopic->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$topic = $rsTopic->fetch(PDO::FETCH_ASSOC);
	
	switch($item){
		case "title": $item = $topic['topic_title'];break;
		case "creator": $item = get_member($mLink, $topic['topic_creator'], 'full name');break;
		case "lastUser": $item = get_member($mLink, $topic['topic_last_user'], 'full name');break;
	}
	
	return $item;
}
//END GET FORUM INFO

//START GET FORUM TOPIC REPLIES
function get_topic_replies($mLink, $topicID){
	$query = "
	SELECT post_id
	FROM ".$_SESSION['forum_posts_table']."
	WHERE topic_id=:topic_id
	";
	
	try{
		$rsCntPosts = $mLink->prepare($query);
		$aValues = array(
			':topic_id'	=> $topicID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsCntPosts->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$cntPosts = -1;
	while($posts = $rsCntPosts->fetch(PDO::FETCH_ASSOC)){
		$cntPosts = $cntPosts + 1;	
	}
	
	return $cntPosts;
}
//END GET FORUM TOPIC REPLIES

//START GET FORUM POST REPLIES
function get_post_replies($mLink, $postID){
	
	$query = "
	SELECT post_id
	FROM ".$_SESSION['forum_posts_table']."
	WHERE parent_post_id=:post_id
	";
	
	try{
		$rsCntPosts = $mLink->prepare($query);
		$aValues = array(
			':post_id'	=> $postID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsCntPosts->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$cntPosts = 0;
	while($posts = $rsCntPosts->fetch(PDO::FETCH_ASSOC)){
		$cntPosts = $cntPosts + 1;	
	}
	
	return $cntPosts;
}
//END GET FORUM POST REPLIES



//START GET FORUM TOPIC REPLIES
function get_post_topic_replies($mLink, $topicID, $timestamp){
	
	$query = "
		SELECT post_id
		FROM community_forum_posts
		WHERE topic_id=:topic_id AND timestamp>:timestamp
	";
	
	try{
		$rsCntPosts = $mLink->prepare($query);
		$aValues = array(
			':topic_id'		=> $topicID,
			':timestamp'	=> $timestamp
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsCntPosts->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$cntPosts = 0;
	while($posts = $rsCntPosts->fetch(PDO::FETCH_ASSOC)){
		$cntPosts = $cntPosts + 1;	
		$postID = $posts['post_id'];
	}
	
	//return $cntPosts;
	return $cntPosts;
}
//END GET FORUM TOPIC REPLIES

//START UNREAD POSTS
function get_unread_post($mLink, $topicID){
	$query = "
		SELECT p.* 
		FROM community_forum_posts AS p
		WHERE p.topic_id=:topic_id 
		AND NOT EXISTS(
			SELECT *
			FROM members_forum_viewed AS v
			WHERE v.viewed_post=p.post_id AND v.member_id=:member_id
		)
		ORDER BY p.timestamp DESC
	";
	
	//Fund Symbols
	try{
		$rsPosts = $mLink->prepare($query);
		$aValues = array(
			':topic_id' 	=> $topicID,
			':member_id'	=> $_SESSION['member_id']
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsPosts->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	
	$numPosts = $rsPosts->rowCount();
	
	return $numPosts;
}

//END UNREAD POSTS

//START GET USER TITLE
function get_user_title($memberID, $mLink, $sectionID){
	
	if($memberID != ""){
		$query = "
		SELECT *
		FROM ".$_SESSION['members_flags_table']."
		WHERE member_id=:member_id
		";
		
		try{
			$rsGetFlags = $mLink->prepare($query);
			$aValues = array(
				':member_id'	=> $memberID
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsGetFlags->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$flags = $rsGetFlags->fetch(PDO::FETCH_ASSOC);
		
		$free			= $flags['free'];
		$basic 			= $flags['basic'];
		$student		= $flags['student'];
		$premium 		= $flags['premium'];
		$master 		= $flags['master'];
		$staff 			= $flags['staff'];
		$moderator 		= $flags['moderator'];
		$superModerator	= $flags['super_moderator'];
		$editor 		= $flags['editor'];
		$superEditor 	= $flags['super_editor'];
		$admin 			= $flags['admin'];
		$superAdmin 	= $flags['super_admin'];
		
	}else{
		$free			= $_SESSION['free'];
		$basic 			= $_SESSION['basic'];
		$student		= $_SESSION['student'];
		$premium 		= $_SESSION['premium'];
		$master 		= $_SESSION['master'];
		$staff 			= $_SESSION['staff'];
		$moderator 		= $_SESSION['moderator'];
		$superModerator	= $_SESSION['super_moderator'];
		$editor 		= $_SESSION['editor'];
		$superEditor 	= $_SESSION['super_editor'];
		$admin 			= $_SESSION['admin'];
		$superAdmin 	= $_SESSION['super_admin'];
	}
	
	if($free == "1"){
		$userTitle = "Free Member";
	}
	if($basic == "1"){
		$userTitle = "Basic Member";	
	}
	if($student == "1"){
		$userTitle = "Student Member";	
	}
	if($premium == "1"){
		$userTitle = "Premium Member";	
	}
	if($master == "1"){
		$userTitle = "Marketocracy Master";	
	}
	if($staff == "1"){
		$userTitle = "Staff";	
	}
	if($moderator == "1"){
		$userTitle = "Moderator";	
	}
	if($superModerator == "1"){
		$userTitle = "Moderator";	
	}
	if($editor == "1"){
		$userTitle = "Editor";	
	}
	if($superEditor == "1"){
		$userTitle = "Editor";	
	}
	if($admin == "1"){
		$userTitle = "Administrator";	
	}
	if($superAdmin == "1"){
		$userTitle = "Administrator";	
	}
	
	//Check to see if section is Forums
	if($sectionID != "04-01"/*forums*/){
		
		if($basic == "1"){
			$userTitle = "Basic Member";	
		}
		if($student == "1"){
			$userTitle = "Student Member";	
		}
		if($premium == "1"){
			$userTitle = "Premium Member";	
		}
		if($master == "1"){
			$userTitle = "Marketocracy Master";	
		}
		
		
		
		// MEMBER + MODERATOR
		if($basic == "1" && $moderator == "1"){
			$userTitle = "Basic Member | Moderator";
		}
		if($student == "1" && $moderator == "1"){
			$userTitle = "Student Member | Moderator";
		}
		if($premium == "1" && $moderator == "1"){
			$userTitle = "Premium Member | Moderator";
		}
		if($master == "1" && $moderator == "1"){
			$userTitle = "Master | Moderator";
		}
		
		if($basic == "1" && $superModerator == "1"){
			$userTitle = "Basic Member | Moderator";
		}
		if($student == "1" && $superModerator == "1"){
			$userTitle = "Student Member | Moderator";
		}
		if($premium == "1" && $superModerator == "1"){
			$userTitle = "Premium Member | Moderator";
		}
		if($master == "1" && $superModerator == "1"){
			$userTitle = "Master | Moderator";
		}
		
		// MEMBER + EDITOR
		if($basic == "1" && $editor == "1"){
			$userTitle = "Basic Member | Editor";
		}
		if($student == "1" && $editor == "1"){
			$userTitle = "Student Member | Editor";
		}
		if($premium == "1" && $editor == "1"){
			$userTitle = "Premium Member | Editor";
		}
		if($master == "1" && $editor == "1"){
			$userTitle = "Master | Editor";
		}
		
		if($basic == "1" && $superEditor == "1"){
			$userTitle = "Basic Member | Editor";
		}
		if($student == "1" && $superEditor == "1"){
			$userTitle = "Student Member | Editor";
		}
		if($premium == "1" && $superEditor == "1"){
			$userTitle = "Premium Member | Editor";
		}
		if($master == "1" && $superEditor == "1"){
			$userTitle = "Master | Editor";
		}
		
		// High Level Overrides 
		if($staff == "1"){
			$userTitle = "Staff";	
		}
		if($admin == "1"){
			$userTitle = "Administrator";	
		}
		if($superAdmin == "1"){
			$userTitle = "Super Administrator";	
		}
		
	}
	//END - Check to see if section is Forums
	
	return $userTitle;
}
//END GET USER TITLE

//START FORUM DETAILS
function get_forum_info($item, $mLink, $memberID, $forumID, $topicID){
	
	if($item == 'numPosts'){
		//Query DB for number of posts
		$query = "
			SELECT post_id
			FROM ".$_SESSION['forum_posts_table']."
			WHERE post_creator=:member_id
		";
		
		try{
			$rsGetPosts = $mLink->prepare($query);
			$aValues = array(
				':member_id' 	=> $memberID
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsGetPosts->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$numPosts = $rsGetPosts->rowCount();	
	}
	
	
	//BEGIN DATA SWITCH
	switch($item){
		case "numPosts": $item = $numPosts;break;	
	}
	
	return $item;
	
}

//END FORUM DETAILS

//START GET POST DETAILS
function get_post_info($item, $mLink, $postID){
	
	$query = "
		SELECT *
		FROM ".$_SESSION['forum_posts_table']."
		WHERE post_id=:post_id
	";
	
	try{
		$rsGetPost = $mLink->prepare($query);
		$aValues = array(
			':post_id' 	=> $postID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsGetPost->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$post = $rsGetPost->fetch(PDO::FETCH_ASSOC);
	
	switch($item){
		case "convoID"	: $item = $post['convo_id'];break;
		case "catID"	: $item = $post['cat_id'];break;
		case "parentID"	: $item = $post['parent_post_id'];break;
		case "level"	: $item = $post['level'];break;
		case "topicID"	: $item = $post['topic_id'];break;
		case "creator"	: $item = $post['post_creator'];break;
		case "timestamp": $item = $post['timestamp'];break;	
	}
	
	return $item;
}
//END GET POST DETAILS

//START FORUM POST URL

function get_post_url($mLink, $postID){
									
	//Query DB to get Post Data	
	$query = "
		SELECT p.*, t.forum_id
		FROM ".$_SESSION['forum_posts_table']." AS p
		INNER JOIN ".$_SESSION['forum_topics_table']." AS t ON p.topic_id=t.topic_id
		WHERE post_id=:post_id
	";
	
	try{
		$rsGetPost = $mLink->prepare($query);
		$aValues = array(
			':post_id' 	=> $postID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsGetPost->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$post = $rsGetPost->fetch(PDO::FETCH_ASSOC);
	
	//Get static Vars
	$forumID	= $post['forum_id'];
	$catID 		= $post['cat_id'];
	$topicID 	= $post['topic_id'];
	$convoID	= $post['convo_id'];
	
	//We need to figure out what page number the postID will be in: Note that this position will changed based on new posts being added and user's preference on how many posts per page
									
	// Get the default number of posts per page from the session var (setting stored in Database)
	$page_rows = $_SESSION['forum_posts_default'];
	
	//If for some reason the session is not set, set the default to 10 posts per page
	if(!isset($_SESSION['forum_posts_default'])){
		$page_rows = 10;	
	}
	
	//Check to see if user has defined number of rows
	if(isset($_SESSION['forum_page_rows'])){
		//If the user has set their own setting use it
		if($_SESSION['forum_page_rows'] != NULL){
			$page_rows = $_SESSION['forum_page_rows'];	
		}
	}
	
	// Query database the same way as you would on the view topic page
	$query = "
		SELECT post_id
		FROM ".$_SESSION['forum_posts_table']." 
		WHERE topic_id=:topic_id AND level='1'
		ORDER BY timestamp ASC
	";
	
	try{
		$rsPosts = $mLink->prepare($query);
		$aValues = array(
			':topic_id' 	=> $topicID
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsPosts->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	//Set counter at zero, this will be used to determin the postion of the post or parent post
	$cnt = 0;
	
	//Loop through results
	while($findPost = $rsPosts->fetch(PDO::FETCH_ASSOC)){
		
		//For each result increase the counter by 1(one)
		$cnt = $cnt + 1;
		
		//When the postID is equal to the convoID/Child break out of the loop so we will have our postion
		if($findPost['post_id'] == $convoID){
			break;
		}
		
		//If the item is a LEVEL 1, test for post id
		if($findPost['post_id'] == $postID){
			break;	
		}
	}
	
	
	//To get the page number, devide the new count by the number of posts per page, then round up. This result in the proper page number for the pagination.
	$pn = ceil($cnt/$page_rows);
	
	//Combine all the variables to generate forum post link
	if($post['level'] != "1"){
		$lastPostURL = '?page=04-01-00-004&forum='.$forumID.'&cat='.$catID.'&topic='.$topicID.'&pn='.$pn.'&child='.$convoID.'&post='.$postID.'';
	}else{
		//$lastPostURL = '?page=04-01-00-004&forum='.$forumID.'&cat='.$catID.'&topic='.$topicID.'&pn='.$pn.'&post='.$postID.'&child='.$postID.'';
		$lastPostURL = '?page=04-01-00-004&forum='.$forumID.'&cat='.$catID.'&topic='.$topicID.'&pn='.$pn.'&post='.$postID.'';
	}
	
	//return $lastPostURL;
	return $lastPostURL;
}
//END FORUM POST URL

//START GET FUND INFO
function get_funds($mLink, $fundID, $item, $switch){

	/*$query = "
		SELECT * 
		FROM ".$_SESSION['fund_table']."
		WHERE member_id=:member_id AND fund_id=:fund_id AND active=1
	";*/
	
	$query = "
		SELECT * 
		FROM ".$_SESSION['fund_table']."
		WHERE fund_id=:fund_id AND active=1
	";
	
	try{
		$rsGetFund = $mLink->prepare($query);
		$aValues = array(
			//':member_id' 	=> $_SESSION['member_id'],
			':fund_id'		=> $fundID
			
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsGetFund->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$fundInfo = $rsGetFund->fetch(PDO::FETCH_ASSOC);
	
	if($switch == "agg"){
		$query = "
			SELECT *
			FROM ".$_SESSION['fund_aggregate_table']."
			WHERE fund_id=:fund_id
			ORDER BY timestamp
			LIMIT 1
		";
		
		try{
			$rsAgg = $mLink->prepare($query);
			$aValues = array(
				':fund_id'		=> $fundID
				
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsAgg->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$fundAgg = $rsAgg->fetch(PDO::FETCH_ASSOC);
		
		$inceptDate = $fundAgg['effectiveInceptionDate'];
	}
	
	
	switch($item){
		case "fundID": 		$item = $fundInfo['fund_id'];break;
		case "fundSymbol":	$item = $fundInfo['fund_symbol'];break;
		case "fundName": 	$item = $fundInfo['fund_name'];break;	
		case "fund":		$item = ''.$fundInfo['fund_symbol'].' - '.$fundInfo['fund_name'].'';break;
		case "inceptDate": 	$item = ''.substr($inceptDate, 0, 4).'-'.substr($inceptDate, 4, 2).'-'.substr($inceptDate, 6, 2).'';break;
	}
	
	return $item;
}

function get_global_funds($mLink, $fundID, $item, $switch){

	$query = "
		SELECT * 
		FROM ".$_SESSION['fund_table']."
		WHERE fund_id=:fund_id AND active=1
	";
	
	try{
		$rsGetFund = $mLink->prepare($query);
		$aValues = array(
			':fund_id'		=> $fundID
			
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsGetFund->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$fundInfo = $rsGetFund->fetch(PDO::FETCH_ASSOC);
	
	if($switch == "agg"){
		$query = "
			SELECT *
			FROM ".$_SESSION['fund_aggregate_table']."
			WHERE fund_id=:fund_id
			ORDER BY timestamp
			LIMIT 1
		";
		
		try{
			$rsAgg = $mLink->prepare($query);
			$aValues = array(
				':fund_id'		=> $fundID
				
			);
			$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			$rsAgg->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}
		
		$fundAgg = $rsAgg->fetch(PDO::FETCH_ASSOC);
		
		$inceptDate = $fundAgg['effectiveInceptionDate'];
	}
	
	
	switch($item){
		case "fundID": 		$item = $fundInfo['fund_id'];break;
		case "fundSymbol":	$item = $fundInfo['fund_symbol'];break;
		case "fundName": 	$item = $fundInfo['fund_name'];break;	
		case "fund":		$item = ''.$fundInfo['fund_symbol'].' - '.$fundInfo['fund_name'].'';break;
		case "inceptDate": 	$item = ''.substr($inceptDate, 0, 4).'-'.substr($inceptDate, 4, 2).'-'.substr($inceptDate, 6, 2).'';break;
	}
	
	return $item;
}
//END GET FUND INFO

//START CREATE DATE RANGE FORUMLA

function createDateRangeArray($strDateFrom,$strDateTo,$special)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));
	
	
	if($special == "dash"){
		if ($iDateTo>=$iDateFrom)
		{
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo)
			{
				$iDateFrom+=86400; // add 24 hours
				array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
	}else{
		if ($iDateTo>=$iDateFrom)
		{
			array_push($aryRange,date('Ymd',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo)
			{
				$iDateFrom+=86400; // add 24 hours
				array_push($aryRange,date('Ymd',$iDateFrom));
			}
		}
	}
    return $aryRange;
}

//END CREATE DATE RANGE FORUMLA

//START DATE LIST
function date_list($mLink, $MDY, $exclude){
	if($MDY == "day"){
		$listID = '6';
	}elseif($MDY == "month"){
		$listID = '7';
	}elseif($MDY == "year"){
		$listID = '8';
	}
	
	if(isset($exclude)){
		$where = 'AND value<>'.$exclude.'';	
	}
	
	$query = "
		SELECT label, value 
		FROM ".$_SESSION['lists_options_table']."
		WHERE list_id=:list_id ".$where."
	";
	
	try{
		$rsList = $mLink->prepare($query);
		$aValues = array(
			':list_id' => $listID
			
		);
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		$rsList->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
	
	$html = '';
	
	while($list = $rsList->fetch(PDO::FETCH_ASSOC)){
		
		$html .= '<option value="'.$list['value'].'">'.$list['label'].'</option>';
			
	}
	
	return $html;
	
}
//END DATE LIST

//START GET PAGE TITLE
//END GET PAGE TITLE

//START HASHTAG FUNCTION
/*function hashtag($string) {
	
	//Set Vars
	$htag = '#';
	$aString = explode(" ", $string);
	$aStringCnt = count($aString);
	$i = 0;
	
	while($i < $aStringCnt) {
		
		if(substr($aString[$i], 0, 1) === $htag){
			$aString[$i] = '<a href="javascript:void(0);">'.$aString[$i].'</a>';	
		}
		
		$i++;
			
	}
	
	$string = implode(" ", $aString);
	
	return $string;
		
}*/

function hashtag($str){
	$regex = "/#+([a-zA-Z0-9_]+)/";
	$str = preg_replace($regex, '<a href="hashtag.php?tag=$1">$0</a>', $str);	
	return($str);
}
//END HASHTAG FUNCTION

?>
