<?php
/**
 * HomeWizard P1 Meter Dashboard - Modern Edition
 */

$ip_address = "192.168.178.13"; // Wijzig in jouw IP
$api_url = "http://{$ip_address}/api/v1/data";

// Data ophalen met een timeout van 3 seconden
$context = stream_context_create(['http' => ['timeout' => 3]]);
$result = @file_get_contents($api_url, false, $context);

if ($result === FALSE) {
    die("<div style='color:red; font-family:sans-serif; padding:20px;'>Fout: Kan geen verbinding maken met de HomeWizard meter op $ip_address.</div>");
}

$data = json_decode($result);

// Helpers voor styling
$is_levering = $data->active_power_w < 0;
$tariff_label = ($data->active_tariff > 1) ? "Hoog" : "Laag";
$tariff_class = ($data->active_tariff > 1) ? "text-orange" : "text-green";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P1 Meter Monitor</title>
    <style>
        :root {
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --text-main: #e0e0e0;
            --green: #4caf50;
            --red: #f44336;
            --orange: #ff9800;
            --blue: #2196f3;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg-color); color: var(--text-main); margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Grid Layout */
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .card { background: var(--card-bg); padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        
        /* Typography */
        h2 { margin-top: 0; font-size: 0.9rem; text-transform: uppercase; color: #888; }
        .value { font-size: 1.8rem; font-weight: bold; }
        .text-green { color: var(--green); }
        .text-red { color: var(--red); }
        .text-orange { color: var(--orange); }

        /* Tables */
        table { width: 100%; border-collapse: collapse; background: var(--card-bg); border-radius: 12px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #333; }
        th { background: #252525; font-size: 0.8rem; text-transform: uppercase; color: #888; }
        tr:last-child td { border-bottom: none; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .badge-low { background: rgba(76, 175, 80, 0.2); color: var(--green); }
        .badge-high { background: rgba(255, 152, 0, 0.2); color: var(--orange); }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>P1 Monitor <small style="font-size: 0.5em; color: #666;"><?php echo $data->meter_model; ?></small></h1>
        <div class="status">● Live</div>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Actueel Verbruik</h2>
            <div class="value <?php echo $is_levering ? 'text-green' : ''; ?>">
                <?php echo abs($data->active_power_w); ?> W
                <small style="font-size: 0.4em;"><?php echo $is_levering ? '(Levering)' : '(Opname)'; ?></small>
            </div>
        </div>
        <div class="card">
            <h2>Huidig Tarief</h2>
            <div class="value <?php echo $tariff_class; ?>"><?php echo $tariff_label; ?></div>
        </div>
        <div class="card">
            <h2>Spanningsuitvallen</h2>
            <div class="value"><?php echo $data->long_power_fail_count; ?> <small style="font-size: 0.4em;">(lang)</small></div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px; padding: 0;">
        <table>
            <thead>
                <tr>
                    <th>Meterstand</th>
                    <th>Import (kWh)</th>
                    <th>Export (kWh)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>T1 (Dal)</td>
                    <td class="text-red"><?php echo number_format($data->total_power_import_t1_kwh, 3); ?></td>
                    <td class="text-green"><?php echo number_format($data->total_power_export_t1_kwh, 3); ?></td>
                </tr>
                <tr>
                    <td>T2 (Piek)</td>
                    <td class="text-red"><?php echo number_format($data->total_power_import_t2_kwh, 3); ?></td>
                    <td class="text-green"><?php echo number_format($data->total_power_export_t2_kwh, 3); ?></td>
                </tr>
                <tr style="font-weight: bold; background: #252525;">
                    <td>Totaal</td>
                    <td class="text-red"><?php echo number_format($data->total_power_import_kwh, 3); ?></td>
                    <td class="text-green"><?php echo number_format($data->total_power_export_kwh, 3); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card" style="padding: 0;">
        <table>
            <thead>
                <tr>
                    <th>Fase</th>
                    <th>Vermogen</th>
                    <th>Spanning</th>
                    <th>Stroom</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(['l1', 'l2', 'l3'] as $f): ?>
                <tr>
                    <td>Fase <?php echo strtoupper($f); ?></td>
                    <td class="<?php echo ($data->{"active_power_{$f}_w"} < 0) ? 'text-green' : ''; ?>">
                        <?php echo $data->{"active_power_{$f}_w"}; ?> W
                    </td>
                    <td class="<?php echo ($data->{"active_voltage_{$f}_v"} > 245) ? 'text-red' : ''; ?>">
                        <?php echo $data->{"active_voltage_{$f}_v"}; ?> V
                    </td>
                    <td><?php echo $data->{"active_current_{$f}_a"}; ?> A</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
