<?php
$user = 'rcozmolici';
$sitePrefix = '/~' . $user . '/';

$access_glob = __DIR__ . '/access.log';
$error_glob  = __DIR__ . '/error.log';

function read_lines($pattern) {
    $files = glob($pattern);
    if (!$files) return [];
    rsort($files);
    $lines = [];
    foreach ($files as $f) {
        foreach (file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $l) {
            $lines[] = $l;
        }
    }
    return $lines;
}

//Parse access logs
$re = '/^(\S+) \S+ \S+ \[([^\]]+)\] "(GET|POST|HEAD) ([^"]*) HTTP\/[0-9.]+" (\d{3}) \S+ "(?:[^"]*)" "([^"]*)"/';
$byPage = [];
$byHour = [];
$recent = [];

foreach (read_lines($access_glob) as $line) {
    if (!preg_match($re, $line, $m)) continue;
    [$all, $ip, $ts, $method, $path, $code, $ua] = $m;
    if (strpos($path, $sitePrefix) === false) continue;
    $page = strtok(substr($path, strlen($sitePrefix)), '?');
    $byPage[$page] = ($byPage[$page] ?? 0) + 1;

    $dt = DateTime::createFromFormat('d/M/Y:H:i:s O', $ts);
    if ($dt) {
        $bucket = $dt->format('Y-m-d H');
        $byHour[$bucket] = ($byHour[$bucket] ?? 0) + 1;
    }
    $recent[] = [$ip, $ts, $page, $code, substr($ua, 0, 50)];
    if (count($recent) > 20) array_shift($recent);
}
arsort($byPage);
ksort($byHour);

//Parse error log (last 15)
$error_lines = [];
if (is_readable($error_glob)) {
    $tmp = file($error_glob, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $error_lines = array_slice($tmp, -15);
}


?>
<!doctype html>
<meta charset="utf-8">
<title>Web Log Evaluation — Radu Cozmolici</title>
<h1>SuperLeague — Web Log Evaluation</h1>
<p>Analyzing logs for <code><?=htmlspecialchars($sitePrefix)?></code></p>

<h2>Hits by Page</h2>
<table border="1" cellpadding="4">
<tr><th>Page</th><th>Hits</th></tr>
<?php foreach ($byPage as $p => $n): ?>
<tr><td><?=htmlspecialchars($p)?></td><td><?=$n?></td></tr>
<?php endforeach; ?>
</table>

<h2>Recent Accesses (last 20)</h2>
<table border="1" cellpadding="3">
<tr><th>IP</th><th>Time</th><th>Page</th><th>Code</th><th>User-Agent</th></tr>
<?php foreach ($recent as [$ip,$ts,$p,$code,$ua]): ?>
<tr>
  <td><?=htmlspecialchars($ip)?></td>
  <td><?=htmlspecialchars($ts)?></td>
  <td><?=htmlspecialchars($p)?></td>
  <td><?=$code?></td>
  <td><?=htmlspecialchars($ua)?></td>
</tr>
<?php endforeach; ?>
</table>

<h2>Requests per Hour</h2>
<canvas id="chart" width="900" height="300"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?=json_encode(array_keys($byHour))?>;
const data   = <?=json_encode(array_values($byHour))?>;
new Chart(document.getElementById('chart'), {
  type: 'line',
  data: { labels, datasets: [{ label: 'Requests per hour', data }] },
  options: { responsive: false, scales: { y: { beginAtZero: true } } }
});
</script>

<h2>Recent Errors (last 15)</h2>
<pre style="border:1px solid #ccc; padding:6px; white-space:pre-wrap;">
<?=htmlspecialchars(implode("\n", $error_lines) ?: 'No recent errors recorded.')?>
</pre>

<p><a href="../index.html">Back to site</a></p>