<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://192.168.178.13/api/v1/data"); // wijzig ipadres in het ipadres van je HomeWizard P1 meter
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
if (curl_errno($ch)) { echo curl_error($ch); }
else {
 // echo $result;
  $decoded = json_decode($result);
echo "<b> METER INFO </b> <br/><b> SMR Versie: </b>$decoded->smr_version<br/>";
echo "<b> Meter Model: </b>$decoded->meter_model<br/>";
echo "<b> WiFi SSID: </b> $decoded->wifi_ssid<br/>";
echo "<b> WiFi signaalsterkte: </b>$decoded->wifi_strength<br/><br/>";

echo "<b>STROOM IMPORT</b><br/><b>Totaal Import T1: </b>$decoded->total_power_import_t1_kwh kWh <br/>";
echo "<b> Totaal Import T2: </b>$decoded->total_power_import_t2_kwh kWh <br/><br/>";

echo "<b>STROOM EXPORT</b><br/><b> Totaal Export T1: </b>$decoded->total_power_export_t1_kwh kWh <br/>";
echo "<b> Totaal Export T2: </b>$decoded->total_power_export_t2_kwh kWh <br/><br/>";

echo "<b>HUIDIG VERBRUIK</b><br/><b> Totaal stroomverbruik:</b> $decoded->active_power_w Watt <br/>";
echo "<b>Totaal L1: </b>$decoded->active_power_l1_w Watt <br/>";
echo "<b>Totaal L2: </b>$decoded->active_power_l2_w Watt <br/>";
echo "<b> Totaal L3: </b>$decoded->active_power_l3_w Watt<br/><br/>";

}
curl_close($ch);
?>
