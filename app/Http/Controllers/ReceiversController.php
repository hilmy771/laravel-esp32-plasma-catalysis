<?php

namespace App\Http\Controllers;

// ** utils
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

// ** requests
use App\Http\Requests\StoreReceiversRequest;
use App\Http\Requests\UpdateReceiversRequest;

// ** models
use App\Models\Devices;
use App\Models\Receivers;

class ReceiversController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('receivers.index', [
            'title' => 'Receivers',
            'isAllowCreate' => true,
            'records' => Receivers::whereHas('device', function($q) use ($user) {
                                      $q->where('created_by', $user->id);
                                  })
                                  ->orderBy('id', 'desc')
                                  ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();

        return view('receivers.create', [
            'title' => 'Create New Receiver',
            'devices' => Devices::where('created_by', $user->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceiversRequest $request): RedirectResponse
    {
        $record = Receivers::simpan($request->validated());
        return Redirect::route('receivers.index')->with('status', 'record-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Receivers $receiver): View
    {
        $user = Auth::user();
        if ($receiver->device->created_by !== $user->id) {
            abort(404);
        }

        return view('receivers.show', [
            'title' => 'Show Receiver',
            'record' => $receiver,
            'devices' => Devices::where('created_by', $user->id)->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receivers $receiver): View
    {
        $user = Auth::user();
        if ($receiver->device->created_by !== $user->id) {
            abort(404);
        }

        return view('receivers.edit', [
            'title' => 'Edit Receiver',
            'record' => $receiver,
            'devices' => Devices::where('created_by', $user->id)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceiversRequest $request, Receivers $receiver): RedirectResponse
    {
        $user = Auth::user();
        if ($receiver->device->created_by !== $user->id) {
            return Redirect::route('receivers.index')->with('status', '404');
        }

        $receiver->ubah($request->validated());
        return Redirect::route('receivers.index')->with('status', 'record-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receivers $receiver)
    {
        $user = Auth::user();
        if ($receiver->device->created_by !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Data is not found.'
            ], 404);
        }

        $result = $receiver->delete();
        
        if ($result) {
            return response()->json([
                'status' => true,
                'message' => 'Data has been deleted.'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete data.'
            ], 400);
        }
    }
}
