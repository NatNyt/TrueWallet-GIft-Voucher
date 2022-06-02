<?php 
include './api.php';

$tw = new TwVoucher('https://gift.truemoney.com/campaign/?v=xxxxx', 'xxxx');
print($tw->RedeemVoucher());
