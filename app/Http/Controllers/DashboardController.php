<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\HistoryActivity;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Quotation;
use App\Models\RequestProject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
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
        $userId = Auth::user()->id;

        $reqCount = RequestProject::where('customer_id', $userId)->count();
        $qtCount = Quotation::where('customer_id', $userId)->count();
        $poCount = PurchaseOrder::where('customer_id', $userId)->count();
        $amendCount = Contract::where('customer_id', $userId)->where('status', 'amandement_pending')->count();
        $rejectedCount = Contract::where('customer_id', $userId)->where('status', 'rejected')->count();

        $data = [
            [
                'label' => 'Request Project',
                'count' => $reqCount,
                'subtext' => 'Permintaan Penawaran Anda',
                'icon' => 'bi-send-plus-fill',
                'color' => 'blue',
                'url' => route('requests-project.index')
            ],
            [
                'label' => 'Quotation',
                'count' => $qtCount,
                'subtext' => 'Surat Penawaran Harga',
                'icon' => 'bi-file-earmark-text-fill',
                'color' => 'green',
                'url' => route('quotations.index')
            ],
            [
                'label' => 'Purchase Order',
                'count' => $poCount,
                'subtext' => 'Pesanan Pembelian Anda',
                'icon' => 'bi-file-earmark-richtext-fill',
                'color' => 'yellow',
                'url' => route('purchase-orders.index')
            ],
            [
                'label' => 'PO Amandemen',
                'count' => $amendCount,
                'subtext' => $amendCount > 0 ? "$amendCount Pengajuan Dalam Proses" : 'Tidak ada revisi aktif',
                'icon' => 'bi-arrow-repeat',
                'color' => 'purple',
                'url' => route('purchase-orders.index', ['type' => 'amandement'])
            ],
            [
                'label' => 'Kontrak Ditolak',
                'count' => $rejectedCount,
                'subtext' => $rejectedCount > 0 ? "$rejectedCount Kontrak Ditolak Pabrik" : 'Semua kontrak diterima',
                'icon' => 'bi-x-octagon-fill',
                'color' => 'red',
                'url' => route('purchase-orders.index')
            ]
        ];

        $activity = Auth::user()->activityHistory()->latest('activity_time')->limit(15)->get();

        return view('dashboard', compact('data', 'activity'));
    }

    protected function staffDashboard()
    {
        $reqCount = RequestProject::count();
        $reqPending = RequestProject::whereDoesntHave('quotation')->count();

        $qtCount = Quotation::count();
        $qtNeedProcess = Quotation::whereIn('status', ['draft', 'created', 'sent', 'negotiating'])->count();

        $poExtCount = PurchaseOrder::count();
        $poExtUnprocessed = PurchaseOrder::whereDoesntHave('internals')->count();

        $poIntCount = PurchaseOrderInternal::count();
        $poIntInProd = PurchaseOrderInternal::where('status', 'production')->count();

        $amendPending = Contract::where('status', 'amandement_pending')->count()
            + PurchaseOrder::where('status', 'amandement_pending')->whereDoesntHave('contracts')->count();

        $contractRejected = Contract::where('status', 'rejected')->count();

        $data = [
            [
                'label' => 'Request Project',
                'count' => $reqCount,
                'subtext' => $reqPending > 0 ? "$reqPending Permintaan Baru Masuk" : 'Semua request ditanggapi',
                'icon' => 'bi-send-plus-fill',
                'color' => 'blue',
                'url' => route('requests-project.index')
            ],
            [
                'label' => 'Quotation',
                'count' => $qtCount,
                'subtext' => $qtNeedProcess > 0 ? "$qtNeedProcess Perlu Diproses / Nego" : 'Terkendali',
                'icon' => 'bi-file-earmark-text-fill',
                'color' => 'green',
                'url' => route('quotations.index')
            ],
            [
                'label' => 'PO External',
                'count' => $poExtCount,
                'subtext' => $poExtUnprocessed > 0 ? "$poExtUnprocessed Belum Masuk Internal" : 'Semua PO diproses',
                'icon' => 'bi-file-earmark-richtext-fill',
                'color' => 'yellow',
                'url' => route('purchase-orders.index')
            ],
            [
                'label' => 'PO Internal',
                'count' => $poIntCount,
                'subtext' => "$poIntInProd Item In Production",
                'icon' => 'bi-boxes',
                'color' => 'blue',
                'url' => route('purchase-orders-internal.index')
            ],
            [
                'label' => 'PO Amandemen',
                'count' => $amendPending,
                'subtext' => $amendPending > 0 ? "$amendPending Menunggu Approval" : 'Tidak ada antrean pending',
                'icon' => 'bi-arrow-repeat',
                'color' => 'purple',
                'url' => route('purchase-orders.approval-amandement')
            ],
            [
                'label' => 'Kontrak Ditolak',
                'count' => $contractRejected,
                'subtext' => $contractRejected > 0 ? "$contractRejected Perlu Revisi Target Sales" : 'Tidak ada kontrak ditolak',
                'icon' => 'bi-x-octagon-fill',
                'color' => 'red',
                'url' => route('contracts.index')
            ]
        ];

        $activity = HistoryActivity::with('user')->latest('activity_time')->limit(15)->get();

        return view('dashboard', compact('data', 'activity'));
    }

    protected function managerDashboard()
    {
        $reqCount = RequestProject::count();
        $qtCount = Quotation::count();
        $qtActiveNego = Quotation::whereIn('status', ['negotiating', 'sent'])->count();

        $poExtCount = PurchaseOrder::count();
        $poIntCount = PurchaseOrderInternal::count();

        $amendPending = Contract::where('status', 'amandement_pending')->count()
            + PurchaseOrder::where('status', 'amandement_pending')->whereDoesntHave('contracts')->count();

        $contractRejected = Contract::where('status', 'rejected')->count();

        $data = [
            [
                'label' => 'Request Project',
                'count' => $reqCount,
                'subtext' => 'Total Permintaan Proyek',
                'icon' => 'bi-send-plus-fill',
                'color' => 'blue',
                'url' => route('requests-project.index')
            ],
            [
                'label' => 'Quotation',
                'count' => $qtCount,
                'subtext' => $qtActiveNego > 0 ? "$qtActiveNego Sedang Berjalan / Nego" : 'Terkendali',
                'icon' => 'bi-file-earmark-text-fill',
                'color' => 'green',
                'url' => route('quotations.index')
            ],
            [
                'label' => 'PO External',
                'count' => $poExtCount,
                'subtext' => 'Total Purchase Order External',
                'icon' => 'bi-file-earmark-richtext-fill',
                'color' => 'yellow',
                'url' => route('purchase-orders.index')
            ],
            [
                'label' => 'PO Internal',
                'count' => $poIntCount,
                'subtext' => 'Total Item Sub-PO Internal',
                'icon' => 'bi-boxes',
                'color' => 'blue',
                'url' => route('purchase-orders-internal.index')
            ],
            [
                'label' => 'PO Amandemen',
                'count' => $amendPending,
                'subtext' => $amendPending > 0 ? "$amendPending Menunggu Approval" : 'Tidak ada antrean pending',
                'icon' => 'bi-arrow-repeat',
                'color' => 'purple',
                'url' => route('purchase-orders.approval-amandement')
            ],
            [
                'label' => 'Kontrak Ditolak',
                'count' => $contractRejected,
                'subtext' => $contractRejected > 0 ? "$contractRejected Kontrak Ditolak / Revisi" : 'Tidak ada penolakan',
                'icon' => 'bi-x-octagon-fill',
                'color' => 'red',
                'url' => route('contracts.index')
            ]
        ];

        $activity = HistoryActivity::with('user')->latest('activity_time')->limit(15)->get();

        return view('dashboard', compact('data', 'activity'));
    }

    protected function adminDashboard()
    {
        $userCount = User::count();
        $reqCount = RequestProject::count();
        $qtCount = Quotation::count();
        $poExtCount = PurchaseOrder::count();
        $poIntCount = PurchaseOrderInternal::count();
        $amendPending = Contract::where('status', 'amandement_pending')->count();
        $contractRejected = Contract::where('status', 'rejected')->count();

        $data = [
            [
                'label' => 'User Management',
                'count' => $userCount,
                'subtext' => 'Total Pengguna Sistem',
                'icon' => 'bi-people-fill',
                'color' => 'blue',
                'url' => route('users.index')
            ],
            [
                'label' => 'Request Project',
                'count' => $reqCount,
                'subtext' => 'Total Permintaan Proyek',
                'icon' => 'bi-send-plus-fill',
                'color' => 'blue',
                'url' => route('requests-project.index')
            ],
            [
                'label' => 'Quotation',
                'count' => $qtCount,
                'subtext' => 'Total Surat Penawaran',
                'icon' => 'bi-file-earmark-text-fill',
                'color' => 'green',
                'url' => route('quotations.index')
            ],
            [
                'label' => 'PO External',
                'count' => $poExtCount,
                'subtext' => 'Total PO Customer',
                'icon' => 'bi-file-earmark-richtext-fill',
                'color' => 'yellow',
                'url' => route('purchase-orders.index')
            ],
            [
                'label' => 'PO Internal',
                'count' => $poIntCount,
                'subtext' => 'Total Item Sub-PO Pabrik',
                'icon' => 'bi-boxes',
                'color' => 'blue',
                'url' => route('purchase-orders-internal.index')
            ],
            [
                'label' => 'PO Amandemen',
                'count' => $amendPending,
                'subtext' => "$amendPending Menunggu Approval",
                'icon' => 'bi-arrow-repeat',
                'color' => 'purple',
                'url' => route('purchase-orders.approval-amandement')
            ],
            [
                'label' => 'Kontrak Ditolak',
                'count' => $contractRejected,
                'subtext' => "$contractRejected Kontrak Perlu Revisi",
                'icon' => 'bi-x-octagon-fill',
                'color' => 'red',
                'url' => route('contracts.index')
            ]
        ];

        $activity = HistoryActivity::with('user')->latest('activity_time')->limit(15)->get();

        return view('dashboard', compact('data', 'activity'));
    }
}
