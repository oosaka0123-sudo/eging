<?php
header('Content-Type: application/json; charset=utf-8');
$configFile=dirname(__DIR__,2).'/config/config.php';
if(!is_file($configFile)){
    http_response_code(503);
    echo json_encode(['error'=>'service_unavailable'], JSON_UNESCAPED_UNICODE);
    exit;
}
try{
    require dirname(__DIR__, 2) . '/app/bootstrap.php';
}catch(Throwable $e){
    error_log('EGING simulate bootstrap error: '.$e->getMessage());
    http_response_code(503);
    echo json_encode(['error'=>'service_unavailable'], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_request'], JSON_UNESCAPED_UNICODE);
    exit;
}
$allowed = [
    'season' => ['spring','autumn'],
    'field_type' => ['port','rock','surf'],
    'depth_band' => ['shallow','mid','deep'],
    'wind_band' => ['low','mid','strong'],
    'tide_phase' => ['rising','falling','slack'],
    'target_size' => ['small','medium','large'],
    'time_of_day' => ['dawn','day','dusk','night'],
];
foreach ($allowed as $key => $values) {
    if (!isset($input[$key]) || !in_array($input[$key], $values, true)) {
        http_response_code(422);
        echo json_encode(['error' => 'invalid_condition', 'field' => $key], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
$currentBand = null;
if (isset($input['current_band']) && $input['current_band'] !== '') {
    if (!in_array($input['current_band'], ['slow','normal','fast'], true)) {
        http_response_code(422);
        echo json_encode(['error' => 'invalid_condition', 'field' => 'current_band'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $currentBand = $input['current_band'];
}

$sql = "SELECT output, rationale
FROM simulator_rules
WHERE enabled = 1
AND (season IS NULL OR season = :season)
AND (field_type IS NULL OR field_type = :field_type)
AND (depth_band IS NULL OR depth_band = :depth_band)
AND (wind_band IS NULL OR wind_band = :wind_band)
AND (current_band IS NULL OR current_band = :current_band)
AND (tide_phase IS NULL OR tide_phase = :tide_phase)
AND (target_size IS NULL OR target_size = :target_size)
AND (time_of_day IS NULL OR time_of_day = :time_of_day)
ORDER BY priority ASC, id ASC
LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':season' => $input['season'],
    ':field_type' => $input['field_type'],
    ':depth_band' => $input['depth_band'],
    ':wind_band' => $input['wind_band'],
    ':current_band' => $currentBand,
    ':tide_phase' => $input['tide_phase'],
    ':target_size' => $input['target_size'],
    ':time_of_day' => $input['time_of_day'],
]);
$row = $stmt->fetch();
if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'no_rule'], JSON_UNESCAPED_UNICODE);
    exit;
}
echo json_encode([
    'output' => json_decode($row['output'], true),
    'rationale' => $row['rationale'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
