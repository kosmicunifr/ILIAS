<?php
/**
 * Class ilMStListCourse
 *
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class ilMStListCourse {

    /**
     *
     * @var int
     */
    protected $crs_ref_id;
    /**
     * @var string
     */
    protected $crs_title;
    /**
     *
     * @var int
     */
    protected $usr_id;
    /**
     *
     * @var int
     */
    protected $usr_reg_status;
    /**
     *
     * @var int
     */
    protected $usr_lp_status;
    /**
     * @var string
     */
    protected $usr_login;
    /**
     * @var string
     */
    protected $usr_firstname;
    /**
     * @var string
     */
    protected $usr_lastname;
    /**
     * @var string
     */
    protected $usr_email;
    /**
     * @var string
     */
    protected $usr_assinged_orgus;

    /**
     * @return int
     */
    public function getCrsRefId()
    {
        return $this->crs_ref_id;
    }

    /**
     * @param int $crs_ref_id
     */
    public function setCrsRefId($crs_ref_id)
    {
        $this->crs_ref_id = $crs_ref_id;
    }

    /**
     * @return string
     */
    public function getCrsTitle()
    {
        return $this->crs_title;
    }

    /**
     * @param string $crs_title
     */
    public function setCrsTitle($crs_title)
    {
        $this->crs_title = $crs_title;
    }

    /**
     * @return int
     */
    public function getUsrId()
    {
        return $this->usr_id;
    }

    /**
     * @param int $usr_id
     */
    public function setUsrId($usr_id)
    {
        $this->usr_id = $usr_id;
    }

    /**
     * @return int
     */
    public function getUsrRegStatus()
    {
        return $this->usr_reg_status;
    }

    /**
     * @param int $usr_reg_status
     */
    public function setUsrRegStatus($usr_reg_status)
    {
        $this->usr_reg_status = $usr_reg_status;
    }

    /**
     * @return int
     */
    public function getUsrLpStatus()
    {
        return $this->usr_lp_status;
    }

    /**
     * @param int $usr_lp_status
     */
    public function setUsrLpStatus($usr_lp_status)
    {
        $this->usr_lp_status = $usr_lp_status;
    }

    /**
     * @return string
     */
    public function getUsrLogin()
    {
        return $this->usr_login;
    }

    /**
     * @param string $usr_login
     */
    public function setUsrLogin($usr_login)
    {
        $this->usr_login = $usr_login;
    }

    /**
     * @return string
     */
    public function getUsrFirstname()
    {
        return $this->usr_firstname;
    }

    /**
     * @param string $usr_firstname
     */
    public function setUsrFirstname($usr_firstname)
    {
        $this->usr_firstname = $usr_firstname;
    }

    /**
     * @return string
     */
    public function getUsrLastname()
    {
        return $this->usr_lastname;
    }

    /**
     * @param string $usr_lastname
     */
    public function setUsrLastname($usr_lastname)
    {
        $this->usr_lastname = $usr_lastname;
    }

    /**
     * @return string
     */
    public function getUsrEmail()
    {
        return $this->usr_email;
    }

    /**
     * @param string $usr_email
     */
    public function setUsrEmail($usr_email)
    {
        $this->usr_email = $usr_email;
    }

    /**
     * @return string
     */
    public function getUsrAssingedOrgus()
    {
        return $this->usr_assinged_orgus;
    }

    /**
     * @param string $usr_assinged_orgus
     */
    public function setUsrAssingedOrgus($usr_assinged_orgus)
    {
        $this->usr_assinged_orgus = $usr_assinged_orgus;
    }

    //Other
    /**
     * @return ilObjUser
     */
    public function returnIlUserObj() {
        $il_user_obj = new ilObjUser($this->usr_id);
        return $il_user_obj;
    }

    /**
     * @return ilObjCourse
     */
    public function returnIlCourseObj() {
        $il_course_obj = new ilObjCourse($this->crs_ref_id);
        return $il_course_obj;
    }
}