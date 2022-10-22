<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:companies', ['only' => ['index']]);
        $this->middleware('permission:companies-create', ['only' => ['create']]);
        $this->middleware('permission:companies-store', ['only' => ['store']]);
        $this->middleware('permission:companies-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:companies-update', ['only' => ['edit']]);
        $this->middleware('permission:companies-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['page_title'] = 'Company List';
        if ($request->ajax()) {
            $user = Auth::user();
            $query = DB::table('companies')
                ->leftJoin('users', 'companies.user_id', '=', 'users.id')
                ->select([
                    'companies.*',
                    'users.name', 'users.email', 'users.phone',
                ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->filterColumn('name', function ($query, $keyword) {
                    $sql = "users.name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $sql = "users.email like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('phone', function ($query, $keyword) {
                    $sql = "users.phone like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge badge-success">Activated</span>';
                    } else {
                        return '<span class="badge badge-warning">Deactivated</span>';
                    }
                })
                ->addColumn('action', function ($row) use ($user) {
                    $show = route('companies.show', $row->id);
                    $edit = route('companies.edit', $row->id);
                    $btn = '';
                    if ($user->hasPermissionTo('companies-show')) {
                        $btn .= '<a class="btn btn-info bold uppercase btn-xs" href="' . $show . '"><i class="far fa-eye"></i> Show</a> ';
                    }if ($user->hasPermissionTo('companies-edit')) {
                        $btn .= '<a class="btn btn-success bold uppercase btn-xs" href="' . $edit . '"><i class="far fa-edit"></i> Edit</a> ';
                    }if ($user->hasPermissionTo('companies-destroy')) {
                        $btn .= '<button class="btn btn-danger btn-xs bold uppercase delete_button" data-toggle="modal" data-target="#DeleteModal" data-id="' . $row->id . '" type="button"><i class="fa fa-trash"></i> Delete</button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('backend.companies.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Add New Company';
        return view('backend.companies.create', $data);
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
            'name'            => 'required',
            'owner_name'      => 'required',
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'required|unique:users,email',
            'register_number' => 'required',
            'leave_number'    => 'required',
            'address'         => 'required',
            'password'        => 'required|min:6|confirmed',
        ]);

        $in['name'] = $request->input('name');
        $in['email'] = $request->input('email');
        $in['phone'] = $request->input('phone');
        $in['password'] = bcrypt($request->input('password'));
        $user = User::create($in);
        $role = Role::findByName('Company');
        $user->assignRole($role);

        $co['user_id'] = $user->id;
        $co['owner_name'] = $request->input('owner_name');
        $co['register_number'] = $request->input('register_number');
        $co['leave_number'] = $request->input('leave_number');
        $co['address'] = $request->input('address');
        $co['status'] = $request->input('status') == 'on' ? true : false;
        Company::create($co);

        return redirect()->back()->withToastSuccess('Company Created Successfully.');
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
        $data['page_title'] = 'Edit Company';
        $data['company'] = Company::with('user')->findOrFail($id);
        return view('backend.companies.edit', $data);
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
        $company = Company::with('user')->findOrFail($id);
        $request->validate([
            'name'            => 'required',
            'owner_name'      => 'required',
            'email'           => 'required|email|unique:users,email,' . $company->user_id,
            'phone'           => 'required|unique:users,email,' . $company->user_id,
            'register_number' => 'required',
            'leave_number'    => 'required',
            'address'         => 'required',
            'password'        => 'nullable|confirmed|min:6',
        ]);

        $in['name'] = $request->input('name');
        $in['email'] = $request->input('email');
        $in['phone'] = $request->input('phone');
        if ($request->filled('password')) {
            $in['password'] = bcrypt($request->input('password'));
        }
        $company->user()->update($in);

        $co['owner_name'] = $request->input('owner_name');
        $co['register_number'] = $request->input('register_number');
        $co['leave_number'] = $request->input('leave_number');
        $co['address'] = $request->input('address');
        $co['status'] = $request->input('status') == 'on' ? true : false;
        $company->update($co);

        return redirect()->back()->withToastSuccess('Company Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return redirect()->route('companies.index')->withToastSuccess('Company Deleted Successfully.');
    }
}
