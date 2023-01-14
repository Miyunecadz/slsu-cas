<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schedules = Schedule::join('services', 'services.id', '=', 'schedules.service_id')->where('specialist_id', auth()->user()->user_id);
        if($request->has('keyword')) {
            $schedules = $schedules->where('name', 'LIKE', "%$request->keyword%")->orWhere('description', 'LIKE', "%$request->keyword%")->orWhere('time_start', $request->keyword)->orWhere('time_end', $request->keyword)->orWhere('date', $request->keyword);
        }
        $schedules= $schedules->latest('schedules.created_at')->get();
        return view('specialists.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('specialists.schedules.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|exists:services,id',
            'time_start' => 'required',
            'time_end' => 'required',
            'date' => 'required'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        Schedule::create([
            'service_id' => $request->service,
            'specialist_id'=> auth()->user()->user_id,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'date' => $request->date,
        ]);

        return redirect(route('schedules.index'))->with('success', 'New schedule has been added to database');
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
    public function destroy(Schedule $schedule)
    {
        if(!$schedule->delete()) {
            return back()->withErrors([
                'staatus' => 'Unable to delete user'
            ]); 
        }

        return back()->with('success', 'Schedule successfully deleted!');
    }
}
