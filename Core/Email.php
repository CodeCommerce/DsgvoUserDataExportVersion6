<?php
/**
 * Copyright (c) 2018
 * CodeCommerce - Christopher Bauer
 * www.codecommerce.de
 */

namespace CodeCommerce\UserData\Core;

use OxidEsales\Eshop\Core\Registry as Registry;

class Email extends Email_parent
{
    /**
     * DSGVO Userdata  mail template
     *
     * @var string
     */
    protected $_sDsgvoUserdata = "email/html/dsgvo_userdata.tpl";

    /**
     * send mail with userexport data
     * @param $sFilePath
     * @param $oUser
     * @return bool|int
     */
    public function sendUserDataMail($sFilePath, $oUser)
    {
        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams($oShop);

        // create messages
        $oSmarty = $this->_getSmarty();
        $this->setUser($oUser);

        // Process view data array through oxoutput processor
        $this->_processViewArray();
        Registry::getConfig()->setAdminMode(FALSE);

        $this->setBody($oSmarty->fetch($this->_sDsgvoUserdata));

        Registry::getConfig()->setAdminMode(TRUE);
        //sets subject of email
        $sSubject = Registry::getLang()->translateString('CC_DSGVO_USERDATA_MAILSUBJECT');
        $this->setSubject($sSubject);

        $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

        $this->setRecipient($oUser->getFieldData('oxusername'), $sFullName);
        $this->setReplyTo($oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue());

        $sFullPath = $sFilePath;

        if (@is_readable($sFullPath) && @is_file($sFullPath)) {
            $this->addAttachment($sFullPath, 'user_data.json');
        }

        if ($this->send()) {
            return TRUE;
        }

        return FALSE;
    }
}