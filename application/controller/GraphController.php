<?php

class GraphController extends EA_Controller
{
	public function init()
	{
		$this->disableLayout();
	}

	public function serviceAction()
	{
		$iServiceId = (int) $this->oRequest->getParam('service_id');
		$sGraph = (string) $this->oRequest->getParam('graph');

		if ($iServiceId === 0 || $sGraph === '')
		{
			die('todo');
		}

		$oDb = EA_Db::getInstance();

		$oQuery = new EA_Frontend_Queries_FetchHostService();
		$oQuery->setHostServiceId($iServiceId);

		$aHostService = $oDb->fetchRow($oQuery);

		$oCheckGraphs = array();
		$sCheckGraphClass = 'EA_Check_' . $aHostService['key_name'] . '_Graphs';
		$sCheckResponseClass = 'EA_Check_' . $aHostService['key_name'] . '_Response';

		if (class_exists($sCheckGraphClass))
		{
			$oCheckGraphObj = new $sCheckGraphClass();
			$oCheckGraphs = $oCheckGraphObj->getAvailableGraphs();
		}

		if (!isset($oCheckGraphs[$sGraph]))
		{
			die('todo2');
		}

		$sGraphTitle = $oCheckGraphs[$sGraph]['title'];
		$sGraphFunction = $oCheckGraphs[$sGraph]['function'];

		$oQuery = new EA_Frontend_Queries_FetchHostServiceHistory();
		$oQuery->setHostServiceId($iServiceId);
		$oQuery->setInterval(1);

		$aLogs = $oDb->fetchAll($oQuery);

		require_once('jpgraph/jpgraph.php');
		require_once('jpgraph/jpgraph_line.php');

		$oGraph = new Graph(500, 200, 'auto');
		$oGraph->SetScale('textlin');
		$oGraph->title->Set($aHostService['service_name'] . ' on ' . $aHostService['host_name'] . ' - ' . $sGraphTitle);

		$aYData = array();

		foreach($aLogs as $aLog)
		{
			$oResponseObj = unserialize($aLog['response']);

			if ($oResponseObj instanceof $sCheckResponseClass)
			{
				$aYData[] = $oResponseObj->$sGraphFunction();
			}
			else
			{
				$aYData[] = 0;
			}
		}

		$oLinePlot = new LinePlot($aYData);
		$oGraph->Add($oLinePlot);
		$oGraph->Stroke();
	}
}