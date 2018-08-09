<?php

/*
 *
 * SORT WIDGET CLASS
 * By: C. Moller - 05 May 2016
 *
 */

class SortWidget
{

	public $baseUrl;
	public $resetAfter;

	public $keep;
	public $remove;


	// Config is NOT required to use some of the widget functions like: orderby()
	public function config($baseUrl, $resetAfter = null, $keepParams = null, $removeParams = null)
	{
		$this->baseUrl = $baseUrl;
		$this->resetAfter = $resetAfter?:'desc';
		$this->keep = empty($keepParams)?[]:$keepParams;
		$this->remove = empty($removeParams)?[]:$removeParams;
		return $this;
	}


	public function getNextSortOrder($orderby = null)
	{
		return ($orderby == 'desc') ? 'asc' : 'desc';
	}


	public function makeSortUrl(array $update, $keep = null, $remove = null)
	{
		return $this->baseUrl . Request::getUpdatedQueryStr($update, $keep, $remove);
	}


	public function orderby()
	{
		$orderby = '';
		$sortfields_param = Request::get('sort');
		$sortfields = $sortfields_param ? explode('|', $sortfields_param) : [];

		if (empty($sortfields)) return $orderby;

		$sortdirs_param = Request::get('dir');
		$sortdirs = $sortdirs_param ? explode('|', $sortdirs_param) : [];

		if (empty($sortdirs)) return $orderby;

		foreach ($sortfields as $index => $field)
		{
			if ($index) { $orderby .= ','; }
			$orderby .= $field . ' ' . $sortdirs[$index];
		}

		return $orderby;
	}


	public function renderLink($title, $sortfield, $reset_after = null, $keep_params = [], $remove_params = [])
	{
		$sortdir = null;

		if( ! $reset_after) { $reset_after = $this->resetAfter; }

		if ($this->keep) { $keep_params = array_merge($this->keep, $keep_params); }
		if ($this->remove) { $remove_params = array_merge($this->remove, $remove_params); }

		$sortfields_param = Request::get('sort');
		$sortfields = $sortfields_param ? explode('|', $sortfields_param) : [];

		if (empty($sortfields))
		{
			// We do not have an existing sortfields param (but we want one when we click on this link), so add this field to $sortfields and $sortdirs
			$sortfields[] = $sortfield;
			$sortdirs[] = $this->getNextSortOrder($reset_after);
		}
		else
		{
			// We have an existing sortfields param, lets check if it includes THIS field?
			$sortfield_index = array_search($sortfield, $sortfields);

			if (is_null($sortfield_index)) { return Errors::raise('array_search($sortfield, $sortfields) returned and error!'); }

			$sortdirs_param = Request::get('dir');
			$sortdirs = $sortdirs_param ? explode('|', $sortdirs_param) : [];

			if ($sortfield_index === FALSE)
			{
				// We have a sortfields param, but it doesn't include THIS field, so add THIS field!
				$sortfields[] = $sortfield;
				$sortdirs[] = $this->getNextSortOrder($reset_after);
			}
			else
			{
				// We have a sortfields param AND it includes THIS field! So lets just update it as well as the matching $sortdir in the $sortdirs array
				$sortdir = array_get($sortdirs, $sortfield_index);

				// There MUST be a matching $sortdirs item for every $sortfields item!
				if ( ! $sortdir) { return Errors::raise('Invalid $sortdirs format.'); }

				// if (in "$reset_after" mode): Remove THIS field from $sortfields + $sortdirs. I.e. next mode = "not sorted" for THIS field.
				if ($sortdir == $reset_after)
				{
					unset($sortfields[$sortfield_index]);
					unset($sortdirs[$sortfield_index]);
				}
				// else: Just update $sortdirs to the next mode for THIS field
				else
				{
					$sortdirs[$sortfield_index] = $this->getNextSortOrder($sortdir);
				}
			}
		}

		if ($sortfields)
		{
			$update = ['sort' => implode('|', $sortfields), 'dir' => implode('|', $sortdirs)];
		}
		else
		{
			// With no $sortfields, remove both the 'sort' and 'dir' params from the sort url!
			$remove_params += ['sort', 'dir']; // Concat to $remove_params
			$update = [];
		}

		//d($sortdir,$update
		return '<a class="sort' . ($sortdir ? ' '.$sortdir : '') . '" href="' . $this->makeSortUrl($update, $keep_params, $remove_params) . '">' . $title . '</a>';
	}


	public function __toString()
	{
		return ' * SortLink Widget * ';
	}

}
