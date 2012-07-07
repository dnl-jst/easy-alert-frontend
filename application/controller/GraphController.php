<?php

/*
 * Copyright (c) 2012, Daniel Jost
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted/provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this list
 *   of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice, this list
 *   of conditions and the following disclaimer in the documentation and/or other materials
 *   provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR
 * BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

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