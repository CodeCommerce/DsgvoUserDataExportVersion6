<?php
/**
 * Copyright (c) 2018
 * CodeCommerce - Christopher Bauer
 * www.codecommerce.de
 */

namespace CodeCommerce\UserData\Core\Events;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Module\ModuleCache;
use OxidEsales\Eshop\Core\Module\ModuleInstaller;
use OxidEsales\Eshop\Core\Registry;

/**
 * modul init class
 */
class Userdatainit
{

    /**
     * array with modules depended to this use this module
     * @var array
     */
    private $_aModules;

    /**
     * array with multishoptable
     * @var array
     */
    private $_aMultiShopTables;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->_aModules = [];

        $this->_aMultiShopTables = [];
    }

    /**
     * on active function
     */
    static function onActivate()
    {
        /**
         * check if dependencies are set
         * insert sql files
         */
        try {

            $oInit = oxNew(Userdatainit::class);
            foreach ($oInit->getDependencies() as $sModuleId) {
                $oModule = oxNew(Module::class);
                if (!$oModule->load($sModuleId) || !$oModule->isActive()) {
                    $oEx = oxNew(StandardException::class);
                    $oEx->setMessage('MODULE_NOT_FOUND');
                    throw $oEx;
                }
            }

            $oInit->checkSql();

            $oInit->addMultiShopTables();

        } catch (StandardException $oExcp) {
            Registry::get("oxUtilsView")->addErrorToDisplay($oExcp);

            /**
             * deactivate module if not all dependencies are active
             */
            $oModule = oxNew(Module::class);
            $oModule->load('cc_dsgvo_userdata');

            $oModuleCache = oxNew(ModuleCache::class, $oModule);
            /** @var oxModuleInstaller $oModuleInstaller */
            $oModuleInstaller = oxNew(ModuleInstaller::class, $oModuleCache);

            $oModuleInstaller->deactivate($oModule);
        }
    }

    /**
     * returns dependencies - module id's
     * @return array
     */
    public function getDependencies()
    {
        return $this->_aModules;
    }

    /**
     * checks if sql must be installed / updated
     */
    private function checkSql()
    {
        $query = "SELECT * FROM oxcontents WHERE OXID = 'e44dd16ba472a81a025fdbba2c2af26f'";
        $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->select($query);

        $allResults = $resultSet->fetchAll();

        if (count($allResults) == 0) {
            /**
             * getting sql from file
             */
            $sSqlFile = file_get_contents(getShopBasePath() . 'modules/codecommerce/dsgvo_userdata/setup/install.sql');
            $aSqlRows = explode(";", $sSqlFile);
            /**
             * execute sql
             */
            foreach ($aSqlRows as $sSqlRow) {
                if (trim($sSqlRow) !== '') {
                    DatabaseProvider::getDb()->Execute($sSqlRow);
                }
            }

            /**
             * update views
             */
            $oDbHandler = oxNew(DbMetaDataHandler::class);
            $oDbHandler->updateViews();

            $oEx = oxNew(StandardException::class);
            $oEx->setMessage(Registry::getLang()->translateString('DB_INSTALLED_AND_VIEWS_UPDATED'));
            throw $oEx;
        }
    }

    /**
     * adds multishop tables to oxconfig
     */
    protected function addMultiShopTables()
    {
        $aMultiShopTables = array_merge(Registry::getConfig()->getConfigParam('aMultiShopTables'), $this->_aMultiShopTables);
        $aMultiShopTables = array_unique($aMultiShopTables);

        Registry::getConfig()->saveShopConfVar('arr', 'aMultiShopTables', $aMultiShopTables);
    }

    /**
     * unset table from multishoptable config
     * @param $aMultiShopTables array for all multishop tables
     * @param $sTableName       tablename
     * @return mixed
     */
    protected function removeMultiShopTableFromConfig($aMultiShopTables, $sTableName)
    {
        foreach ($aMultiShopTables as $key => $svalue) {
            if ($svalue == '$sTableName')
                unset($aMultiShopTables[$key]);
        }

        return $aMultiShopTables;
    }
}