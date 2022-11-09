<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Worker;
use App\Models\OrderWorker;
use Illuminate\Support\Str;
use App\Models\BasicSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Gloudemans\Shoppingcart\Facades\Cart;

class HireController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super Admin|Admin|Company');
    }

    public function newHire()
    {
        $data['page_title'] = 'Worker Hire';
        $data['workers'] = Worker::with([
            'user:id,name,email,phone,avatar',
            'category:id,name',
            'status:id,name',
        ])->whereStatusId(1)->paginate(18);
        return view('hire.new', $data);
    }

    /**
     * @param Request $request
     */
    public function storeHire(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $worker = Worker::findOrFail($request->input('id'));
        if ($worker->status_id == 1) {
            //Cart::destroy();
            $chk = Cart::search(function ($records) use ($worker) {
                return $records->id == $worker->id;
            });
            if (!$chk->count()) {
                Cart::add([
                    'id'      => $worker->id,
                    'name'    => $worker->user->name,
                    'qty'     => 1,
                    'price'   => $worker->charge,
                    'weight'  => 1,
                    'options' => [
                        'country'     => $worker->country,
                        'dob'         => $worker->dob,
                        'passport'    => $worker->passport,
                        'passport_ex' => $worker->passport_ex,
                        'visa'        => $worker->visa,
                        'visa_ex'     => $worker->visa_ex,
                    ],
                ]);
                return redirect()->back()->withToastSuccess('Worker added on Cart.');
            } else {
                return redirect()->back()->withToastWarning('Worker already on your bucket.');
            }
        } else {
            return redirect()->back()->withToastWarning('Worker not available now.');
        }
    }

    public function cart()
    {
        $data['page_title'] = 'Worker Cart';
        $data['workers'] = Cart::content();
        $data['total'] = Cart::total();
        $data['subtotal'] = Cart::subtotal();
        return view('hire.cart', $data);
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        Cart::remove($id);
        return redirect()->back()->withToastSuccess('Worker Remove from bucket.');
    }

    /**
     * @param Request $request
     */
    public function confirm(Request $request)
    {
        $carts = Cart::content();
        if ($carts->count()) {
            $user = Auth::user();
            $or['custom'] = Str::random(12);
            $or['company_id'] = $user->company->id;
            $or['total'] = Cart::total();
            $order = Order::create($or);
            foreach ($carts as $cart) {
                $worker = Worker::find($cart->id);
                $worker->status_id = 2;
                $worker->save();
                $order->workers()->create([
                    'worker_id' => $cart->id,
                ]);
            }
            Cart::destroy();
            return redirect()->route('hire.history')->withToastSuccess('Order Placed successfully.');

        } else {
            return redirect()->route('hire.new')->withToastWarning('Bucket is empty.');
        }
    }

    /**
     * @param Request $request
     */
    public function history(Request $request)
    {
        $data['page_title'] = 'Order List';

        if ($request->ajax()) {
            $basic = BasicSetting::first();
            $queries = Order::query();
            $user = Auth::user();
            if ($user->hasRole('Company')) {
                $queries->whereCompanyId($user->company->id);
            }
            $queries->join('order_workers', 'orders.id', '=', 'order_workers.order_id')
                ->join('companies', 'companies.id', '=', 'orders.company_id')
                ->join('users', 'users.id', '=', 'companies.user_id')
                ->select([
                    'orders.*', 'orders.id as oid',
                    'companies.id as cid', 'companies.owner_name', 'companies.address',
                    'users.id as uid', 'users.name', 'users.phone', 'users.email',
                    DB::raw("COUNT(order_workers.order_id) as total_worker"),
                ])
                ->groupBy('orders.id');

            return DataTables::of($queries)
                ->addIndexColumn()
                ->addColumn('company_info', function ($row) {
                    return $row->name . '<br>' . $row->owner_name;
                })
                ->addColumn('company_contact', function ($row) {
                    return $row->phone . '<br>' . $row->email . '<br>' . $row->address;
                })
                ->editColumn('total_worker', function ($row) {
                    return $row->total_worker . '\'s';
                })
                ->editColumn('total', function ($row) use ($basic) {
                    return $basic->symbol . '' . $row->total;
                })
                ->editColumn('payment_at', function ($row) {
                    if ($row->payment_at) {
                        $txt = '<span class="badge badge-success">Paid</span>' . '<br>';
                        $txt .= Carbon::parse($row->payment_at)->toDateTimeString();
                    } else {
                        $txt = '<span class="badge badge-warning">Unpaid</span>';
                    }
                    return $txt;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $txt = '<span class="badge badge-success">Approved</span>';
                    } elseif ($row->status == 2) {
                        $txt = '<span class="badge badge-danger">Reject</span>';
                    } else {
                        $txt = '<span class="badge badge-warning">Pending</span>';
                    }
                    return $txt;
                })
                ->addColumn('action', function ($row) {
                    $route = route("hire-details", $row->custom);
                    $btn = '<a href="' . $route . '" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> View</a>';
                    $btn .= ' <button class="btn btn-danger btn-xs bold uppercase delete_button" data-toggle="modal" data-target="#DeleteModal" data-id="' . $row->id . '" type="button"><i class="fa fa-trash"></i> Delete</button>';
                    return $btn;
                })
                ->rawColumns(['company_info', 'company_contact', 'payment_at', 'status', 'action'])
                ->escapeColumns()
                ->make(true);
        }
        return view('hire.history', $data);
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        Cart::destroy();
        return redirect()->route('hire.new')->withToastSuccess('Bucket destroyed successfully.');
    }

    /**
     * @param Request $request
     */
    public function orderDestroy(Request $request)
    {
        $order = Order::with('workers')->findOrFail($request->input('id'));
        foreach ($order->workers as $order) {
            $wk = $order->worker;
            if ($wk) {
                $wk->status_id = 1;
                $wk->save();
            }
        }
        $order->workers()->delete();
        $order->delete();
        return redirect()->route('hire.new')->withToastSuccess('Order destroyed successfully.');
    }

    /**
     * @param $custom
     */
    public function details($custom)
    {
        $order = Order::with([
            'workers.worker.user',
            'workers.worker.category',
            'workers.worker.status',
        ])->whereCustom($custom)->firstOrFail();
        $data['page_title'] = 'Order Details #' . $custom;
        $data['order'] = $order;
        return view('hire.details', $data);
    }

    /**
     * @param $details
     */
    public function employeeDetails($custom)
    {
        $worker = Worker::with([
            'user:id,name,email,phone,avatar',
            'category:id,name',
            'status:id,name',
            'orders.order',
            'orders.order.company.user',
        ])->whereCustom($custom)->firstOrFail();

        $data['page_title'] = 'Worker Details';
        $data['worker'] = $worker;
        $data['age'] = Carbon::parse($worker->dob)->diff(Carbon::now())->format('%y Years %m Month %d Days');
        if (Carbon::parse($worker->passport_ex)->isPast()) {
            $data['passport_expire'] = 'Expired';
        } else {
            $data['passport_expire'] = Carbon::parse($worker->passport_ex)->diff(Carbon::now())->format('%y Years %m Month %d Days');
        }
        if (Carbon::parse($worker->visa_ex)->isPast()) {
            $data['visa_expire'] = 'Expired';
        } else {
            $data['visa_expire'] = Carbon::parse($worker->visa_ex)->diff(Carbon::now())->format('%y Years %m Month %d Days');
        }
        return view('hire.employee-details', $data);
    }

    /**
     * @param Request $request
     */
    public function workerDestroy(Request $request)
    {
        $log = OrderWorker::with(['worker'])->findOrFail($request->id);
        $worker = $log->worker;
        $worker->status_id = 1;
        $worker->save();
        $orderId = $log->order_id;
        $log->delete();

        $order = Order::with('workers.worker')->findOrFail($orderId);
        $total = 0;
        foreach ($order->workers as $worker) {
            if ($worker->worker) {
                $total += $worker->worker->charge;
            }
        }

        $order->total = $total;
        $order->save();

        return redirect()->back()->withToastSuccess('Worker Deleted Successfully');
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment'  => 'required',
            'status'   => 'required',
        ]);

        $status = $request->input('status');
        $payment = $request->input('payment');

        $order = Order::with(['workers.worker', 'company.user'])->findOrFail($request->input('order_id'));
        $workers = $order->workers;

        $collections = collect([]);

        if ($status == 1) {
            $companyNotification = [
                'phone'   => $order->company->user->phone,
                'message' => $order->custom . ' Order Approved.',
            ];
            $collections->push($companyNotification);
            foreach ($workers as $worker) {
                $wk = $worker->worker;
                $wk->status_id = 3;
                $wk->save();
                $collections->push([
                    'phone'   => $wk->user->phone,
                    'message' => "Your are booked now.",
                ]);
            }
        } else {
            $companyNotification = [
                'phone'   => $order->company->user->phone,
                'message' => $order->custom . ' Order Rejected.',
            ];
            $collections->push($companyNotification);
            foreach ($workers as $worker) {
                $wk = $worker->worker;
                $wk->status_id = 1;
                $wk->save();
                $collections->push([
                    'phone'   => $wk->user->phone,
                    'message' => "Your current status available now.",
                ]);
            }
        }

        if ($payment) {
            $order->payment_at = now();
        }
        $order->status = $status;
        $order->save();

        return redirect()->back()->withToastSuccess('Order Status Updated');
    }
}
