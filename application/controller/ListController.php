<?php

class ListController extends EA_Controller
{
	public function servicesAction()
	{
		$oQuery = new EA_Frontend_Queries_FetchServiceList();

		$oDb = EA_Db::getInstance();
		$aServices = $oDb->fetchAll($oQuery);

		$this->oView->aServices = $aServices;
	}

	public function hostsAction()
	{

	}
}