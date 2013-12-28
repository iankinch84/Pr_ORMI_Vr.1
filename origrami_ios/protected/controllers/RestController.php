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
     * Get the coupon information
     * 
     * @param couponCode
     */
    public function actionCouponList(){
        if (!isset($_GET["couponCode"])) {
            Util::response(NULL, "No 'couponCode' parameter");
        }
        
        $couponCode = $_GET["couponCode"];
        
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
    
    public function actionLogin(){
        if ( !isset($_POST["username"]) &&
                !isset($_POST["email"]) ) {
            Util::sendErrorMessage("Parameter is not complete");
        }
        
        if ( !isset($_POST["password"]) ) {
            Util::sendErrorMessage("Parameter is not complete");
        }
        
        $query = "password=" . $_POST["password"];
        
        if ( isset($_POST["username"]) ) {
            $query .= "&username=" . $_POST["username"];
        }
        elseif ( isset($_POST["email"]) ) {
            $query .= "&email=" . $_POST["email"];
        }
        
        $result = Util::queryDB(Util::$MODEL_CUSTOMERS, $query);
        
        if ( Util::isVariableEmpty($result) ) {
            Util::sendErrorMessage("Invalid Login Data");
        }
        
        Util::response(true, "Login Success");
        
    }
}

?>
