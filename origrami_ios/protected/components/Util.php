<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author Ian
 */
class Util{
    //put your code here
    
    /**
     * Deklarasi Variabel Publik
     * 
     * @var string 
     */
    public static $MODEL_COUPON = "coupon", 
                $MODEL_CUSTOMERS = "customers",
                $MODEL_ORDERS = "orders",
                $MODEL_PRODUCTS = "products",
                $MODEL_PRODUCT_THEME = "producttheme",
                $MODEL_PRODUCT_THEME_ITEMS = "productthemeitems",
                $MODEL_SHOPPINGCART = "shoppingcart",
                $MODEL_ADMIN = "admin",
                $DEFAULT_PASSWORD = "11234567899";
    
    /**
     * Get Model By String Name
     * 
     * @param string $_modelString
     * @return model
     */
    public static function getModelByString( $_modelString = NULL) {
        if ( is_null($_modelString) ) {
            return NULL;
        }
        
        switch ( strtolower($_modelString) ) {
            case "coupon":
                $model = Couponcode::model();
                break;
            case "customers":
                $model = Customers::model();
                break;
            case "orders":
                $model = Orders::model();
                break;
            case "products":
                $model = Products::model();
                break;
            case "producttheme":
                $model = Producttheme::model();
                break;
            case "productthemeitems":
                $model = ProductthemeItems::model();
                break;
            case "shoppingcart":
                $model = Shoppingcart::model();
                break;
            case "admin":
                $model = Admin::model();
                break;
            
            default:
                $model = NULL;
        }
        
        return $model;
    }
    
    /**
     * Send Error Message
     * 
     * @param string $_errorMessage Error Message
     */
    public static function sendErrorMessage($_errorMessage = "Unknown Error"){
        self::response(FALSE, "Error: " . $_errorMessage);
    }
    
    /**
    * Send raw HTTP response
     * 
    * @param int $status HTTP status code
    * @param string $body The body of the HTTP response
    * @param string $contentType Header content-type
    * @return HTTP response 
    */
    public static function sendResponse($status = 200, $body = '', 
                                        $contentType = 'application/json')
    {
        // Set the status
        $statusHeader = 'HTTP/1.1 ' . $status . ' ' . Util::getStatusCodeMessage($status);
        header($statusHeader);
        // Set the content type
        header('Content-type: ' . $contentType);

        echo $body;
        Yii::app()->end();
    }
    
    /**
    * Return the http status message based on integer status code
     * 
    * @param int $status HTTP status code
    * @return string status message
    */
    public static function getStatusCodeMessage($_status)
    {
        $codes = array(
                    100 => 'Continue',
                    101 => 'Switching Protocols',
                    200 => 'OK',
                    201 => 'Created',
                    202 => 'Accepted',
                    203 => 'Non-Authoritative Information',
                    204 => 'No Content',
                    205 => 'Reset Content',
                    206 => 'Partial Content',
                    300 => 'Multiple Choices',
                    301 => 'Moved Permanently',
                    302 => 'Found',
                    303 => 'See Other',
                    304 => 'Not Modified',
                    305 => 'Use Proxy',
                    306 => '(Unused)',
                    307 => 'Temporary Redirect',
                    400 => 'Bad Request',
                    401 => 'Unauthorized',
                    402 => 'Payment Required',
                    403 => 'Forbidden',
                    404 => 'Not Found',
                    405 => 'Method Not Allowed',
                    406 => 'Not Acceptable',
                    407 => 'Proxy Authentication Required',
                    408 => 'Request Timeout',
                    409 => 'Conflict',
                    410 => 'Gone',
                    411 => 'Length Required',
                    412 => 'Precondition Failed',
                    413 => 'Request Entity Too Large',
                    414 => 'Request-URI Too Long',
                    415 => 'Unsupported Media Type',
                    416 => 'Requested Range Not Satisfiable',
                    417 => 'Expectation Failed',
                    500 => 'Internal Server Error',
                    501 => 'Not Implemented',
                    502 => 'Bad Gateway',
                    503 => 'Service Unavailable',
                    504 => 'Gateway Timeout',
                    505 => 'HTTP Version Not Supported',

        );
        return (isset($codes[$_status])) ? $codes[$_status] : '';
    }
    
