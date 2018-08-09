<?php

/*
 *
 * PAGER WIDGET CLASS
 * By: C. Moller - 20 Feb 2016
 * Updated: 23 Apr 2016 - Added $baseUrl
 *
 */

class PagerWidget {

	public $itemspp;
	public $pageno;
	public $offset;
	public $baseUrl;
	public $navUrls;
	public $lastpage;
	public $itemscount;

	public $keep;
	public $remove;


	protected function getNavUrls()
	{
		$keep = $this->keep;
		$remove = $this->remove;
		$pageno = $this->pageno;
		$first_params = Request::getUpdatedUrlParams(['p' => 1], $keep, $remove);
		$this->navUrls['first'] = $this->baseUrl . '?' . http_build_query($first_params);
		$prev_params = Request::getUpdatedUrlParams(['p' => (($pageno > 1) ? ($pageno-1) : 1)], $keep, $remove);
		$this->navUrls['prev'] = $this->baseUrl . '?' . http_build_query($prev_params);
		$next_params = Request::getUpdatedUrlParams(['p' => (($pageno < $this->lastpage) ? ($pageno+1) : $pageno)], $keep, $remove);
		$this->navUrls['next'] = $this->baseUrl . '?' . http_build_query($next_params);
		$last_params = Request::getUpdatedUrlParams(['p' => $this->lastpage], $keep, $remove);
		$this->navUrls['last'] = $this->baseUrl . '?' . http_build_query($last_params);
		$pageno_params = Request::getUpdatedUrlParams(['dlg' => 'pageno'], $keep, $remove);
		$this->navUrls['pageno'] = $this->baseUrl . '?' . http_build_query($pageno_params);
	}


	public function config($baseUrl, $itemscount, $itemspp = 7, $pageno = 1, $uri_keep = null, $uri_remove = null)
	{
		$this->baseUrl = $baseUrl;
		$this->itemscount = $itemscount;
		$this->itemspp = $itemspp;
		$this->pageno = $pageno;
		$this->keep = empty($uri_keep)?[]:$uri_keep;
		$this->remove = empty($uri_remove)?[]:$uri_remove;
		$this->offset = ($this->pageno - 1) * $itemspp;
		$this->lastpage = ceil($this->itemscount / $this->itemspp);
		$this->getNavUrls();
		return $this;
	}


	public function limit()
	{
		return $this->offset . ',' . $this->itemspp;
	}

	protected function render()
	{
		// PAGER WIDGET TEMPLATE
		if (empty($this->navUrls))
		{
			return 'Error: Unconfigured pager-widget!';
		}
		$urls = &$this->navUrls;
		ob_start();?><a class="icon first" href="<?=$urls['first']?>">&nbsp;</a><a class="icon prev" href="<?=$urls['prev']?>">&nbsp;</a><a class="pageno" href="<?=$urls['pageno']?>" title="Klik om bladsynommer te stel">bl. <b><?=$this->pageno?></b> van <b><?=$this->lastpage?></b></a><a class="icon next" href="<?=$urls['next']?>">&nbsp;</a><a class="icon last" href="<?=$urls['last']?>">&nbsp;</a><?php return trim(ob_get_clean());
	}


	public function __toString()
	{
		return $this->render();
	}

}
