<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{   
    
    /**
     *
     * @var string store user ID
     */
    private $_ID = "";
    
    /**
     *
     * @var boolean true if authentication use username, and false for email 
     */
    public $USE_USERNAME = "";
    
    /**
     * Construction
     * 
     * @param string $_userKey whether username or email
     * @param string $_password password as usual
     * @param boolean $_isUsername userKey is username or email, true for username
     */
    public function __construct($_userKey, $_password, $_isUsername = true){
        
        $this->username = $_userKey;
        $this->password = $_password;
        $this->USE_USERNAME = $_isUsername;
        
    }
    
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {        
        $query = "password=" . $this->password;
        
        if ( $this->USE_USERNAME ) {
            $query .= "&username=";
        }
        else {
            $query .= "&email=";
        }
        
        $query .= $this->username;
        
        $result = Util::queryDB(Util::$MODEL_CUSTOMERS, $query);
        
        
        if ( Util::isVariableEmpty($result) ) {
//            $this->errorCode = self::ERROR_USERNAME_INVALID;
            return false;
        }
        
        $this->_ID = $result[0]["id"];
        
        return true;
    }
    
    /**
     * Get User ID
     * 
     * @return string
     */
    public function getId() {
        return $this->_ID;
    }
}