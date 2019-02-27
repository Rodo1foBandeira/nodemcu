<?php

namespace App\Http\Controllers;

use App\Button;
use App\Code;
use App\Device;
use App\Infrared;
use App\Port;
use Illuminate\Http\Request;

class InfraredController extends Controller
{
    const ROUTE = 'infrared';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infrareds = Infrared::all();
        return view($this::ROUTE.'.index',compact('infrareds'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ports = Port::where('type', 'INFRARED_OUTPUT')->pluck('name', 'id');
        $devices = Device::pluck('name','id');
        return view($this::ROUTE.'.create', compact('ports', 'devices'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $infrared = Infrared::create($request->all());
        $buttons = $request->input('buttons');
        foreach ($buttons as $key => &$button)
        {
            $button['infrared_id'] = $infrared->id;
            unset($button['device_id']);
        }
        Button::insert($buttons);
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
        $infrared = Infrared::with('buttons', 'buttons.code')->find($id);
        return view($this::ROUTE.'.show',compact('infrared'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$infrared = Infrared::with('buttons','buttons.code', 'buttons.code.device')->find($id);
        $infrared = Infrared::with('buttons.code.device')->find($id);
        $ports = Port::where('type', 'INFRARED_OUTPUT')->pluck('name', 'id');
        $devices = Device::pluck('name','id');
        return view($this::ROUTE.'.edit',compact('infrared', 'ports', 'devices'));
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
        $infrared = Infrared::with('buttons')->find($id);
        $infrared->fill($request->all());

        $rq_buttons_ids = [];
        if ($request->has('buttons'))
            foreach ($request->input('buttons') as $key => $rq_button)
            {
                if (isset($rq_button['id']))
                    array_push($rq_buttons_ids, (integer)$rq_button['id']);
            }

        foreach ($infrared->buttons as $key => $button)
        {
            if (array_search($button->id, $rq_buttons_ids) === false)
            {
                $button->delete();
            }
        }

        if ($request->has('buttons'))
            foreach ($request->input('buttons') as $key => $rq_button)
            {
                if (isset($rq_button['id']))
                {
                    $button = $infrared->buttons->find((integer)$rq_button['id']);
                    $button->code_id = $rq_button['code_id'];
                    $button->x = $rq_button['x'];
                    $button->y = $rq_button['y'];
                    $button->update();
                }else{
                    unset($rq_button['device_id']);
                    $infrared->buttons()->create($rq_button);
                }
            }
        $infrared->update();
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
        Infrared::find($id)->delete();
        return redirect($this::ROUTE);
    }

    public function enviarCodigo($button_id)
    {
        $button = Button::with('infrared.port.node','code.device')->find($button_id);
        $client = new \GuzzleHttp\Client();
        $retorno = $client->request('GET', $button->infrared->port->node->ip.'/?setInfrared='.$button->code->device->type.'&bits='.$button->code->device->bits.'&code='.hexdec($button->code->code))->close();
    }
}
