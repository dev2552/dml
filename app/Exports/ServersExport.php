<?php 

namespace App\Exports;

use App\Models\ServerModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class ServersExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithMapping,ShouldAutoSize,WithEvents
{
	protected $serverModel;
	protected $groupId;
	protected $startDate;
	protected $endDate;


	public function __construct(ServerModel $serverModel,$data)
	{
		$this->serverModel = $serverModel;
		$this->groupId = isset($data['groupId']) ? $data['groupId'] : null;
		$this->currency = isset($data['currency']) ? $data['currency'] : null;
		$this->startDate = $data['startDate'];
		$this->endDate = $data['endDate'];
	}

	public function collection()
	{
		$startDate = \Carbon\Carbon::parse($this->startDate)->startOfDay();
		$endDate = \Carbon\Carbon::parse($this->endDate)->endOfDay();
		if($this->groupId) return $this->serverModel->where('group_id',$this->groupId)->whereBetween('created',[$this->startDate,$this->endDate])->get();
		return $this->serverModel->whereBetween('created',[$this->startDate,$this->endDate])->get();
	}

	public function headings():array
	{
		return [

			'#','Group','Name','Provider','Main Ip','Main Domain','N° Ips','Purchased','Order','Delivred','Expiration','Cancelation','OS','CPU','RAM','HDD','Price','Status',

		];
	}

	public function map($serverModel):array
	{
		return [

			$serverModel->id,
			($serverModel->group ? $serverModel->group->name : null),
			$serverModel->name,
			$serverModel->provider->name,
			$serverModel->main_ip,
			$serverModel->main_domain,
			count($serverModel->ips),
			($serverModel->date_purchase ? $serverModel->date_purchase->format('Y-m-d') : null),
			($serverModel->date_order ? $serverModel->date_order->format('Y-m-d') : null),
			($serverModel->date_delivred ? $serverModel->date_delivred->format('Y-m-d') : null),
			($serverModel->date_expiration ? $serverModel->date_expiration->format('Y-m-d') : null),
			($serverModel->date_cancelation ? $serverModel->date_cancelation->format('Y-m-d') : null),
			$serverModel->os,
			$serverModel->cpu,
			$serverModel->ram,
			$serverModel->hdd,
			$serverModel->price.' '.$serverModel->currency,
			($serverModel->status->last()) ? $serverModel->status->last()->status : null,

		];
	}

	public function registerEvents() : array 
	{
		return [
			AfterSheet::class => function(AfterSheet $event)
			{
				$event->sheet->getStyle('A1:R1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
			}
		];
	}



}