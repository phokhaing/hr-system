<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnityController extends Controller
{
    /**
     * Get District
     *
     * @param $province_id
     * @return mixed
     */
    public function getDistrict($province_id)
    {
        $data = DB::table('districts')->where('province_id', '=', $province_id)->get();
        return view('partials/location_option', compact('data'));
    }

    /**
     * Get Commune
     *
     * @param $district_id
     * @return mixed
     */
    public function getCommune($district_id)
    {
        $data = DB::table('communes')->where('district_id', '=', $district_id)->get();
        return view('partials/location_option', compact('data'));
    }

    /**
     * Get Village
     *
     * @param $commune_id
     * @return mixed
     */
    public function getVillage($commune_id)
    {
        $data = DB::table('villages')->where('commune_id', '=', $commune_id)->get();
        return view('partials/location_option', compact('data'));
    }

    /**
     * Generate end date probation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function end_probation(Request $request)
    {
        $duration = $request->duration;
        $em_date = $request->employment_date;
        $end_date_probation = date('Y-m-d', strtotime($em_date. ' + '.$duration.' months'));

        return \response()->json(['end_date' => date('d-M-Y', strtotime($end_date_probation)), 'status' => 1]);
    }
}
