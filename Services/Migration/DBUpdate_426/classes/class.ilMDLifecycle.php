<?php
/*
    +-----------------------------------------------------------------------------+
    | ILIAS open source                                                           |
    +-----------------------------------------------------------------------------+
    | Copyright (c) 1998-2001 ILIAS open source, University of Cologne            |
    |                                                                             |
    | This program is free software; you can redistribute it and/or               |
    | modify it under the terms of the GNU General Public License                 |
    | as published by the Free Software Foundation; either version 2              |
    | of the License, or (at your option) any later version.                      |
    |                                                                             |
    | This program is distributed in the hope that it will be useful,             |
    | but WITHOUT ANY WARRANTY; without even the implied warranty of              |
    | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
    | GNU General Public License for more details.                                |
    |                                                                             |
    | You should have received a copy of the GNU General Public License           |
    | along with this program; if not, write to the Free Software                 |
    | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
    +-----------------------------------------------------------------------------+
*/


/**
* Meta Data class (element annotation)
*
* @author Stefan Meyer <meyer@leifos.com>
* @package ilias-core
* @version $Id$
*/
include_once 'class.ilMDBase.php';

class ilMDLifecycle extends ilMDBase
{
    // Get subelemsts 'Contribute'
    public function &getContributeIds()
    {
        include_once 'Services/Migration/DBUpdate_426/classes/class.ilMDContribute.php';

        return ilMDContribute::_getIds($this->getRBACId(), $this->getObjId(), $this->getMetaId(), 'meta_lifecycle');
    }
    public function &getContribute($a_contribute_id)
    {
        include_once 'Services/Migration/DBUpdate_426/classes/class.ilMDContribute.php';
        
        if (!$a_contribute_id) {
            return false;
        }
        $con = new ilMDContribute();
        $con->setMetaId($a_contribute_id);

        return $con;
    }
    public function &addContribute()
    {
        include_once 'Services/Migration/DBUpdate_426/classes/class.ilMDContribute.php';

        $con = new ilMDContribute($this->getRBACId(), $this->getObjId(), $this->getObjType());
        $con->setParentId($this->getMetaId());
        $con->setParentType('meta_lifecycle');

        return $con;
    }


    // SET/GET
    public function setStatus($a_status)
    {
        switch ($a_status) {
            case 'Draft':
            case 'Final':
            case 'Revised':
            case 'Unavailable':
                $this->status = $a_status;

                // no break
            default:
                return false;
        }
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setVersion($a_version)
    {
        $this->version = $a_version;
    }
    public function getVersion()
    {
        return $this->version;
    }
    public function setVersionLanguage($lng_obj)
    {
        if (is_object($lng_obj)) {
            $this->version_language = &$lng_obj;
        }
    }
    public function &getVersionLanguage()
    {
        return $this->version_language;
    }
    public function getVersionLanguageCode()
    {
        if (is_object($this->version_language)) {
            return $this->version_language->getLanguageCode();
        }
        return false;
    }

    public function save()
    {
        if ($this->db->autoExecute(
            'il_meta_lifecycle',
            $this->__getFields(),
            ilDBConstants::AUTOQUERY_INSERT
        )) {
            $this->setMetaId($this->db->getLastInsertId());

            return $this->getMetaId();
        }
        return false;
    }

    public function update()
    {
        global $ilDB;
        
        if ($this->getMetaId()) {
            if ($this->db->autoExecute(
                'il_meta_lifecycle',
                $this->__getFields(),
                ilDBConstants::AUTOQUERY_UPDATE,
                "meta_lifecycle_id = " . $ilDB->quote($this->getMetaId())
            )) {
                return true;
            }
        }
        return false;
    }

    public function delete()
    {
        global $ilDB;
        
        // Delete 'contribute'
        foreach ($this->getContributeIds() as $id) {
            $con = $this->getContribute($id);
            $con->delete();
        }


        if ($this->getMetaId()) {
            $query = "DELETE FROM il_meta_lifecycle " .
                "WHERE meta_lifecycle_id = " . $ilDB->quote($this->getMetaId());
            
            $this->db->query($query);
            
            return true;
        }
        return false;
    }
            

    public function __getFields()
    {
        return array('rbac_id' => $this->getRBACId(),
                     'obj_id' => $this->getObjId(),
                     'obj_type' => ilUtil::prepareDBString($this->getObjType()),
                     'lifecycle_status' => ilUtil::prepareDBString($this->getStatus()),
                     'meta_version' => ilUtil::prepareDBString($this->getVersion()),
                     'version_language' => ilUtil::prepareDBString($this->getVersionLanguageCode()));
    }

    public function read()
    {
        global $ilDB;
                
        include_once 'Services/Migration/DBUpdate_426/classes/class.ilMDLanguageItem.php';

        if ($this->getMetaId()) {
            $query = "SELECT * FROM il_meta_lifecycle " .
                "WHERE meta_lifecycle_id = " . $ilDB->quote($this->getMetaId());

            $res = $this->db->query($query);
            while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
                $this->setRBACId($row->rbac_id);
                $this->setObjId($row->obj_id);
                $this->setObjType($row->obj_type);
                $this->setStatus(ilUtil::stripSlashes($row->lifecycle_status));
                $this->setVersion(ilUtil::stripSlashes($row->meta_version));
                $this->setVersionLanguage(new ilMDLanguageItem($row->version_language));
            }
        }
        return true;
    }

    /*
     * XML Export of all meta data
     * @param object (xml writer) see class.ilMD2XML.php
     *
     */
    public function toXML(&$writer)
    {
        $writer->xmlStartTag('Lifecycle', array('Status' => $this->getStatus()));
        $writer->xmlElement('Version', array('Language' => $this->getVersionLanguageCode()), $this->getVersion());

        // contribute
        foreach ($this->getContributeIds() as $id) {
            $con = &$this->getContribute($id);
            $con->toXML($writer);
        }

        $writer->xmlEndTag('Lifecycle');
    }

                

    // STATIC
    public function _getId($a_rbac_id, $a_obj_id)
    {
        global $ilDB;

        $query = "SELECT meta_lifecycle_id FROM il_meta_lifecycle " .
            "WHERE rbac_id = " . $ilDB->quote($a_rbac_id) . " " .
            "AND obj_id = " . $ilDB->quote($a_obj_id);


        $res = $ilDB->query($query);
        while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
            return $row->meta_lifecycle_id;
        }
        return false;
    }
}
