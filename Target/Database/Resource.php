<?php

namespace Target\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Resource extends Eloquent{

	protected $fillable = [
		'title_plus',
		'url',
		'subject_id',
		'resourcetype_id',
		'publisher_id',
		'uploader_id',
		];

	public function resourcetype()
	{
			return $this->belongsTo('Target\Database\Resourcetype');
	}

    public function publisher()
    {
        return $this->belongsTo('Target\Database\Publisher');
    }

}
