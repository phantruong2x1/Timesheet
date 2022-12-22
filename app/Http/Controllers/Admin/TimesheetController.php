<?php

namespace App\Http\Controllers;
use App\Models\Timesheet;

use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function postAdd(Request $request)
    {
        $timeSheets = new Timesheet();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sciener.com/v3/lockRecord/list?clientId=431201014e0544ebb8122bdaa68fd534&accessToken=3429d1f497d1d793083304f054bb0472&lockId=4910283&pageNo=1&pageSize=10&date=1671596023185&startDate=0&endDate=0',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        //$response = curl_exec($curl);
        $response = curl_exec($curl);

        //return json
        $list = json_decode($response);
        var_dump($list);
        curl_close($curl);

        $timeSheets->position_name = $request->position_name;
        $timeSheets->position_desc = $request->position_desc;

        //Lưu
        $timeSheets->save();

        return dd($list);
    }
}
