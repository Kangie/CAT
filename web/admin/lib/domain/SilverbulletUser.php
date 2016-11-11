<?php
namespace lib\domain;

/**
 * 
 * @author Zilvinas Vaira
 *
 */
class SilverbulletUser extends PersistentEntity{
    
    const TABLE = 'silverbullet_user';
    
    /**
     * Required profile identifier
     * 
     * @var string
     */
    const PROFILEID = 'profile_id';
    
    /**
     * Required user name attribute
     * 
     * @var string
     */
    const USERNAME = 'username';
    
    /**
     *
     * @var string
     */
    const EXPIRY = 'expiry';
    
    private $defaultUserExpiry;
    
    /**
     * List of certificates for user entity
     * 
     * @var SilverbulletCertificate []
     */
    private $certificates = array();
    
    /**
     * Constructor that should be used when creating a new record. Refer to Silverbullet:: create and Silverbullet::list to load existing records.
     * 
     * @param int $profileId
     * @param string $username
     */
    public function __construct($profileId, $username){
        parent::__construct(self::TABLE, self::TYPE_INST);
        $this->set(self::PROFILEID, $profileId);
        $this->set(self::USERNAME, $username);
        //$this->set(self::EXPIRY, 'NOW() + INTERVAL 1 WEEK');
        $this->defaultUserExpiry = date('Y-m-d H:i:s',strtotime("+1 week"));
        //$this->set(self::EXPIRY, $this->defaultUserExpiry);
    }
    
    public function setExpiry($year, $month, $day){
        $tokenExpiry = date('Y-m-d H:i:s', strtotime($year."-".$month."-".$day));
        if($tokenExpiry > $this->defaultUserExpiry){
            $this->set(self::EXPIRY, $tokenExpiry);
        }else{
            $this->row = array();
        }
    }
    
    public function getProfileId(){
        return $this->get(self::PROFILEID);
    }
    
    public function getUsername(){
        return $this->get(self::USERNAME);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isExpired(){
        $expiryTime = strtotime($this->get(self::EXPIRY));
        $currentTime = time();
        return $currentTime > $expiryTime;
    }
    
    /**
     *
     * @return string
     */
    public function getExpiry(){
        return $this->get(self::EXPIRY);
    }
    
    /**
     * 
     * @return \lib\domain\SilverbulletCertificate
     */
    public function getCertificates(){
        return $this->certificates;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isActive(){
        return count($this->certificates) > 0;
    }
    
    protected function validate(){
        //TODO Implement type handling for SilverbulletUser
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \lib\domain\Persistent::load()
     */
    public function load(){
        $state = parent::load();
        $this->certificates = SilverbulletCertificate::getList($this);
        return $state;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \lib\domain\Persistent::delete()
     */
    public function delete(){
        $state = parent::delete();
        foreach ($this->certificates as $certificate) {
            $certificate->delete();
        }
        return $state;
    }
    
    /**
     * 
     * @param ins $userId
     * @return \lib\domain\SilverbulletUser
     */
    public static function prepare($userId){
        $instance = new SilverbulletUser(null, '');
        $instance->set(self::ID, $userId);
        return $instance;
    }
    
    /**
     * 
     * @return \lib\domain\SilverbulletUser []
     */
    public static function getList($profileId) {
        $databaseHandle = \DBConnection::handle(self::TYPE_INST);
        $result = $databaseHandle->exec("SELECT * FROM `" . self::TABLE . "` WHERE `".self::PROFILEID."`=?", 's', $profileId);
        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $user = new SilverbulletUser(null, '');
            $user->row = $row;
            $user->certificates = SilverbulletCertificate::getList($user);
            $list[] = $user;
        }
        return $list;
    }
    
}
