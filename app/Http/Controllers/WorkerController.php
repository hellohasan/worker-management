<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use App\Models\Worker;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class WorkerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:workers', ['only' => ['index']]);
        $this->middleware('permission:workers-create', ['only' => ['create']]);
        $this->middleware('permission:workers-store', ['only' => ['store']]);
        $this->middleware('permission:workers-show', ['only' => ['show']]);
        $this->middleware('permission:workers-edit', ['only' => ['edit']]);
        $this->middleware('permission:workers-update', ['only' => ['update']]);
        $this->middleware('permission:workers-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = 'Manage Workers';
        if ($request->ajax()) {
            $user = Auth::user();
            $query = DB::table('workers')
                ->leftJoin('users', 'workers.user_id', '=', 'users.id')
                ->leftJoin('categories', 'workers.category_id', '=', 'categories.id')
                ->leftJoin('statuses', 'workers.status_id', '=', 'statuses.id')
                ->select([
                    'workers.*',
                    'users.name as workerName',
                    'users.phone', 'users.email', 'users.avatar',
                    'categories.name as categoryName',
                    'statuses.name as statusName',
                ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    return $row->workerName . '<br>' . $row->email . '<br>' . $row->phone;
                })
                ->filterColumn('details', function ($query, $keyword) {
                    $sql = "CONCAT(users.name,'-',users.email,'-',users.phone)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('age', function ($query, $order) {
                    $query->orderBy('workers.dob', $order);
                })
                ->orderColumn('passport', function ($query, $order) {
                    $query->orderBy('workers.passport_ex', $order);
                })
                ->orderColumn('visa', function ($query, $order) {
                    $query->orderBy('workers.visa_ex', $order);
                })
                ->editColumn('avatar', function ($row) {
                    $url = asset("storage/users/{$row->avatar}");
                    return '<img src="' . $url . '" alt="">';
                })
                ->editColumn('passport', function ($row) {
                    if (Carbon::parse($row->passport_ex)->isPast()) {
                        $expire = 'Expired';
                    } else {
                        $expire = Carbon::parse($row->passport_ex)->diff(Carbon::now())->format('%y Years %m Month %d Days');
                    }
                    return $row->passport . '<br>' . $expire;
                })
                ->editColumn('visa', function ($row) {
                    if (Carbon::parse($row->visa_ex)->isPast()) {
                        $expire = 'Expired';
                    } else {
                        $expire = Carbon::parse($row->visa_ex)->diff(Carbon::now())->format('%y Years %m Month %d Days');
                    }
                    return $row->visa . '<br>' . $expire;
                })
                ->addColumn('age', function ($row) {
                    return $row->dob . '<br>' . Carbon::parse($row->dob)->diff(Carbon::now())->format('%y Years %m Month %d Days');
                })
                ->addColumn('action', function ($row) use ($user) {
                    $show = route('workers.show', $row->id);
                    $edit = route('workers.edit', $row->id);
                    $btn = '';
                    if ($user->hasPermissionTo('workers-show')) {
                        $btn .= '<a class="btn btn-info bold uppercase btn-xs" href="' . $show . '"><i class="far fa-eye"></i> Show</a> ';
                    }if ($user->hasPermissionTo('workers-edit')) {
                        $btn .= '<a class="btn btn-success bold uppercase btn-xs" href="' . $edit . '"><i class="far fa-edit"></i> Edit</a> ';
                    }if ($user->hasPermissionTo('workers-destroy')) {
                        $btn .= '<button class="btn btn-danger btn-xs bold uppercase delete_button" data-toggle="modal" data-target="#DeleteModal" data-id="' . $row->id . '" type="button"><i class="fa fa-trash"></i> Delete</button>';
                    }
                    return $btn;
                })
                ->rawColumns(['avatar', 'details', 'age', 'passport', 'visa', 'action'])
                ->escapeColumns()
                ->make(true);
        }
        return view('backend.workers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Add New worker';
        $data['categories'] = Category::whereStatus(true)->select(['id', 'name as text'])->get();
        $data['countries'] = CustomHelper::getCountryList();
        $data['status'] = Status::whereStatus(true)->select(['id', 'name as text'])->get();
        return view('backend.workers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'email'       => 'required|unique:users,email',
            'phone'       => 'required|unique:users,phone',
            'photo'       => 'nullable|mimes:jpg,jpeg,png',
            'category_id' => 'required',
            'dob'         => 'required',
            'address'     => 'required',
            'passport'    => 'required',
            'passport_ex' => 'required',
            'visa'        => 'required',
            'visa_ex'     => 'required',
            'charge'      => 'required',
            'status_id'   => 'required',
        ]);
        $in['name'] = $request->input('name');
        $in['email'] = $request->input('email');
        $in['phone'] = $request->input('phone');
        $in['password'] = bcrypt(Str::random(8));

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            Image::make($photo)->resize(100, 100)->save(public_path("storage/users/$filename"));
            $in['avatar'] = $filename;
        }

        $user = User::create($in);
        $role = Role::findByName('Worker');
        $user->assignRole($role);

        $wo = $request->except(['_method', '_token', 'name', 'email', 'phone', 'photo']);
        $wo['user_id'] = $user->id;
        Worker::create($wo);

        return redirect()->back()->withToastSuccess('Worker Created Successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $worker = Worker::with([
            'user:id,name,email,phone,avatar',
            'category:id,name',
            'status:id,name',
        ])->findOrFail($id);
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

        return view('backend.workers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit worker';
        $data['worker'] = Worker::with('user')->findOrFail($id);
        $data['categories'] = Category::whereStatus(true)->select(['id', 'name as text'])->get();
        $data['countries'] = CustomHelper::getCountryList();
        $data['status'] = Status::whereStatus(true)->select(['id', 'name as text'])->get();
        return view('backend.workers.edit', $data);
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
        $worker = Worker::with('user')->findOrFail($id);
        $request->validate([
            'name'        => 'required',
            'email'       => 'required|unique:users,email,' . $worker->user_id,
            'phone'       => 'required|unique:users,phone,' . $worker->user_id,
            'photo'       => 'nullable|mimes:jpg,jpeg,png',
            'category_id' => 'required',
            'dob'         => 'required',
            'address'     => 'required',
            'passport'    => 'required',
            'passport_ex' => 'required',
            'visa'        => 'required',
            'visa_ex'     => 'required',
            'charge'      => 'required',
            'status_id'   => 'required',
        ]);
        $in['name'] = $request->input('name');
        $in['email'] = $request->input('email');
        $in['phone'] = $request->input('phone');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            Image::make($photo)->resize(100, 100)->save(public_path("storage/users/$filename"));
            $in['avatar'] = $filename;
            if ($worker->user->getRawOriginal('avatar') != 'avatar.png') {
                File::delete("storage/users/{$worker->user->getRawOriginal('avatar')}");
            }
        }

        $worker->user()->update($in);

        $wo = $request->except(['_method', '_token', 'name', 'email', 'phone', 'photo']);
        $worker->update($wo);

        return redirect()->back()->withToastSuccess('Worker Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $worker = Worker::findOrFail($id);
        if ($worker->user->getRawOriginal('avatar') != 'avatar.png') {
            File::delete("storage/users/{$worker->user->getRawOriginal('avatar')}");
        }
        $worker->delete();
        return to_route('workers.index')->withToastSuccess('Worker Deleted Successfully.');
    }
}
