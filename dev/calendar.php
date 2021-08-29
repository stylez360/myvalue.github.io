<style type="text/css">
table {
    width:199px;
    border:0px solid #888;
    border-collapse:collapse;
}

td {
    width:27px;
	font-family: Arial, sans-serif;
    border-collapse:collapse;
    border:1px solid #888;
    text-align:center;
}

.calblock{
	float:none;
}

.onecal{
	vertical-align:top;
    background-color: #E9ECEF;

}
.cal{
	border-style:solid;
	border-width:thin;
	border-color:#E9ECEF;
	float:left;
	padding:3px;
}

.days{
    background-color: #F1F3F5;
}

.hasday{
    background-color: #FFFFDE;
}
.noday{
    background-color: #E9ECEF;
}

th {
	font-family: Arial, sans-serif;
    border-collapse:collapse;
    border:1px solid #888;
    background-color: #E9ECEF;
}
</style>


<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set("America/Chicago");
/*
	showCalendar( $monthToshow = null, $yearToShow = null, $firstDayOfWeek = 0 )

	Shows a single month calendar. All parameters use Day, Month and Year encodings specified by PHP GETDATE.
	monthToShow: month of the calendar (relative to year ... typically 1 - 12, but
					can be larger to extend into following year(s))
	yearToShow: year of the calendar (4 digits)
	If either monthToShow or yearToShow are not supplied, then assumes current month in current year.
	firstDayOfWeek: specifies the first day of each displayed week in the calendar, (0 - 6)
					default to Sunday (0)
*/
FUNCTION showCalendar( $monthToShow = NULL  , $yearToShow = NULL , $firstDayOfWeek = 0 ){
	IF (($monthToShow === NULL) OR ($yearToShow === NULL)) {
		$today = GETDATE();
		$monthToShow = $today['mon'];
		$yearToShow = $today['year'];
	}
	ELSE {
		$today = GETDATE(MKTIME(0,0,0,$monthToShow,1,$yearToShow));
	}
	// get first and last days of the month
    $firstDay = GETDATE(MKTIME(0,0,0,$monthToShow,1,$yearToShow));
    $lastDay  = GETDATE(MKTIME(0,0,0,$monthToShow+1,0,$yearToShow)); //trick! day = 0

    // Create a table with the necessary header information
    ECHO '<div class = cal><table>';
    ECHO '  <tr><th colspan="7">'.$today['month']."&nbsp;&nbsp;&nbsp;".$today['year']."</th></tr>";
    ECHO '<tr class="days">';
	$dayText = ARRAY ( // this array is used to simplify the display of calendar headers
		// the entries just specify the text to show for the days in the header
		0 => 'Su','Mo','Tu','We','Th','Fr','Sa',
		// duplicate the first 6 entries to simplify display of calendar headers in a loop
		'Su','Mo','Tu','We','Th','Fr'
	);
	FOR ($i=0;$i<7;$i++){ // put 7 days in header, starting at appropriate day ($firstDayOfWeek)
		ECHO '<td>'.$dayText[$firstDayOfWeek + $i].'</td>';
    }
	ECHO '</tr><tr>';
    // Display the first calendar row with correct start of week
	IF ( $firstDayOfWeek <= $firstDay['wday'] ) {
		$blanks = $firstDay['wday'] - $firstDayOfWeek;
	}
	ELSE {
		$blanks = $firstDay['wday'] - $firstDayOfWeek + 7;
	}
	FOR($i=1;$i<=$blanks;$i++){
        ECHO '<td class="noday"> </td>';
    }
    $actday = 0; // used to count and represent each day
	// Note: loop below starts using the residual value of $i from loop above
    FOR( /* use value of $i resulting from last loop*/ ;$i<=7;$i++){
        ECHO '<td class=hasday>'.++$actday.'</td>';
    }
    ECHO '</tr>';

    // Get how many complete weeks are in the actual month
    $fullWeeks = FLOOR(($lastDay['mday']-$actday)/7);
    FOR ($i=0;$i<$fullWeeks;$i++){
		ECHO '<tr>';
        FOR ($j=0;$j<7;$j++){
               ECHO '<td class=hasday>'.++$actday.'</td>';
        }
        ECHO '</tr>';
    }

    //Now display the partial last week of the month (if there is one)
    IF ($actday < $lastDay['mday']){
        ECHO '<tr>';
        $actday++;
        FOR ($i=0;$i<7;$i++){
            IF ($actday <= $lastDay['mday']){
                ECHO '<td class=hasday>'.$actday++.'</td>';
            }
            ELSE {
                ECHO '<td class="noday"> </td>';
            }
        }
        ECHO '</tr>';
    }
    ECHO '</table></div>';
}

/*	demo execution starts here
	This demo example uses showCalendar to package 12 months into 3 rows.
	It defaults to start in the current month.
*/
	$useDefault = TRUE;  // to always start in a particular month, set this false and see (A) below
	IF ($useDefault) { // start at current month
		$thisDay    = GETDATE();
		$startMonth = $thisDay['mon'];
		$startYear = $thisDay['year'];
	}
	ELSE {
		$startMonth = 1;   // (A) start at specified month ... January, 2011 in this case
		$startYear = 2011;
	}

	FOR ($block = 1; $block<=3; $block++) { // there are 3 blocks
		ECHO '<div class = "calblock"><table><tr>';
		FOR ($calcount=1; $calcount <=4; $calcount++) { // each block holds 4 months
			ECHO '<td class = "onecal">';
			showCalendar( $startMonth++,$startYear, 1 /* start all weeks on Monday (1) */);
			ECHO '</td>';
		}
		ECHO '</tr></table></div>';
	}
?>