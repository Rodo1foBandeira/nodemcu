<?php

namespace App\Http\Controllers;

use App\Code;
use App\Device;
use App\Port;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    const ROUTE = 'device';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::all();
        return view($this::ROUTE.'.index',compact('devices'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ports = Port::where('type', 'INFRARED_INPUT')->pluck('name', 'id');
        return view($this::ROUTE.'.create', compact('ports'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $device = Device::create($request->all());
        $codes = $request->input('codes');
        foreach ($codes as $key => &$code)
        {
            $code['device_id'] = $device->id;
        }
        Code::insert($codes);
        return redirect($this::ROUTE);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ports = Port::where('type', 'INFRARED_INPUT')->pluck('name', 'id');
        $device = Device::find($id);
        return view($this::ROUTE.'.edit',compact('device', 'ports'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $device = Device::with('codes')->find($id);
        $device->fill($request->all());

        $rq_codes_ids = [];
        if ($request->has('codes'))
            foreach ($request->input('codes') as $key => $rq_code)
            {
                if (isset($rq_code['id']))
                    array_push($rq_codes_ids, (integer)$rq_code['id']);
            }

        foreach ($device->codes as $key => &$code)
        {
            if (array_search($code->id, $rq_codes_ids) === false)
            {
                $code->delete();
            }
        }

        if ($request->has('codes'))
            foreach ($request->input('codes') as $key => $rq_code)
            {
                if (isset($rq_code['id']))
                {
                    $code = $device->codes->find((integer)$rq_code['id']);
                    $code->name = $rq_code['name'];
                    $code->code = $rq_code['code'];
                    $code->update();
                }else{
                    $device->codes()->create($rq_code);
                }
            }
        $device->update();
        return redirect($this::ROUTE);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Device::find($id)->delete();
        return redirect($this::ROUTE);
    }

    public function lerCodigo($port_id)
    {
        $port = Port::with('node')->find($port_id);
        $client = new \GuzzleHttp\Client();
        $retorno = $client->request('GET', $port->node->ip.'/?getInfrared=0')->getBody();
        //$client->close();
        return $retorno;
    }

    public function getCodes($device_id)
    {
        $codes = Code::where('device_id',$device_id)->select('id','name')->get();
        return response()->json($codes);
    }
}
