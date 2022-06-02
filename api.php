<?php

require './vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\GuzzleClient;
class TwVoucher{
    /**
     * GlobalConfig สามรถแก้ไขได้ถามไม่รู้จริงไม่ควรแก้ไขใดๆทั้งนั้น
     * api_url คือเป็นลิ้ง api url สำหรับหรับรับเงิน
     * voucher_replace จะแทนด้วยหรัสซองอั่ั่งเป๋า
     */
    public $globalConfig = [
        "api_url" => 'https://gift.truemoney.com/campaign/vouchers/{voucher_replace}/redeem'
    ];
    public $vouchers = 'https://gift.truemoney.com/campaign/?v=xxxxxxxxxxx';
    public $phone = '06xxxxxxxxx';
    public function __construct(string $voucher, string $phone){
        $this->vouchers = $this->praseVoucher($voucher);
        $this->phone = $this->prasePhone($phone);
    }
    public function RedeemVoucher(){
        $curl = curl_init();
        $headers = array(
            "Content-Type: application/json",
         );
         $phone = $this->phone;
         $data = json_encode(array(
             'mobile' => $phone,
             'voucher_hash' => $this->vouchers
        ));
        $url = str_replace( '{voucher_replace}', $this->vouchers, $this->globalConfig['api_url']);
        $client = new Client();
        $respone = $client->request('POST', $url, array('headers' => $headers, 'body' => $data, 'curl' => array(
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
        )));
        return $respone->getBody();
    }
    public function updateVoucher(string $voucher){
        $this->vouchers = $this->praseVoucher($voucher);
    }
    public function updatePhone(string $phone){
        $this->phone = $this->prasePhone($phone);
    }
    public function prasePhone(string $phone){
        if(strlen($phone) == 10){
            return $phone;
        }else{
            throw new Exception('Invaild Phone');
        }
    }
    public function praseVoucher(string $voucher){
        if($voucher == ''){
            throw new \Exception('Invalid Voucher');
        }
        $voucher = explode('?v=', $voucher);
        if(isset($voucher[1])){
            if(!$this->contains($voucher[1], 'gift.truemoney.com')){
                if(strlen($voucher[1]) == 18){
                    return $voucher[1];
                }else{
                    throw new Exception('Invaild Voucher');
                }
            }else{
                throw new Exception('Invaild Voucher');
            }
        }else{
            if($this->contains($voucher[0], 'gift.truemoney.com')){
                if(strlen($voucher[0]) == 18){
                    return $voucher[0];
                }else{
                    throw new Exception('Invaild Voucher');
                }
            }else{
                throw new Exception('Invaild Voucher');
            }
        }
    }
    public function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }
}
