<?php

namespace App\Http\Controllers;

// ** utils
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

// ** requests
use App\Http\Requests\StoreDevicesRequest;
use App\Http\Requests\UpdateDevicesRequest;

// ** models
use App\Models\Devices;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('devices.index', [
            'title' => 'Devices',
            'isAllowCreate' => true,
            'records' => Devices::where('created_by', $user->id)
                                ->orderBy('id', 'desc')
                                ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('devices.create', [
            'title' => 'Create New Device'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDevicesRequest $request): RedirectResponse
    {
        $record = Devices::simpan($request->validated());
        return Redirect::route('devices.index')->with('status', 'record-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Devices $device): View
    {
        $user = Auth::user();
        if ($device->created_by !== $user->id) {
            abort(404);
        }

        return view('devices.show', [
            'title' => 'Show Device',
            'record' => $device
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devices $device): View
    {
        $user = Auth::user();
        if ($device->created_by !== $user->id) {
            abort(404);
        }

        return view('devices.edit', [
            'title' => 'Edit Device',
            'record' => $device
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDevicesRequest $request, Devices $device): RedirectResponse
    {
        $user = Auth::user();
        if ($device->created_by !== $user->id) {
            return Redirect::route('devices.index')->with('status', '404');
        }

        $device->ubah($request->validated());
        return Redirect::route('devices.index')->with('status', 'record-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devices $device)
    {
        $user = Auth::user();
        if ($device->created_by !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Data is not found.'
            ], 404);
        }

        $result = $device->delete();
        
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
