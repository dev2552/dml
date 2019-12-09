<?php 

namespace App\Exports;

use App\Models\PaymentModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentsExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithMapping,ShouldAutoSize,WithEvents
{
	protected $paymentModel;
	protected $groupId;
	protected $startDate;
	protected $endDate;
	protected $currency;

	public function __construct(PaymentModel $paymentModel,$data)
	{
		$this->paymentModel = $paymentModel;
		$this->groupId  = isset($data['groupId']) ? $data['groupId'] : null;
		$this->currency = isset($data['currency']) ? $data['currency'] : null;
		$this->startDate = \Carbon\Carbon::parse($data['startDate'])->startOfDay();
		$this->endDate = \Carbon\Carbon::parse($data['endDate'])->endOfDay();
	}

	public function collection()
	{
		if($this->groupId && $this->currency) return $this->paymentModel
			->where('group_id',$this->groupId)
			->where('currency',$this->currency)
			->whereBetween('created',[$this->startDate,$this->endDate])
			->get();

		if($this->groupId) return $this->paymentModel
			->where('group_id',$this->groupId)
			->whereBetween('created',[$this->startDate,$this->endDate])
			->get();

		if($this->currency) return $this->paymentModel
			->where('currency',$this->currency)
			->whereBetween('created',[$this->startDate,$this->endDate])
			->get();


		return $this->paymentModel->whereBetween('created',[$this->startDate,$this->endDate])->get();
	}

	public function headings():array
	{
		return ['#','Group','Type','#','Price','Created','Date Payment','Description','User'];
	}

	public function map($paymentModel):array
	{
		return [

			$paymentModel->id,
			$paymentModel->group->name,
			$paymentModel->type,
			($paymentModel->server ? $paymentModel->server->s_name : ($paymentModel->domain ? $paymentModel->domain->domain : ($paymentModel->ip ? $paymentModel->ip->ip : null))),
			$paymentModel->total_price.' '.$paymentModel->currency,
			( $paymentModel->created ? $paymentModel->created->format('Y-m-d') : null ),
			( $paymentModel->payment_date ? $paymentModel->payment_date->format('Y-m-d') : null ),
			$paymentModel->description,
			( $paymentModel->createdBy ? $paymentModel->createdBy->username : null ),

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