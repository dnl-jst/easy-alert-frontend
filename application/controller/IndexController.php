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

class IndexController extends EA_Controller
{
	protected $oAuth;

	public function init()
	{
		#$this->oAuth = EA_Controller_Auth::getInstance();
		#$this->oAuth->authenticate('info@daniel-jost.de', 'test1234');
	}

	public function indexAction()
	{
		$oQuery = new EA_Frontend_Queries_FetchServiceList();

		$oDb = EA_Db::getInstance();
		$aServices = $oDb->fetchAll($oQuery);

		$aStateCount = array(
			EA_Check_Abstract_Response::STATE_CRITICAL => 0,
			EA_Check_Abstract_Response::STATE_WARNING => 0,
			EA_Check_Abstract_Response::STATE_OK => 0
		);

		foreach ($aServices as $aService)
		{
			$sLastState = $aService['last_state'];

			if (!empty($sLastState))
			{
				$aStateCount[$sLastState]++;
			}
		}

		$this->oView->aStateCount = $aStateCount;
		$this->oView->iTotalServices = count($aServices);
	}

	public function ajaxdashboardAction()
	{

		$oQuery = new EA_Frontend_Queries_FetchServiceList();

		$oDb = EA_Db::getInstance();
		$aServices = $oDb->fetchAll($oQuery);

		$aStateCount = array(
			EA_Check_Abstract_Response::STATE_CRITICAL => 0,
			EA_Check_Abstract_Response::STATE_WARNING => 0,
			EA_Check_Abstract_Response::STATE_OK => 0
		);

		foreach ($aServices as $aService)
		{
			$sLastState = $aService['last_state'];

			if (!empty($sLastState))
			{
				$aStateCount[$sLastState]++;
			}
		}

		$response = array();
		$response['aStateCount'] = $aStateCount;
		$response['iTotalServices'] = count($aServices);

		$this->disableLayout();
		echo json_encode($response);
	}
}