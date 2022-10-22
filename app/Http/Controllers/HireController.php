<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Order;
use App\Models\Worker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class HireController extends Controller
{
    public function newHire()
    {
        $data['page_title'] = 'Worker Hire';
        $data['workers'] = Worker::with([
            'user:id,name,email,phone,avatar',
            'category:id,name',
            'status:id,name',
        ])->whereStatusId(1)->paginate(15);
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

    public function history()
    {
        $data['page_title'] = 'Order List';
        $data['orders'] = Order::with([
            'company.user:id,name,email,phone',
            'workers',
        ])->withCount('workers')
            ->orderByDesc('id')->paginate(15);
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
}
