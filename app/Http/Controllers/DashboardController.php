<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\HistoryActivity;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        //load data disini jika perlu

        $user = Auth::user();

        // Route ke dashboard sesuai role
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'manager':
                return $this->managerDashboard();
            case 'staff':
                return $this->staffDashboard();
            case 'customer':
                return $this->customerDashboard();
            default:
                abort(403, 'Unauthorized role');
        }
    }

    protected function customerDashboard()
    {
        $data = [
            [
                'label' => 'Quotation',
                'count' => Quotation::where('customer_id', '=', Auth::user()->id)->count(),
                'icon' => 'bi bi-file-earmark-text-fill',
                'color'=> 'blue'
            ],
            [
                'label' => 'Purchase Order',
                'count' => PurchaseOrder::where('customer_id', '=', Auth::user()->id)->count(),
                'icon' => 'bi bi-file-earmark-richtext-fill',
                'color'=> 'blue'
            ],
            [
                'label' => 'PO Amandement',
                'count' => Contract::where('customer_id', '=', Auth::user()->id)
                    ->where('status', '=', 'done')->count(),
                    'icon' => 'bi bi-file-earmark-diff-fill',
                    'color'=> 'blue'
            ]
        ];

        $activity = Auth::user()->activityHistory;

        return view('dashboard', compact('data', 'activity'));
    }

    protected function staffDashboard()
    {
        $data = [
            [
                'label' => 'Quotation',
                'count' => Quotation::count(),
                'icon' => 'bi bi-file-earmark-text-fill',
                'color' => 'green'
            ],
            [
                'label' => 'Purchase Order',
                'count' => PurchaseOrder::count(),
                'icon' => 'bi bi-file-earmark-richtext-fill',
                'color' => 'yellow'
            ],
            [
                'label' => 'PO Amandement',
                'count' => Contract::where('customer_id', '=', Auth::user()->id)
                    ->where('status', '=', 'done')->count(),
                    'icon' => 'bi bi-file-earmark-diff-fill',
                    'color' => 'purple'
            ]
        ];

        $activity = Auth::user()->activityHistory;

        return view('dashboard', compact('data', 'activity'));;
    }


    protected function managerDashboard()
    {
        $data = [
            [
                'label' => 'Quotation',
                'count' => Quotation::count(),
                'icon' => 'bi bi-file-earmark-text-fill',
                'color'=> 'red'
            ],
            [
                'label' => 'Purchase Order',
                'count' => PurchaseOrder::count(),
                'icon' => 'bi bi-file-earmark-richtext-fill',
                'color'=> 'yellow'
            ],
            [
                'label' => 'PO Amandement',
                'count' => Contract::where('customer_id', '=', Auth::user()->id)
                    ->where('status', '=', 'done')->count(),
                    'icon' => 'bi bi-file-earmark-diff-fill',
                    'color'=> 'purple'
            ]
        ];

        $activity = Auth::user()->activityHistory;

        return view('dashboard', compact('data', 'activity'));
    }
    protected function adminDashboard()
    {
        $data = [
            [
                'label' => 'User',
                'count' => User::count(),
                'icon' => 'bi bi-person-workspace',
                'color' => 'blue'
            ],
            [
                'label' => 'Quotation',
                'count' => Quotation::count(),
                'icon' => 'bi bi-file-earmark-text-fill',
                'color' => 'green'
            ],
            [
                'label' => 'Purchase Order',
                'count' => PurchaseOrder::count(),
                'icon' => 'bi bi-file-earmark-richtext-fill',
                'color' => 'yellow'
            ],
            [
                'label' => 'Tinjauan Kontrak',
                'count' => Contract::count(),
                'icon' => 'bi bi-collection-fill',
                'color' => 'red'
            ]
        ];

        $activity = Auth::user()->activityHistory;

        return view('dashboard', compact('data', 'activity'));
    }
}
