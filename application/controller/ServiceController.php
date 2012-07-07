<?php

class ServiceController extends EA_Controller
{
	public function listAction()
	{
		$oQuery = new EA_Frontend_Queries_FetchServiceList();

		$oDb = EA_Db::getInstance();
		$aServices = $oDb->fetchAll($oQuery);

		$this->oView->aServices = $aServices;
	}

	public function detailAction()
	{
		$iServiceId = (int) $this->oRequest->getParam('service_id');

		if ($iServiceId === 0)
		{
			die('tröte!');
		}

		$oQuery = new EA_Frontend_Queries_FetchHostService();
		$oQuery->setHostServiceId($iServiceId);

		$oDb = EA_Db::getInstance();
		$aService = $oDb->fetchRow($oQuery);

		$this->oView->serviceId = $iServiceId;
		$this->oView->serviceName = $aService['service_name'];
		$this->oView->hostName = $aService['host_name'];

		$sCheckGraphsClassName = 'EA_Check_' . $aService['key_name'] . '_Graphs';

		$aGraphs = array();
		if (class_exists($sCheckGraphsClassName))
		{
			$oCheckGraphsObj = new $sCheckGraphsClassName();
			$aGraphs = $oCheckGraphsObj->getAvailableGraphs();
		}

		$this->oView->aGraphs = $aGraphs;
	}
}