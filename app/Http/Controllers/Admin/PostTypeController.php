<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PostTypeRequest as StoreRequest;
use App\Http\Requests\Admin\PostTypeRequest as UpdateRequest;

class PostTypeController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\PostType');
		$this->xPanel->setRoute(admin_uri('p_types'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.ad type'), trans('admin::messages.ad types'));
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['details_row']);
		$this->xPanel->denyAccess(['create', 'delete']);
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => "id",
			'label' => "ID",
		]);
		$this->xPanel->addColumn([
			'name'  => "name",
			'label' => trans("admin::messages.Name"),
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'       => "name",
			'label'      => trans("admin::messages.Name"),
			'type'       => "text",
			'attributes' => [
				'placeholder' => trans("admin::messages.Name"),
			],
		]);
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
