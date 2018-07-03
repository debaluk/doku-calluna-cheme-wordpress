<?php
/**
 * Cancel reservation button
 *
 * This template can be overridden by copying it to yourtheme/hotelier/reservation/cancel-reservation.php.
 *
 * @author  Benito Lopez <hello@lopezb.com>
 * @package Hotelier/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php
/* edit by debaluk
 * 30-6-2018
 * Get informasi transaksi dan api setting doku
 * 
 */
 	//cari idboking uri[3]
 	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);
	$idbooking = $uri_segments[3];
	
 	//untuk koneksi native
	$host="localhost";
	$db_name="theg8427_dbwpH0m3hot3L1n1";
	$_name="theg8427_userwpH0m3hot3L1n1";
	$_pass="thecrystalnusaduabali20180623";
		
	$con = mysqli_connect($_host,$_name,$_pass);
	
	if($con){
		$buka =mysqli_select_db($con,$db_name);
		 if(!$buka){
			die("Oops! Database Down..."); 
		 }
	}else{
		die("Oops! Server Down..., Mohon maaf kami sedang upgrade hardware.");	
	}
	$dataconfig=mysqli_query($con,"select * from wp_dokusetting");
	$rowdataconfig=mysqli_fetch_array($dataconfig);
 	
	//cari id berdasarkan reservation_id
	$databooking=mysqli_query($con,"select * from wp_hotelier_bookings where reservation_id=$idbooking");
	$rowdatabooking=mysqli_fetch_array($databooking);
	$databookingid=$rowdatabooking[id];
	//echo $databookingid;
	//echo $idbooking;
	//cari detil harga booking
	//tabel : wp_hotelier_reservation_itemmeta where reservation_item_id
	$databookingharga=mysqli_query($con,"
		select 
			reservation_item_id, 
			max(CASE WHEN meta_key='_qty' THEN meta_value END) 'qty', 
			max(CASE WHEN meta_key='_price' THEN meta_value END) 'harga', 
			max(CASE WHEN meta_key='_total' THEN meta_value END) 'total'
		FROM 
			wp_hotelier_reservation_itemmeta 
		WHERE reservation_item_id='$databookingid'
		GROUP BY 
			reservation_item_id");
	$rowdatabookingharga=mysqli_fetch_array($databookingharga);
	
	//cari personal, nama tabel wp_postmeta key where post_id
	$databookingperonal=mysqli_query($con,"
		select 
			post_id, 
			MAX(CASE WHEN meta_key='_guest_first_name' THEN meta_value END) 'nama_depan', 
			MAX(CASE WHEN meta_key='_guest_last_name' THEN meta_value END) 'nama_belakang', 
			MAX(CASE WHEN meta_key='_guest_email' THEN meta_value END) 'nama_email',
			MAX(CASE WHEN meta_key='_guest_telephone' THEN meta_value END) 'nama_telp',
			MAX(CASE WHEN meta_key='_guest_address1' THEN meta_value END) 'nama_alamat',
			MAX(CASE WHEN meta_key='_guest_country' THEN meta_value END) 'nama_country'
		FROM 
			wp_postmeta 
		WHERE post_id='$idbooking'
		GROUP BY 
			post_id");
	$rowdatabookingpersonal=mysqli_fetch_array($databookingperonal);
	
	//cari id room by id booking
	$dataroom=mysqli_query($con,"select * from wp_hotelier_reservation_items where reservation_id=$idbooking");
	$rowdataroom=mysqli_fetch_array($dataroom);
	$dataroomname=$rowdataroom[reservation_item_name];
	
	//cari nama kamar
	
	
	//jika status development
	if ($rowdataconfig[doku_status]=='Development')
	{
		?>
		<script language="JavaScript" type="text/javascript" src="https://staging.doku.com/dateformat.js"></script>
		<script language="JavaScript" type="text/javascript" src="https://staging.doku.com/sha-1.js"></script>
		<?php
		$linkpost='http://staging.doku.com/Suite/Receive';
		$mall_id     = $rowdataconfig['demo_mall_id'];
		$shared_key  = $rowdataconfig['demo_share_key'];
		$chain       = $rowdataconfig['demo_chain_number'];
	}
	else {
		?>
		<script language="JavaScript" type="text/javascript" src="https://staging.doku.com/dateformat.js"></script>
		<script language="JavaScript" type="text/javascript" src="https://staging.doku.com/sha-1.js"></script>

		<?php
		$linkpost='https://pay.doku.com/Suite/Receive';
		$mall_id     = $rowdataconfig['live_mall_id'];
		$shared_key  = $rowdataconfig['live_share_key'];
		$chain       = $rowdataconfig['live_chain_number'];
	}
?>

<div class="col-sm-2 row">
	<script language="javascript" type="text/javascript">
	function getRequestDateTime() {
		var now = new Date();
	
		document.MerchatPaymentPage.REQUESTDATETIME.value = dateFormat(now, "yyyymmddHHMMss");	
	}
	
	function randomString(STRlen) {
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var string_length = STRlen;
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
	
		return randomstring;
	
	}
	
	function genInvoice() {	
		document.MerchatPaymentPage.TRANSIDMERCHANT.value = randomString(12);
	}
	
	function genSessionID() {	
		document.MerchatPaymentPage.SESSIONID.value = randomString(20);
	}
	
	function genBookingCode() {	
		document.MerchatPaymentPage.BOOKINGCODE.value = randomString(6);
	}
	
	function getWords() {
	
		var msg = document.MerchatPaymentPage.AMOUNT.value + document.MerchatPaymentPage.MALLID.value + document.MerchatPaymentPage.SHAREDKEY.value + document.MerchatPaymentPage.TRANSIDMERCHANT.value;
		
		document.MerchatPaymentPage.WORDS.value = SHA1(msg);	
	}
	
	</script>
	<form action="<?php echo $linkpost;?>" id="MerchatPaymentPage" name="MerchatPaymentPage" method="post" >
		<input name="submit" type="submit" class="bt_submit calluna-button btn btn-primary small button--cancel-reservation-button" id="submit" value="Pay"/>
		<input name="BASKET" type="hidden" id="BASKET" value="<?php echo $dataroomname;?>,<?php echo substr($rowdatabookingharga[harga],0,-2);?>.00,<?php echo $rowdatabookingharga[qty];?>,<?php echo substr($rowdatabookingharga[harga],0,-2);?>.00" size="100" />
    	<input name="MALLID" type="hidden" id="MALLID" value="<?php echo $mall_id;?>" size="12" /> 
    	<input name="CHAINMERCHANT" type="hidden" id="CHAINMERCHANT" value="NA" size="12" />
    	<input name="CURRENCY" type="hidden" id="CURRENCY" value="360" size="3" maxlength="3" />
    	<input name="PURCHASECURRENCY" type="hidden" id="PURCHASECURRENCY" value="360" size="3" maxlength="3" />
    	<input name="AMOUNT" type="hidden" id="AMOUNT" value="<?php echo substr($rowdatabookingharga[harga],0,-2);?>.00" size="12" />
    	<input name="PURCHASEAMOUNT" type="hidden" id="PURCHASEAMOUNT" value="<?php echo substr($rowdatabookingharga[total],0,-2);?>.00" size="12" />
    	<input name="TRANSIDMERCHANT" type="hidden" id="TRANSIDMERCHANT" size="16" />
    	<input name="SHAREDKEY" type="hidden" id="SHAREDKEY" value="<?php echo $shared_key;?>" size="15" maxlength="12"/>
    	<input type="hidden" id="WORDS" name="WORDS"  size="60"/>
  		<input name="REQUESTDATETIME" type="hidden" id="REQUESTDATETIME" size="14" maxlength="14" />
      	<input type="hidden" id="SESSIONID" name="SESSIONID" /> 
      	<input type="hidden" id="PAYMENTCHANNEL" name="PAYMENTCHANNEL" value="" />
      	<input name="EMAIL" type="hidden" id="EMAIL" value="<?php echo $rowdatabookingpersonal[nama_email];?>" size="12" />
      	<input name="NAME" type="hidden" id="NAME" value="<?php echo $rowdatabookingpersonal[nama_depan];?> <?php echo $rowdatabookingpersonal[nama_belakang];?>" size="30" maxlength="50" />
      	<input name="ADDRESS" type="hidden" id="ADDRESS" value="<?php echo $rowdatabookingpersonal[nama_alamat];?>" size="50" maxlength="50" />
      	<input name="COUNTRY" type="hidden" id="COUNTRY" value="360" size="50" maxlength="50" />
      	<input name="STATE" type="hidden" id="STATE" value="<?php echo $rowdatabookingpersonal[nama_country];?>" size="50" maxlength="50" />
      	<input name="CITY" type="hidden" id="CITY" value="" size="50" maxlength="50" />
      	<input name="PROVINCE" type="hidden" id="PROVINCE" value="" size="50" maxlength="50" />
	</form>
	<script language="javascript" type="text/javascript">
		genInvoice();
		getRequestDateTime();
		genSessionID();
		getWords();
	</script>			
</div>
<div class="col-sm-6">
	<p class="cancel-reservation">
				<a href="<?php echo esc_url( $reservation->get_booking_cancel_url() ); ?>" class="calluna-button btn btn-primary small button--cancel-reservation-button"><?php _e( 'Cancel reservation', 'wp-hotelier' ); ?></a>
			</p>
</div>

<!--<p class="cancel-reservation">
	<a href="<?php echo esc_url( $reservation->get_booking_cancel_url() ); ?>" class="calluna-button btn btn-primary small button--cancel-reservation-button"><?php _e( 'Cancel reservation', 'wp-hotelier' ); ?></a>
</p>-->
