<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Carbon;

class DriverManagementController extends Controller
{
    public function index()
    {
        $total_drivers = Driver::count();
        return view('admin.driver_management.driver_management', ['total_drivers' => $total_drivers]);
    }

    public function driverList(Request $request)
    {
        $search = $request->search;
        $users = Driver::where(function ($query) use ($search) {
            $query->where('driver_name', 'LIKE', '%' . $search . '%');
            $query->orWhere('driver_email', 'LIKE', '%' . $search . '%');
            $query->orWhere('driver_mobile', 'LIKE', '%' . $search . '%');
        });
        if ($request->from_date) {
            $users = $users->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $users = $users->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        if ($request->status != '') {
            $users = $users->where('status', $request->status);
        }
        $drivers = $users->orderBy('id', 'DESC')->paginate(10);
        foreach ($drivers as $driver) {
           // $driver->vehicle_registration = url('storage/', $driver->vehicle_registration);
           // $driver->driver_image = url('storage/', $driver->driver_image);
            if ($driver->document_status == 0) {
                $driver->document_status = "Not Applied";
            } elseif ($driver->document_status == 1) {
                $driver->document_status = "Applied";
            } elseif ($driver->document_status == 2) {
                $driver->document_status = "Verified";
            } else {
                $driver->document_status = "Rejected";
            }
        }
        return ['data' => $drivers];
    }

    public function driverStatus(Request $request)
    {
        if ($request->driver_status == 0) {
            $driver = Driver::where('id', $request->driver_id)->update(['status' => 1]);
        }
        if ($request->driver_status == 1) {
            $driver = Driver::where('id', $request->driver_id)->update(['status' => 0]);
        }
        return true;
    }

    public function viewDocument(Request $request)
    {
        $document = Driver::select('id', 'vehicle_registration', 'driving_license','document_status')->where('id', $request->id)->first();
        if ($document->vehicle_registration != null) {
            $document->vehicle_registration = $document->vehicle_registration;
        }
        if ($document->driving_license != null) {
            $document->driving_license = $document->driving_license;
        }

        return view('admin.driver_management.document', ['documents' => $document]);
    }

    public function acceptDocument(Request $request)
    {
        $document = Driver::where('id', $request->id)->update(['document_status' => $request->document_status]);
        return true;
    }

    public function rejectDocument(Request $request)
    {
        $document = Driver::where('id', $request->id)->update(['document_status' => $request->document_status]);
        return true;
    }
}
