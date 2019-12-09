<?php 

namespace App\Exports;

use App\Models\DomainModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Carbon\Carbon;

class DomainsExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithMapping,ShouldAutoSize,WithEvents
{
	protected $domainModel;
	protected $groupId;
	protected $startDate;
	protected $endDate;

	public function __construct(DomainModel $domainModel,$data)
	{
		$this->domainModel = $domainModel;
		$this->groupId = isset($data['groupId']) ?  $data['groupId'] : null;
		$this->startDate = $data['startDate'];
		$this->endDate = $data['endDate'];
	}

	public function collection()
	{
		if($this->groupId) return $this->domainModel->where('group_id',$this->groupId)->whereBetween('datec',[Carbon::parse($this->startDate)->startOfDay(),Carbon::parse($this->endDate)->endOfDay()])->get();
		return $this->domainModel->whereBetween('datec',[Carbon::parse($this->startDate)->startOfDay(),Carbon::parse($this->endDate)->endOfDay()])->get();
	}

	public function headings():array
	{
		return [

			'#','Group','Domain','Registrar','Purchased','Expiration','Created','Status','Active',

		];
	}

	public function map($domainModel):array
	{
		return [

			$domainModel->id,
			$domainModel->group->name,
			$domainModel->domain,
			($domainModel->registrar ? $domainModel->registrar->name : null),
			($domainModel->entered ? $domainModel->entered->format('Y-m-d') : null),
			($domainModel->datex ? $domainModel->datex->format('Y-m-d') : null),
			($domainModel->datec ? $domainModel->datec->format('Y-m-d') : null),
			$domainModel->status,
			$domainModel->is_active ? 'Active' : 'Inactive',

		];
	}


	public function registerEvents() : array 
	{
		return [
			AfterSheet::class => function(AfterSheet $event)
			{
				$event->sheet->getStyle('A1:I1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
			}
		];
	}



}