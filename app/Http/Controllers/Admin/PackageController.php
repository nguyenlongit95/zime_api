<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Repositories\Package\PackageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PackageController extends Controller
{
    protected $packageRepository;
    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }
    public function index()
    {
        $packages = $this->packageRepository->getAll(5,'desc');
        return view('admin.package.index',[
            'title' => 'Packages',
            'packages' => $packages
        ]);
    }
    public function create()
    {
        return view('admin.package.create',[
            'title' => 'Create package'
        ]);
    }
    public function store(PackageRequest $request)
    {
        try {
            $data = [
                'name' => $request->input('name'),
                'max_file_upload' => $request->input('max_file_upload'),
                'max_file_size' => $request->input('max_file_size')
            ];
            $this->packageRepository->create($data);
            Session::flash('success','Create new package success');
        } catch (\Exception $exception){
            Log::info($exception->getMessage());
        }
        return redirect()->back();
    }
    public function edit($id)
    {
        $package = $this->packageRepository->find($id);
        return view('admin.package.edit',[
            'title' => 'Edit package',
            'package' => $package
        ]);
    }
    public function update(PackageRequest $request, $id)
    {
        $data = [
            'name' => $request->input('name'),
            'max_file_upload' => $request->input('max_file_upload'),
            'max_file_size' => $request->input('max_file_size')
        ];
        try {
            $this->packageRepository->update($data,$id);
            Session::flash('success','Update product success');
        } catch (\Exception $err){
            Session::flash('error','Update permission fail');
            \Log::info($err->getMessage());
        }
        return redirect()->back();
    }

    public function delete(Request $request){
        $data = $request->all();
        if (($this->packageRepository->checkPackageUsed($data['id'])) > 0 ) {
            Session::flash('error','Package was used by someone');
            return redirect()->back();
        }
        if (empty($this->packageRepository->find($data['id']))) {
            Session::flash('error','Data not found');
            return redirect()->back();
        }
        try {
            $this->packageRepository->delete($data['id']);
            Session::flash('success','Delete package success');
        } catch (\Exception $err) {
            Session::flash('error','Delete package fail');
            \Log::info($err->getMessage());
        }
        return redirect()->back();
    }
}
