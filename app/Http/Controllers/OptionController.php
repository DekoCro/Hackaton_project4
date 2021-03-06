<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Option;
use App\User;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($poll_id)
    {
        // $option = new Option;

        // $names = request('name');
        $num = request('num');
        


        return view('/poll/option' , compact('poll_id' , 'num'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $names = request('name');

        foreach($names as $name){
            $option = new Option;
            $option->name = $name;
            $option->poll_id = request('poll_id');
            $option->save();
        }

        return redirect()->route('poll.display');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $option = Option::findOrFail($id);

        return view('/poll/detail' , compact('option'));
    }

    public function vote($id)
    {
        $request = request();


        $option = Option::find($id);

        $vote = \App\Vote::where('user_id' , '=', Auth::id())->join('options', 'votes.option_id', '=', 'options.id')->where('options.poll_id' , '=' , $option->poll_id)->first();

        if($vote){
            return redirect()->route('poll.display')->with('error','You already voted, not possible to vote again!');
        } else {
            $vote = new \App\Vote;
            $vote->option_id = $option->id;
            $vote->user_id = Auth::id();

            if($request->input('up')) {
                $vote->vote = 1;
                $option->rating++;
            } elseif ($request->input('down')) {
                $vote->vote = -1;
                $option->rating--;
            }
            $vote->save();
            $option->save();

            return redirect()->route('poll.display')->with('success','Item created successfully!');
        }
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
