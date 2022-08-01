<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://192.168.178.13/api/v1/data"); // wijzig ipadres in het ipadres van je HomeWizard P1 meter
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
if (curl_errno($ch)) { echo curl_error($ch); }
else {
 // echo $result;
  $decoded = json_decode($result);

echo "<b> METER INFO </b> <br/><b> SMR Versie: </b>";
echo $decoded->smr_version;
echo "<br/>";

echo "<b> Meter Model: </b>";
echo $decoded->meter_model;
echo "<br/>";

echo ("<b> WiFi SSID: </b> ");
echo $decoded->wifi_ssid;
echo "<br/>";

echo "<b> WiFi signaalsterkte: </b>";
echo $decoded->wifi_strength;
echo "<br/><br/>";

echo "<b>STROOM IMPORT</b><br/><b>Totaal Import T1:</b>";
echo $decoded->total_power_import_t1_kwh;
echo (" kWh <br/>");

echo "<b> Totaal Import T2: </b>";
echo $decoded->total_power_import_t2_kwh;
echo (" kWh <br/><br/>");

echo "<b>STROOM EXPORT</b><br/><b> Totaal Export T1: </b>";
echo $decoded->total_power_export_t1_kwh;
echo (" kWh <br/>");

echo "<b> Totaal Export T2: </b>";
echo $decoded->total_power_export_t2_kwh;
echo (" kWh <br/><br/>");

echo "<b>HUIDIG VERBRUIK</b><br/><b> Totaal stroomverbruik:</b>";
echo $decoded->active_power_w;
echo (" Watt <br/>");

echo "<b>Totaal L1: </b>$decoded->active_power_l1_w Watt <br/>";
echo "<b>Totaal L2: </b>$decoded->active_power_l2_w Watt <br/>";
echo ("<b> Totaal L3: </b>$decoded->active_power_l3_w Watt<br/><br/> ");

}
curl_close($ch);
?>
