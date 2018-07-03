<?php
/*
Plugin Name: WP Hotelier DOKU Payment Gateway
Plugin URI: http://www.kedaisistem.com
Description: DOKU Payment Gateway untuk Easy WP Hotelier
Version: 1.0
Author: Debaluk
Author URI: http://www.kedaisistem.com
License: GPL2
*/

/*Referensi
 * https://blog.waroengweb.co.id/php/cara-membuat-menambahkan-menu-di-halaman-admin-wordpress.html
 */

function dokusetting_options_page () {
		
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
?>
	<div class="wrap">
	<h2>Setting Doku</h2>
	<p>Halaman untuk setting Doku Paymentgateway</p>
	<form method="post" enctype="multipart/form-data">
		<table>
			<tr>
				<td>Payment Gateway Aktif</td>
				<td>
					<select name="aktif">
						<option value="<?php echo $rowdataconfig[doku_enable];?>"><?php echo $rowdataconfig[doku_enable];?></option>
						<option value="Ya">Ya</option>
						<option value="Tidak">Tidak</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Payment Gateway Mode</td>
				<td>
					<select name="modestatus">
						<option value="<?php echo $rowdataconfig[doku_status];?>"><?php echo $rowdataconfig[doku_status];?></option>
						<option value="Development">Development</option>
						<option value="Production">Production</option>
					</select>
				</td>
				
			</tr>
			<tr>
				<td>Mall ID Production</td>
				<td>
					<input name="maillid_procduction" class="regular-text" value="<?php echo $rowdataconfig['live_mall_id'];?>" />
				</td>
			</tr>
			<tr>
				<td>Shared Key Procduction</td>
				<td>
					<input name="sharekey_procduction" class="regular-text" value="<?php echo $rowdataconfig['live_share_key'];?>" />
				</td>
			</tr>
			<tr>
				<td>Chain Number Procduction</td>
				<td>
					<input name="chainnumber_procduction" class="regular-text" value="<?php echo $rowdataconfig['live_chain_number'];?>" />
				</td>
			</tr>
			<tr>
				<td>Mall ID Development</td>
				<td>
					<input name="maillid_dev" class="regular-text" value="<?php echo $rowdataconfig['demo_mall_id'];?>" />
				</td>
			</tr>
			<tr>
				<td>Shared Key Development</td>
				<td>
					<input name="sharekey_dev" class="regular-text" value="<?php echo $rowdataconfig['demo_share_key'];?>" />
				</td>
			</tr>
			<tr>
				<td>Chain Number Development</td>
				<td>
					<input name="chainnumber_dev" class="regular-text" value="<?php echo $rowdataconfig['demo_chain_number'];?>" />
				</td>
			</tr>
			
		</table>
		<input type="submit" name="simpan" value="Update Data">
	</form>
	</div>
	<?php
	//echo $rowdataconfig[demo_mall_id];
}

function settingdoku_menu () {
add_menu_page('Doku Setting','Doku Setting','manage_options','settingdoku_admin', dokusetting_options_page);
}

add_action('admin_menu','settingdoku_menu');
?>
