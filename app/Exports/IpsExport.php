<?php 

namespace App\Exports;

use App\Models\IpModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class IpsExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithMapping,ShouldAutoSize,WithEvents
{
	protected $ipModel;
	protected $startDate;
	protected $endDate;

	public function __construct(IpModel $ipModel,$data)
	{
		$this->ipModel = $ipModel;
		$this->startDate = $data['startDate'];
		$this->endDate = $data['endDate'];
	}

	public function collection()
	{
		return $this->ipModel->whereBetween('created',[Carbon::parse($this->startDate)->startOfDay(),Carbon::parse($this->endDate)->endOfDay()])->get();
	}

	public function headings():array
	{
		return [

			'#','Ip','Provider','Group','NetMask','Server','Gateway','Price','Status','Active',

		];
	}

	public function map($ipModel):array
	{
		return [

			$ipModel->id,
			$ipModel->ip,
			$ipModel->provider,
			($ipModel->server ? $ipModel->server->group->name : null),
			$ipModel->netmask,
			($ipModel->server ? $ipModel->server->s_name : null),
			$ipModel->gateway,
			$ipModel->price.' '.$ipModel->currency,
			$ipModel->ip_status,
			$ipModel->is_active ? 'Active' : 'Inactive',

		];
	}

	public function registerEvents() : array 
	{
		return [
			AfterSheet::class => function(AfterSheet $event)
			{
				$event->sheet->getStyle('A1:J1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
			}
		];
	}



}