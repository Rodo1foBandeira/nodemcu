<?php

namespace App\Http\Controllers;

use App\Group;
use App\Node;
use App\Port;
use Illuminate\Http\Request;

class ControleController extends Controller
{
    public function index()
    {
        $groups = $this->prepareGroups();
        return view('controle.index', compact('groups'));
    }

    private function getStatePin($ports, $pin)
    {
        for ($i = 0; $i < count($ports); $i++)
        {
            if ($ports[$i]['pin'] == $pin)
                return (integer)$ports[$i]['status'];
        }
    }

    private function getResponse($url)
    {
        $client = new \GuzzleHttp\Client();
        return $client->request('GET', $url)->getBody()->getContents();
    }

    private function prepareGroups()
    {
        //$nodes = Node::join('ports','ports.node_id', '=', 'nodes.id')->where('type', 'DIGITAL_OUTPUT')->with('ports')->get();
        $nodes = Node::with('ports')->get();
        $ports = [];
        foreach ($nodes as $key => $node)
        {
            $res_ports = json_decode($this->getResponse($node->ip), true);
            foreach ($node->ports as $key => $port)
            {
                // Todo: Type condition of pin
                if($port->type != 'INFRARED_OUTPUT' && $port->type != 'INFRARED_INPUT')
                {
                    $port['status'] = $this->getStatePin($res_ports, $port->pin);
                    $port['ip'] = $node->ip;
                    array_push($ports, $port);
                }
            }
        }

        $groups_ids = [];
        foreach ($ports as $key => $port)
            if (array_search($port->group_id, $groups_ids) === false)
                array_push($groups_ids, $port->group_id);
        $groups = [];
        foreach ($groups_ids as $key => $value)
        {
            $group = Group::find($value);
            $group['ports'] = $this->filterPorts($ports, $value);
            array_push($groups, $group);
        }

        return $groups;
    }

    private function filterPorts($ports, $group_id)
    {
        $ports_filtered = [];
        foreach ($ports as $key => $port)
            if ($port->group_id === $group_id)
                array_push($ports_filtered, $port);

        return $ports_filtered;
    }

    public function onOff($port_id)
    {
        $port = Port::with('node')->find($port_id);
        $client = new \GuzzleHttp\Client();
        $client->request('GET', $port->node->ip.'/?digital='.$port->pin)->getBody()->close();
        $groups = $this->prepareGroups();
        return view('controle._index', compact('groups'));
    }
}
