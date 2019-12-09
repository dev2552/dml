<?php 

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PaginateCollection
{
	public function paginate($items, $perPage = 15, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
	}
}