<?php

namespace App\Http\Controllers;

use App\Input;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class InputController extends Controller
{
    const ROUTE = 'input';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inputs = Input::with('node')->get();
        return view($this::ROUTE.'.index',compact('inputs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $input = Input::with('node')->find($id);
        return view($this::ROUTE.'.show',compact('input'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function edit(Input $input)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Input $input)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Input  $input
     * @return \Illuminate\Http\Response
     */
    public function destroy(Input $input)
    {
        //
    }

    public function  getValor($id){
        $input = Input::with('node')->find($id);

        try {
            $client = new Client();
            $res = $client->request('GET', $input->node->ip, ['connect_timeout' => 5]);
            if ($res->getStatusCode() == 200) {
                return  response()->json(json_decode($res->getBody(), false));
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
}
