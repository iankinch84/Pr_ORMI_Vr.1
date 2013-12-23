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
    
    public static $MODEL_COUPON = "coupon", 
            $MODEL_CUSTOMERS = "customers",
            $MODEL_ORDERS = "orders",
            $MODEL_PRODUCTS = "products",
            $MODEL_PRODUCT_THEME = "producttheme",
            $MODEL_PRODUCT_THEME_ITEMS = "productthemeitems",
            $MODEL_SHOPPINGCART = "shoppingcart",
            $MODEL_ADMIN = "admin";
    
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
        
        try {
            $images = CUploadedFile::getInstanceByName($uploadName);
        
            if ( isset($images) && count($images) > 0 ) {

                Util::createDirectory($path);

                if ( count($images) > 1 ) {
                    foreach ( $images as $image => $pic ) {                                        
                        if ( !$pic->saveAs($path . "/" . $pic->name) ) {
                            return false;                            
                        }
                    }
                }
                else {
                    if ( !$images->saveAs($path . "/" . $images->name) ) {
                        return false;
                    }
                }
                
                return true;
            }            
        }
        catch ( Exception $err ) {
            return false;
        }
        
        return false;
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
            self::sendErrorMessage("No Data Found");
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
}

?>
