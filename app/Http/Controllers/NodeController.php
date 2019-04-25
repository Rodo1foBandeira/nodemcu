<?php

namespace App\Http\Controllers;

use App\Group;
use App\Node;
use App\Port;
use App\PortType;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    const ROUTE = 'node';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nodes = Node::all();
        return view($this::ROUTE.'.index',compact('nodes'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::pluck('name', 'id');
        return view($this::ROUTE.'.create', compact('groups'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $node = Node::create($request->all());
        $ports = $request->input('ports');
        foreach ($ports as $key => &$port)
        {
            $port['node_id'] = $node->id;
        }
        Port::insert($ports);
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
        $node = Node::with('ports')->find($id);
        $groups = Group::pluck('name', 'id');
        return view($this::ROUTE.'.edit',compact('node', 'groups'));
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
        $node = Node::with('ports')->find($id);
        $node->fill($request->all());

        $rq_ports_ids = [];
        if ($request->has('ports'))
            foreach ($request->input('ports') as $key => $rq_port)
            {
                if (isset($rq_port['id']))
                    array_push($rq_ports_ids, (integer)$rq_port['id']);
            }

        foreach ($node->ports as $key => $port)
        {
            if (array_search($port->id, $rq_ports_ids) === false)
            {
                $port->delete();
            }
        }

        if ($request->has('ports'))
            foreach ($request->input('ports') as $key => $rq_port)
            {
                if (isset($rq_port['id']))
                {
                    $port = $node->ports->find((integer)$rq_port['id']);
                    $port->group_id = $rq_port['group_id'];
                    $port->pin = $rq_port['pin'];
                    $port->name = $rq_port['name'];
                    $port->type = $rq_port['type'];
                    $port->update();
                }else{
                    $node->ports()->create($rq_port);
                }
            }
        $node->update();
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
        Node::find($id)->delete();
        return redirect($this::ROUTE);
    }
}
