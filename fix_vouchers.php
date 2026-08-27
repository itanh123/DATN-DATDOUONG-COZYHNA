<?php
$v = \App\Models\Voucher::find(3);
if ($v) {
    $v->target = 'shipping';
    $v->save();
    echo 'Fixed';
}
