<?php

namespace App\Http\Controllers;

use App\Group;
use App\Node;
use App\Port;
use Illuminate\Http\Request;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;

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
            if ($ports[$i]->pin == $pin)
                return (integer)$ports[$i]->status;
        }
    }

    private function getResponse($url)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url, ['connect_timeout' => 5]);
            if ($res->getStatusCode() == 200) {
                return json_decode($res->getBody(), false);
            } else {
                return null;
            }
        }catch (ConnectException $ex){
            return null;
        }catch (ClientException $ex){
            return null;
        }catch (BadResponseException $ex){
            return null;
        }catch (Exception $e){
            return null;
        }
    }

    private function prepareGroups()
    {
        //$nodes = Node::join('ports','ports.node_id', '=', 'nodes.id')->where('type', 'DIGITAL_OUTPUT')->with('ports')->get();
        $nodes = Node::with('ports')->get();
        $ports = [];
        foreach ($nodes as $key => $node)
        {
            $res_ports = $this->getResponse($node->ip);
            if ($res_ports == null)
            	continue;
            foreach ($node->ports as $key => $port)
            {
                // Todo: Type condition of pin
                if($port->type != 'INFRARED_OUTPUT' && $port->type != 'INFRARED_INPUT')
                {
                    if ($port->type == 'PWM_OUTPUT' && $this->getStatePin($res_ports, $port->pin) == 1)
                    {
                        $port['status'] = 100;
                    }
                    else
                    {
                        $port['status'] = $this->getStatePin($res_ports, $port->pin) / 1024 * 100;
                    }
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
        $client = new Client();
        $client->request('GET', $port->node->ip.'/?digital='.$port->pin, ['connect_timeout' => 5])->getBody()->close();
        $groups = $this->prepareGroups();
        return view('controle._index', compact('groups'));
    }

    public function  ajustarPWM($port_id, $value)
    {
        $port = Port::with('node')->find($port_id);
        $client = new Client();
        if($value > 0 && $value < 100)
        {
            $pwm = 1024 / 100 * $value;
            $client->request('GET', $port->node->ip.'/?analog='.$port->pin.'&ciclo='.$pwm)->getBody()->close();

        }
        else
        {
            $client->request('GET', $port->node->ip.'/?digital='.$port->pin.'&set='.$value)->getBody()->close();
        }
        $groups = $this->prepareGroups();
        return view('controle._index', compact('groups'));
    }
}
