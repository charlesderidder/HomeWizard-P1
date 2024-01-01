<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://192.168.178.13/api/v1/data"); // wijzig ipadres in het ipadres van je HomeWizard P1 meter
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if (curl_errno($ch)) { echo curl_error($ch); }
else {
 // echo $result; / STRING  verwijder "//" om alle data weer te geven
  $decoded = json_decode($result);
  $active_power_w = $decoded->active_power_w;
  $active_power_l1_w = $decoded->active_power_l1_w;
  $active_power_l2_w = $decoded->active_power_l2_w;
  $active_power_l3_w = $decoded->active_power_l3_w;
  $active_voltage_l1_v = $decoded->active_voltage_l1_v;
  $active_voltage_l2_v = $decoded->active_voltage_l2_v;
  $active_voltage_l3_v = $decoded->active_voltage_l3_v;
  $meter_model = $decoded->meter_model;
  $active_tariff = $decoded->active_tariff;
  
  if ($active_power_w < 0) {
    echo "<b>Verbruik: </b><span style=\"color:green;\">$active_power_w Watt</span><br/>";
  } else {
    echo "<b>Verbruik: </b>$active_power_w Watt<br/>";
  }

  if ($active_tariff > 1) {
    echo "<td>Huidig tarief: <span style=\"color:red;\">Hoog</span></td><br>";
  } else {
    echo "<td>Huidig tarief: <span style=\"color:green;\">Laag</span></td><br>";
  }

echo "<b>Meter Model: </b>$decoded->meter_model<br/></b>";

// Display import/export/total data in table
echo "<table border='1'><tr><th></th><th> Import </th><th> Export</th></tr>";
echo "<tr> <td>T1</td>";
echo "<td> <b style='color:red;'>$decoded->total_power_import_t1_kwh</b></td>";
echo "<td> <b style='color:green;'>$decoded->total_power_export_t1_kwh</b></td></tr>";
echo "<tr><td>T2</td>";
echo "<td> <b style='color:red;'>$decoded->total_power_import_t2_kwh</b></td>";
echo "<td> <b style='color:green;'>$decoded->total_power_export_t2_kwh</b></td></tr>";
echo "<tr><td>Totaal</td>";
echo "<td> <b style='color:red;'>$decoded->total_power_import_kwh</b></td>";
echo "<td> <b style='color:green;'>$decoded->total_power_export_kwh</b></td></tr></table>";

// Display Fase/Watt/Voltage/Ampere in a table
echo "<table border='1'><tr><th>Fase </th><th> Watt </th><th> Volt</th><th> Ampere</th></tr>";
echo "<tr> <td>L1</td>";

  if ($active_power_l1_w < 0) {
    echo "<td><span style=\"color:green;\">$active_power_l1_w</span></td>";
  } else {
    echo "<td>$active_power_l1_w</td>";
  }  
  
  if ($active_voltage_l1_v > 240) {
    echo "<td><span style=\"color:red;\">$active_voltage_l1_v</span></td>";
  } else {
    echo "<td>$active_voltage_l1_v</td>";
  }

echo "<td> $decoded->active_current_l1_a</td>";
echo "</tr>";
echo "<tr><td>L2</td>";
  if ($active_power_l2_w < 0) {
    echo "<td><span style=\"color:green;\">$active_power_l2_w</span></td>";
  } else {
    echo "<td>$active_power_l2_w</td>";
  }

  if ($active_voltage_l2_v > 240) {
    echo "<td><span style=\"color:red;\">$active_voltage_l2_v</span></td>";
  } else {
    echo "<td>$active_voltage_l2_v</td>";
  }
  
echo "<td> $decoded->active_current_l2_a</td>";
echo "</tr>";	
echo "<tr><td>L3</td>";
  if ($active_power_l3_w < 0) {
    echo "<td><span style=\"color:green;\">$active_power_l3_w</span></td>";
  } else {
    echo "<td>$active_power_l3_w</td>";
  }
  if ($active_voltage_l3_v > 240) {
    echo "<td><span style=\"color:red;\">$active_voltage_l3_v</span></td>";
  } else {
    echo "<td>$active_voltage_l3_v</td>";
  }
echo "<td> $decoded->active_current_l3_a</td>";
echo "</tr>";   
echo "</table>";

echo "<b>Stroomuitval: </b>$decoded->any_power_fail_count	<br/>";
echo "<b>Stroomuitval lang: </b>$decoded->long_power_fail_count	<br/>";
}
?>
