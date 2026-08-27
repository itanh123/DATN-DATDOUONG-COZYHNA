<?php
$validator = Validator::make(['primary_table_id' => 3, 'table_ids' => [3, 4]], [
    'primary_table_id'  => 'required|exists:restaurant_tables,id',
    'table_ids'         => 'required|array|min:1',
    'table_ids.*'       => 'exists:restaurant_tables,id',
]);
if ($validator->fails()) {
    echo json_encode($validator->errors());
} else {
    echo 'Passed';
}
