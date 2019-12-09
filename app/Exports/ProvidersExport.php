<?php 

namespace App\Exports;

use App\Models\ProviderModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class ProvidersExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithMapping,ShouldAutoSize,WithEvents
{
	protected $providerModel;

	public function __construct(ProviderModel $providerModel)
	{
		$this->providerModel = $providerModel;
	}

	public function collection()
	{
		return $this->providerModel->all();
	}

	public function headings():array
	{
		return [

			'#','Name','Country','Email','Created','Status','Type',

		];
	}

	public function map($providerModel):array
	{
		return [

			$providerModel->id,
			$providerModel->name,
			$providerModel->country,
			$providerModel->inscr_email,
			$providerModel->created,
			$providerModel->status,
			$providerModel->type,

		];
	}

	public function registerEvents() : array 
	{
		return [
			AfterSheet::class => function(AfterSheet $event)
			{
				$event->sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
			}
		];
	}



}