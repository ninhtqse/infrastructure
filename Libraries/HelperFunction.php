<?php

namespace Infrastructure\Libraries;

use Api\Users\Repositories\AccountRepository;
use DB;
use Cache;
use Config;
use Infrastructure\Exceptions as EfyException;
use Api\Configs\Repositories\ConfigRepository;
use Uuid;

class HelperFunction {

    private $configRepository;

    private $invoiceNumberRepository;

    private $accountRepository;

    public function __construct(ConfigRepository $configRepository, AccountRepository $accountRepository) {
        $this->configRepository = $configRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * thêm chuỗi số 0 trước số đã cho
     *
     * @param $num
     * @param $nRezo
     *
     * @return string
     */
    public function addRezoNumber($num, $nRezo){
        $nRAdd  = $nRezo - strlen($num);
        $sRezo = '';
        for ($i = 0; $i < $nRAdd; $i++)
            $sRezo .= '0';
        $num = $sRezo . $num;
        return $num;
    }

    /**
     * thay đổi kết nối database mặc định
     *
     * @param $taxCode
     *
     * @return string
     */
    public function changeDefaultConnection($taxCode) {
        $databaseName = $this->getDatabaseNameFromCache($taxCode);
        Config::set('database.connections.sqlsrv.database', $databaseName);
        Config::set('database.connections.sqlsrv.odbc_datasource_name', 'Driver={ODBC Driver 11 for SQL Server};Server='.Config::get('database.connections.sqlsrv.host').';Database='.$databaseName);
        DB::purge('sqlsrv');
    }

    /**
     * lấy ra tên db từ cache, trường hợp chưa có thì lưu vào cache và lấy ra
     *
     * @param $taxCode
     *
     * @return string
     */
    public function getDatabaseNameFromCache($taxCode) {
        $databaseName = Cache::rememberForever('database_' . $taxCode, function() use($taxCode) {

            $name = DB::table('databases as a')
                ->join('accounts as b', 'a.id', '=', 'b.database_id')
                ->where('b.tax_number', $taxCode)
                ->value('a.name');
            if(!$name) {
                throw new EfyException\GeneralException('IWE011', null, null, [$taxCode]);
            }
            return $name;
        });
        return $databaseName;
    }

    /**
     *
     * @param $taxCode
     *
     * @return string
     */
    public function getSystemParameter($name = 'system_parameter', $accountId = false) {
        if($accountId) {
            $params = ['name' => $name, 'account_id' => $accountId];
        } else {
            $params = ['name' => $name, 'account_id' => request()->user()->account_id];
        }
        $config = $this->configRepository->getWhereArray($params)->first();
        $sysParameter = json_decode($config->value);
        return $sysParameter;
    }

    /**
     *
     * @param $nameDatabase
     *
     * @return string
     */
    public function changeConnection($databaseName = null){
        $databaseName = 'ebhxh-v3.master';
        Config::set('database.connections.sqlsrv.database', $databaseName);
        Config::set('database.connections.sqlsrv.odbc_datasource_name', 'Driver={ODBC Driver 11 for SQL Server};Server='.Config::get('database.connections.sqlsrv.host').';Database='.$databaseName);
        DB::purge('sqlsrv');
    }

    public function getConfig() {
        $return = [];
        $params = ['account_id' => request()->user()->account_id];
        $config = $this->configRepository->getWhereArray($params);
        foreach($config as $value) {
            $return[$value->name] = $value->value;
        }
        return $return;
    }

    public function vndText($amount)
    {
         if($amount < 0)
        {
            throw new EfyException\GeneralException('IWE101');
        }
        $Text=array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
        $TextLuythua =array("","nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
        $textnumber = "";
        $textnumberFraction = "";

        $fraction = null;
         
        if (strpos($amount, '.') !== false) {
            // cho phép làm tròn đến 4 số thập phân
            $amount = round($amount, 4);
            if (strpos($amount, '.') !== false) {
                list($amount, $fraction) = explode('.', $amount);
                if($fraction > 0) {
                    $textnumberFraction = $this->vndText($fraction, 1);
                }
            }
        }


        $length = strlen($amount);
       
        for ($i = 0; $i < $length; $i++)
        $unread[$i] = 0;
       
        for ($i = 0; $i < $length; $i++)
        {              
            $so = substr($amount, $length - $i -1 , 1);               
           
            if ( ($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)){
                for ($j = $i+1 ; $j < $length ; $j ++)
                {
                    $so1 = substr($amount,$length - $j -1, 1);
                    if ($so1 != 0)
                        break;
                }                      
                      
                if (intval(($j - $i )/3) > 0){
                    for ($k = $i ; $k <intval(($j-$i)/3)*3 + $i; $k++)
                        $unread[$k] =1;
                }
            }
        }
       
        for ($i = 0; $i < $length; $i++)
        {       
            $so = substr($amount,$length - $i -1, 1);      
            if ($unread[$i] ==1)
            continue;
           
            if ( ($i% 3 == 0) && ($i > 0))
            $textnumber = $TextLuythua[$i/3] ." ". $textnumber;    
           
            if ($i % 3 == 2 )
            $textnumber = 'trăm ' . $textnumber;
           
            if ($i % 3 == 1)
            $textnumber = 'mươi ' . $textnumber;
           
           
            $textnumber = $Text[$so] ." ". $textnumber;
        }
       
        //Phai de cac ham replace theo dung thu tu nhu the nay
        $textnumber = str_replace("không mươi", "linh", $textnumber);
        $textnumber = str_replace("linh không", "", $textnumber);
        $textnumber = str_replace("mươi không", "mươi", $textnumber);
        $textnumber = str_replace("một mươi", "mười", $textnumber);
        $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
        $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
        $textnumber = str_replace("mười năm", "mười lăm", $textnumber);

        if (trim($textnumberFraction)) {
            $textnumberFraction = 'phẩy ' . $textnumberFraction;
            $textnumberFraction = str_replace("phẩy Linh", "phẩy không", $textnumberFraction);
            $textnumberFraction = str_replace("đồng", "", $textnumberFraction);
            $textnumberFraction = str_replace("chẵn", "", $textnumberFraction);
            $textnumberFraction = trim($textnumberFraction);
            $return = ucfirst(strtolower($textnumber.$textnumberFraction)) . ' đồng';
        } else if(trim($textnumber)){
            $return = ucfirst(strtolower($textnumber)) . ' đồng chẵn';
        } else {
            $return = '';
        }
        $return = str_replace("  ", " ", $return);
        $return = str_replace("   ", " ", $return);
        return $return;

    }

    public function wordToPdf($contentDOC) {
        $serviceSignServer = \config('config.serviceSignServer');
        $ext = 'pdf';
        $options = array(
            'soap_version' => 1
        );
        $client = new \SoapClient($serviceSignServer, $options);
        $domtree = new \DOMDocument('1.0', 'UTF-8');
        $Envelope = $domtree->createElementNS('http://factory.ws.vangateway.com/', 'soapenv:Envelope');
        $Envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:efy', 'http://tempuri.org/');
        $Envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:soapenv', 'http://schemas.xmlsoap.org/soap/envelope/');

        $header = $domtree->createElement('soapenv:Header');
        $body = $domtree->createElement('soapenv:Body');

        $Envelope->appendChild($header);
        $Envelope->appendChild($body);
        $domtree->appendChild($Envelope);

        $domtree->loadXML($domtree->saveXML());
        $xp = new \DOMXPath($domtree);
        $body = $xp->query('/soapenv:Envelope/soapenv:Body')->item(0);
        $converDocToPdf = $domtree->createElement('efy:ConvertDocToPdf');
        $contentFile = $domtree->createElement('efy:contentFile', $contentDOC);

        $converDocToPdf->appendChild($contentFile);
        $body->appendChild($converDocToPdf);
        $responseText = $client->__doRequest($domtree->saveXML(), $serviceSignServer, 'http://tempuri.org/ConvertDocToPdf', '1.1');
        $responseText = str_replace('soap:', '', $responseText);
        $response = new \SimpleXMLElement($responseText);
        $result = (string) $response->Body->ConvertDocToPdfResponse->ConvertDocToPdfResult;
        return $result;
    }

    public function genPassword($length = 6)
    {
        $arrChar = ['Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M'];
        $arrCharSpecial = ['!','@','#','$','%','^','&','*'];
        $pass = $arrChar[rand(0,count($arrChar)-1)] . $arrCharSpecial[rand(0,count($arrCharSpecial)-1)] .rand(0,9);

        for ($i = 0; $i < $length - 3; $i++) {
            $upLow = rand(1,2);
            if ($upLow == 1) {
                $pass = $pass . $arrChar[rand(0, count($arrChar)-1)];
            } else {
                $pass = $pass . strtolower($arrChar[rand(0,count($arrChar)-1)]);
            }
        }
        return $pass;
    }

    public function genRandomString($length = 10, $prefix = '') {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $prefix . $randomString;
    }

    public function verifyCaptcha($captcha)
    {
        $secretKey = \Config('config.captcha.secret_key');
        $ip = $_SERVER['REMOTE_ADDR'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => $secretKey, 'response' => $captcha);

        $options = array(
            'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'POST',
              'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        if(!$responseKeys["success"]) {
            throw new EfyException\GeneralException('IWE105');
        }
    }
    // Lưu file vào ổ cứng
    public function saveFileHardDrive($arrFile, $idRecord)
    {
        set_error_handler(
            function ($severity, $message, $file, $line) {
                throw new EfyException\GeneralException($message, $severity, $severity, $file, $line);
            }
        );
        $time = date('Y-m-d H:i:s');
        $config = \Config('config.save_file_hard_drive');
        $dataDir = $config['dir_file'];
        try {
            file_get_contents($config['dir_file_check']);
        } catch(\Exception $e) {
            try {
                file_get_contents($config['dir_file_backup_check']);
            } catch(\Exception $e) {
                throw new EfyException\GeneralException("IE051");
            }
            $dataDir = $config['dir_file_backup'];
            $this->getEntityManager()->getRepository(EntityRecord::class)->updateConfig($config);
        }
        $pathFile = $this->createImageFolder($dataDir, $idRecord, $time);
        //put file
        try {
            foreach($arrFile as $value) {
                if($value['MaToKhai'] == 'CT-DK'){
                    $mtk = 'FILE_DINH_KEM_';
                }else{
                    $mtk = 'FILE_TO_KHAI_';
                }
                file_put_contents($pathFile . '/' . $mtk.$value['fileName'], base64_decode($value['fileData']));
            }
        } catch(\Exception $e) {
            //Không lưu được file
            throw new EfyException\GeneralException("IE051");
        }
        restore_error_handler();
        $url    = str_replace('/','\\',$pathFile);
        $url_new = str_replace('\\var\nas\files\\',$config['dir_share'],$url);
        return $url_new;
    }
    public function createImageFolder($sPath, $id, $time) {
        $folderYear = date('Y', strtotime($time));
        $folderMonth = date('m', strtotime($time));
        $sCurrentDay = date('d', strtotime($time));
        if (!file_exists($sPath . $folderYear)) {
            mkdir($sPath . $folderYear, 0777,true);
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(057) . $folderMonth)) {
                mkdir($sPath . chr(057) . $folderMonth, 0777,true);
            }
        } else {
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(057) . $folderMonth)) {
                mkdir($sPath . chr(057) . $folderMonth, 0777,true);
            }
        }
        //Tao ngay trong nam->thang
        if (!file_exists($sPath . chr(057) . $folderMonth . chr(057) . $sCurrentDay)) {
            mkdir($sPath . chr(057) . $folderMonth . chr(057) . $sCurrentDay, 0777,true);
        }
        //Tao ngay trong nam->thang
        if (!file_exists($sPath . chr(057) . $folderMonth . chr(057) . $sCurrentDay . chr(057) . $id)) {
            mkdir($sPath . chr(057) . $folderMonth . chr(057) . $sCurrentDay . chr(057) . $id, 0777,true);
        }
        return $sPath . '/' . $folderMonth . '/' . $sCurrentDay . '/' . $id;
    }
}