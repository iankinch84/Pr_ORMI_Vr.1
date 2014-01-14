<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestController
 *
 * @author Ian
 */
class RestController extends CController{
    //put your code here
    
    /**
     * Get the coupon information [v]
     * 
     * @param couponCode
     */
    public function actionCouponList(){
        
        if ( Util::isVariableEmpty(Yii::app()->user->id) ) {
            Util::sendErrorMessage("Login First");
        }
        
        if (!isset($_POST["couponCode"])) {
            Util::response(NULL, "No 'couponCode' parameter");
        }
        
        $couponCode = $_POST["couponCode"];
        
        $result = Util::queryDB(Util::$MODEL_COUPON, "code=" . $couponCode);
        
        $payload = array();
        if (isset($result)) {
            foreach ($result as $row) {
                if ($row["enddate"] > Util::getCurrentMillis()) {
                    $coupon = array();
                    $coupon["value"] = $row["value"];
                    $coupon["id"] = $row["id"];                    
                    array_push($payload, $coupon);
                }
            }
        }
        
        Util::response(true, $payload);
        
    }
    
    /**
     * Registration Procedure [v]
     * 
     * @param string $_POST["username"] username
     * @param string $_POST["password"] password plain
     * @param string $_POST["email"] email address
     * 
     * +opt string $_POST["name"] Name
     * +opt string $_POST["website"] Name
     * +opt string $_POST["instagram_id"] instagram id
     * +opt string $_POST["profile"] Profile Picture
     * +opt string $_POST["access_token"] access token from instagram
     */
    public function actionRegister(){
        if ( !isset($_POST["username"]) || 
                !isset($_POST["password"]) ||
                !isset($_POST["email"]) ) {
            Util::sendErrorMessage("Parameter is not complete");
        }
        
        $newCustomer = new Customers;
        $newCustomer->username = $_POST["username"];
        $newCustomer->password = $_POST["password"];
        $newCustomer->name = isset($_POST["name"]) ? $_POST["name"] : "";
        $newCustomer->website = isset($_POST["website"]) ? $_POST["website"] : "";
        $newCustomer->instagram_id = isset($_POST["instagram_id"]) ? $_POST["instagram_id"] : "";
        $newCustomer->profile = isset($_POST["profile"]) ? $_POST["profile"] : "";
        $newCustomer->access_token = isset($_POST["access_token"]) ? $_POST["access_token"] : "";
        $newCustomer->email = $_POST["email"];
        
        if ( $newCustomer->save() ) {
            Util::response(true, "Insert Success");
        }
        
        Util::sendErrorMessage("Insert Failed");
    }
    
    /**
     * Login Procedure [v]
     * 
     * @param string $_POST["username"] username
     * @param string $_POST["email"] email
     * @param string $_POST["password"] password
     */
    public function actionLogin(){
        if ( !isset($_POST["username"]) &&
                !isset($_POST["email"]) ) {
            Util::sendErrorMessage("Parameter is not complete");
        }
        
        if ( !isset($_POST["password"]) ) {
            Util::sendErrorMessage("Parameter is not complete");
        }
        
        if ( isset($_POST["username"]) ) {
            $user = new UserIdentity($_POST["username"], $_POST["password"]);
        }
        elseif ( isset($_POST["email"]) ) {
            $user = new UserIdentity($_POST["email"], $_POST["password"], false);
        }
        
        if ( $user->authenticate() ) {
            Yii::app()->user->login($user);
            
            Util::response(true, "Login Success");        
        }
        
        Util::sendErrorMessage("Login Failed");
    }
    
    /**
     * Logout procedure [v]
     * 
     */
    public function actionLogout(){
        $key = Yii::app()->user->id;
        
        if ( !Util::isVariableEmpty($key) ) {
            Yii::app()->user->logout();
            Util::response(true, "Logout Success");        
        }
        
        Util::response(true, "No User is Login");        
    }
    
    /**
     * Upload Images Procedure [x]
     * 
     */
    public function actionUploadImages(){
        
        $query = "id=" . Yii::app()->user->id;        
        $result = Util::queryDB(Util::$MODEL_CUSTOMERS, $query);
        
        if ( Util::isVariableEmpty($result) ) {
            Util::sendErrorMessage("User Not Found");
        }
        
        $user = array();
        foreach ( $result as $row ) {
            $user["name"] = Util::isVariableEmpty($row["name"]) ? "anonymous" : $row["name"];
            $user["username"] = Util::isVariableEmpty($row["username"]) ? "anonymous" : $row["username"];            
        }
        
        $imageParameterName = "images";
        $imagePath = Yii::getPathOfAlias("webroot");
        $imagePath .= "/images/client/";
        $imagePath .= $user["name"] . "[" . $user["username"] . "]/";
        $imagePath .= date('Y, d-M');
        
        $uploadingImages = Util::uploadImage($imageParameterName, $imagePath);
        
        if (!Util::isVariableEmpty($uploadingImages) && $uploadingImages["status"]) {
            Util::response(true, CJSON::encode($uploadingImages["images"]));
        }
        else{
            Util::sendErrorMessage("Upload Failed");
        }
    }
    
    /**
     * Check Out Procedure [x]
     * 
     * param = {
     *      email: "",
     *      
     * 
     * 
     * }
     */
    public function actionCheckOut(){
        
        //  Check whether parameter [email] exist or not
        if ( Util::isVariableEmpty($_POST["email"]) ) {
            Util::sendErrorMessage("Parameter Email is not found");
        }
        
        $userEmail = $_POST["email"];
        
        //  Check whether parameter [name] exist or not
        if ( Util::isVariableEmpty($_POST["name"]) ) {
            Util::sendErrorMessage("Parameter User Full Name is not found");
        }
        
        $userFullName = $_POST["name"];
        
        //  Check whether user exist or not
        $user = Util::getUserWithEmail($userEmail);
        
        if ( Util::isVariableEmpty($user) ) {
            //  Register new User
            $user = Util::userRegistration($userFullName, $userEmail);
            
            if ( Util::isVariableEmpty($user) ) {
                Util::sendErrorMessage("Registration Failed");
            }
        }
        
        
        
        
        if ( Util::isVariableEmpty($_POST["dummy"]) ) {
            Util::response(true, "kosong");
        }
        
        $result = Util::createNewShoppingcart($_POST["dummy"]);
        
        Util::response(true, $result);
    }   
    
}

?>
