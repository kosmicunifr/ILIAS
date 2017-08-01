<?php
/**
 * Class ilMStListUser
 *
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class ilMStListUser {

    /**
     *
     * @var int
     */
    protected $usr_id;
    /**
     *
     * @var int
     */
    protected $time_limit_owner;
    /**
     *
     * @var int
     */
    protected $active;
    /**
     * @var string
     */
    protected $login;
    /**
     * @var string
     */
    protected $firstname;
    /**
     * @var string
     */
    protected $lastname;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $phone;
    /**
     * @var string
     */
    protected $mobile_phone;
    /**
     * @var string
     */
    protected $assinged_orgus;
    /**
     * @var ilObjUser
     */
    protected $il_user_obj;


    /**
     * @return int
     */
    public function getUsrId() {
        return $this->usr_id;
    }


    /**
     * @param int $usr_id
     */
    public function setUsrId($usr_id) {
        $this->usr_id = $usr_id;
    }


    /**
     * @return int
     */
    public function getTimeLimitOwner() {
        return $this->time_limit_owner;
    }


    /**
     * @param int $time_limit_owner
     */
    public function setTimeLimitOwner($time_limit_owner) {
        $this->time_limit_owner = $time_limit_owner;
    }


    /**
     * @return int
     */
    public function getActive() {
        return $this->active;
    }


    /**
     * @param int $active
     */
    public function setActive($active) {
        $this->active = $active;
    }


    /**
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }


    /**
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }


    /**
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }


    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }


    /**
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }


    /**
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }


    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }


    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }


    /**
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }


    /**
     * @param string $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }


    /**
     * @return string
     */
    public function getMobilePhone() {
        return $this->mobile_phone;
    }


    /**
     * @param string $mobile_phone
     */
    public function setMobilePhone($mobile_phone) {
        $this->mobile_phone = $mobile_phone;
    }


    /**
     * @return string
     */
    public function getAssingedOrgus() {
        return $this->assinged_orgus;
    }


    /**
     * @param string $assinged_orgus
     */
    public function setAssingedOrgus($assinged_orgus) {
        $this->assinged_orgus = $assinged_orgus;
    }

    /**
     * @return ilObjUser
     */
    public function returnIlUserObj() {
        $il_obj_user = new ilObjUser($this->usr_id);
        return $il_obj_user;
    }
}