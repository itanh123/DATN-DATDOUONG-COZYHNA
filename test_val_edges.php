<?php
$data = ['primary_table_id' => 4, 'table_ids' => [3, 4]];
$validator = Validator::make($data, [
    'primary_table_id'  => 'required|exists:restaurant_tables,id',
    'table_ids'         => 'required|array|min:1',
    'table_ids.*'       => 'exists:restaurant_tables,id',
]);
if ($validator->fails()) {
    echo json_encode($validator->errors());
} else {
    echo 'Passed with [3, 4]' . PHP_EOL;
}

$data2 = ['primary_table_id' => 4, 'table_ids' => ['3', 4]];
$validator2 = Validator::make($data2, [
    'primary_table_id'  => 'required|exists:restaurant_tables,id',
    'table_ids'         => 'required|array|min:1',
    'table_ids.*'       => 'exists:restaurant_tables,id',
]);
if ($validator2->fails()) {
    echo json_encode($validator2->errors());
} else {
    echo 'Passed with ["3", 4]' . PHP_EOL;
}

$data3 = ['primary_table_id' => 4, 'table_ids' => [null, 4]];
$validator3 = Validator::make($data3, [
    'primary_table_id'  => 'required|exists:restaurant_tables,id',
    'table_ids'         => 'required|array|min:1',
    'table_ids.*'       => 'exists:restaurant_tables,id',
]);
if ($validator3->fails()) {
    echo json_encode($validator3->errors());
} else {
    echo 'Passed with [null, 4]' . PHP_EOL;
}