    /**
     * Check Whether the Variable is Empty, Null, or Not Set
     * 
     * @param object $_model 
     * @return boolean Is Variable Empty or Not
     */
    public static function isVariableEmpty($_var){
        if ( !isset($_var) || is_null($_var) || empty($_var) ) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Saving images
     * 
     * @param string $uploadName Filename
     * @param string $path /[Your Path]
     */
    public static function uploadImage( $uploadName, $path ) {
        
        $imageResult = array();
        $imageResult["status"] = false;
        $imageResult["images"] = array();
            
        $images = CUploadedFile::getInstanceByName($uploadName);
            
        if ( isset($images) && count($images) > 0 ) {

            Util::createDirectory($path);

            if ( count($images) > 1 ) {
                foreach ( $images as $image => $pic ) {                                        
                    if ( $pic->saveAs($path . "/" . $pic->name) ) {
                    $permission = substr(sprintf('%o', fileperms($path . "/" . $pic->name)), -4);
                    
                    if ( $permission == "0000" ||
                            $permission == "0111" ||
                            $permission == "0222" ||
                            $permission == "0333" ) {
                        chmod($path . "/" . $pic->name, 0666);
                    }
                    
                        array_push($imageResult["images"], $path . "/" . $pic->name);
                    }
                }
            }
            else {
                if ( $images->saveAs($path . "/" . $images->name) ) {
                    echo substr(sprintf('%o', fileperms($path . "/" . $images->name)), -4);
                    
                    $permission = substr(sprintf('%o', fileperms($path . "/" . $images->name)), -4);
                    
                    if ( $permission == "0000" ||
                            $permission == "0111" ||
                            $permission == "0222" ||
                            $permission == "0333" ) {
                        chmod($path . "/" . $images->name, 0666);
                    }
                    array_push($imageResult["images"], $path . "/" . $images->name);
                }
            }

            if (count($imageResult["images"]) == count($images)) {
                $imageResult["status"] = true;
            }
        }
                
        return $imageResult;
    }
    
    /**
     * Create a Directory
     * 
     * @param type $path Directory Path
     * @return boolean Success or Not
     */
    public static function createDirectory( $path ) {
        if ( isset($path) ) {
            if ( !is_dir($path) ) {
                return mkdir($path, 0777, true);
            }
        }
        
        return false;
    }
    
    /**
     * Delete a Directory
     * 
     * @param type $path Directory Path
     * @return boolean Success or Not
     */
    public static function removeDirectory( $path ) {
        if ( isset($path) ) {
            if ( is_dir($path) ) {
                return rmdir($path);
            }
        }
        
        return false;
    }
    
    /**
     * Send Valid Response to a Request
     * 
     * @param boolean $status Error or Not
     * @param string $data Response Message
     */
    public static function response($status = true, $data = "") {
        $payload = array();
        $payload["status"] = $status;
        $payload["data"] = $data;
        
        self::sendResponse(NULL, CJSON::encode($payload));
    }
    
    /**
     * 
     * Querying query in DataBase
     * 
     * @param string $_model
     * @param string $_queryString Format is like QueryString
     * @return array
     */
    public static function queryDB($_model, $_queryString){
        
        if (self::isVariableEmpty($_model)) {
            return NULL;
        }
        
        $whereCondition = "";
        $whereParam = array();
        
        if (!self::isVariableEmpty($_queryString)) {
            $arrQueryString = split("&", $_queryString);
            foreach ( $arrQueryString as $dataQS ) {
                $splitter = split("=", $dataQS);

                if ( strlen($whereCondition) > 0 ) {
                    $whereCondition .= " AND ";
                }

                $whereCondition .= $splitter[0] . "=:" . $splitter[0];
                $whereParam[":" . $splitter[0]] = $splitter[1];
            }
        }                
        
        $models = self::getModelByString($_model);
        $model = $models->findAll($whereCondition, $whereParam);
        
        if ( self::isVariableEmpty($model) ) {
            return $model;
        }
        
        $result = array();
        foreach ( $model as $row ) {
            $result[] = $row->attributes;
        }
        
        return $result;
    }
    
    /**
     * 
     * @return int millisecond
     */
    public static function getCurrentMillis(){
        $utimestamp = microtime() . "";
        $splitter = split(" ", $utimestamp);
        
        return $splitter[1];
    }
    
    /**
     * Create new Shoppingcart item
     * 
     * @param string $_customerId Customer ID
     * @param string $_couponId Coupon ID
     * @return string New Shoppingcart ID
     */
    public static function createNewShoppingcart($_customerId, $_couponId = null){
        if ( Util::isVariableEmpty($_customerId) ) {
            return "-1";
        }
        
        $_couponId = Util::isVariableEmpty($_couponId) ? null : (int)$_couponId;
        
        $shoppingcart = new Shoppingcart();
        $shoppingcart->instagram_id = 0;
        $shoppingcart->coupon = $_couponId;
        $shoppingcart->fbshare = null;
        $shoppingcart->twshare = null;
        $shoppingcart->status = 1;
        $shoppingcart->customer_id = (int) $_customerId;
        
        if ( $shoppingcart->save() ) {
            return Util::isVariableEmpty($shoppingcart->id) ? "-1" : strval($shoppingcart->id);
        }
        
        return "-1";        
    }
    
    /**
     * Create New Orders
     * 
     * @param string $_shoppingcartId
     * @param string $_transactionId
     * @param string $_customerId
     * @param string $_email
     * @param string $_name
     * @param string $_address
     * @param string $_city
     * @param string $_state
     * @param string $_zip
     * @param string $_country
     * @return string
     */
    public static function createNewOrders($_shoppingcartId, $_transactionId, $_customerId, $_email,
                                            $_name, $_address, $_city, $_state, $_zip, $_country) {
        
        if ( Util::isVariableEmpty($_shoppingcartId) ||
                Util::isVariableEmpty($_transactionId) ||
                Util::isVariableEmpty($_customerId) ||
                Util::isVariableEmpty($_customerId) ||
                Util::isVariableEmpty($_email) ||
                Util::isVariableEmpty($_name) ||
                Util::isVariableEmpty($_address) ||
                Util::isVariableEmpty($_city) ||
                Util::isVariableEmpty($_state) ||
                Util::isVariableEmpty($_zip) ||
                Util::isVariableEmpty($_country) ) {
            return "-1";
        }
        
        
        $order = new Orders();
        $order->shoppingcart_id = $_shoppingcartId;
        $order->transaction_id = $_transactionId;
        $order->purchasedate = self::getCurrentMillis();
        $order->buyer_id = $_customerId;
        $order->payer_email = $_email;
        $order->fullname = $_name;
        $order->address = $_address;
        $order->city = $_city;
        $order->state = $_state;
        $order->zip = $_zip;
        $order->country = $_country;
        $order->status = 2; //  Paid and ready to Print
            
        if ( $order->save() ) {
            return strval($order->id);
        }
        
        return "-1";
    }
    
    /**
     * get User berdasarkan instagram ID
     * 
     * @param string $_instagramId
     * @return array()
     */
    public static function getUserWithInstagramId($_instagramId) {
        
        if ( self::isVariableEmpty($_instagramId) ) {
            return null;
        }
        
        $query = "instagram_id=" . $_instagramId;
        
        $data = self::queryDB(self::$MODEL_CUSTOMERS, $query);
        
        if ( self::isVariableEmpty($data) ) {
            return null;
        }
        
        foreach ($data as $user) {
            return $user;
        }        
    }
    
    /**
     * get User with Email Address
     * 
     * @param string $_email
     * @return array()
     */
    public static function getUserWithEmail($_email) {
        if ( self::isVariableEmpty($_email) ) {
            return null;
        }
        
        $query = "email=" . $_email;
        
        $data = self::queryDB(self::$MODEL_CUSTOMERS, $query);
        
        if ( self::isVariableEmpty($data) ) {
            return null;
        }
        
        foreach ($data as $user) {
            return $user;
        }
    }
    
    /**
     * User Registration
     * 
     * @param string $_name
     * @param string $_email
     * @param string $_password
     * @param string $_username
     * @param string $_instagramId
     * @param string $_accessToken
     * @param string $_profPict
     * @param string $_website
     * 
     * @return array()
     */
    public static function userRegistration($_name, $_email, $_password = null, $_username = null, 
                                            $_instagramId = null, $_accessToken = null, 
                                            $_profPict = null, $_website = null ) {
        
        //  Jika username kosong, maka username diambil dari email
        if ( self::isVariableEmpty($_username) ) {
            $temp = split("@", $_email);
            $_username = $temp[0];
        }
        
        //  Jika password kosong, maka akan diset ke default password
        if ( self::isVariableEmpty($_password) ) {
            $_password = self::$DEFAULT_PASSWORD;
        }
        
        $newCustomer = new Customers;
        $newCustomer->username = $_username;
        $newCustomer->password = $_password;
        $newCustomer->name = $_name;
        $newCustomer->website = $_website;
        $newCustomer->instagram_id = $_instagramId;
        $newCustomer->profile = $_profPict;
        $newCustomer->access_token = $_accessToken;
        $newCustomer->email = $_email;
        
        if ( $newCustomer->save() ) {
            return $newCustomer;
        }
        
        return null;
    }
}

?>
