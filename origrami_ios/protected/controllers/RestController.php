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
}

?>
