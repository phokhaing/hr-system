<?php

namespace App\Exports;

use App\RequestResign;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RequestResignExport implements FromView, WithHeadings, ShouldAutoSize
{
    private $keyword, $gender, $company, $branch, $position, $request_resign_from, $request_resign_to;

    /**
     * RequestResignExport constructor.
     * @param $keyword
     * @param $gender
     * @param $company
     * @param $branch
     * @param $position
     * @param $request_resign_from
     * @param $request_resign_to
     */
    public function __construct($keyword, $gender, $company, $branch, $position, $request_resign_from, $request_resign_to)
    {
        $this->keyword = $keyword;
        $this->gender = $gender;
        $this->company = $company;
        $this->branch = $branch;
        $this->position = $position;
        $this->request_resign_from = $request_resign_from;
        $this->request_resign_to = $request_resign_to;
    }

    /**
     * Export from table view
     *
     * @return View
     */
    public function view(): View
    {
        return view('export.staff-request-resign', [
            'request_resigns' => (new RequestResign())
                ->search(
                    $this->keyword,
                    $this->gender,
                    $this->company,
                    $this->branch,
                    $this->position,
                    $this->request_resign_from,
                    $this->request_resign_to
                )
                ->latest()
                ->get()
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}
