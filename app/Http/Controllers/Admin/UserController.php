<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\Files\FileRepositoryInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use phpseclib3\Crypt\Hash;

class UserController extends Controller
{
    protected $userRepository;
    protected $packageRepository;
    protected $fileRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        PackageRepositoryInterface $packageRepository,
        FileRepositoryInterface $fileRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->packageRepository = $packageRepository;
        $this->fileRepository = $fileRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getAll(10,'desc');
        return view('admin.users.index', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    public function detail($id)
    {
        $user = $this->userRepository->find($id);
        $package = $this->packageRepository->find($user->package_id);
        $totalFiles = $this->userRepository->countTotalFilesByOtherUser($user);
        $lastFileUpload = $this->userRepository->lastFileUpload($user);
        $files = $this->fileRepository->listFileOfOtherUser($user);
        return view('admin.users.detail', [
            'title' => 'User',
            'user' => $user,
            'package' => $package,
            'totalFiles' => $totalFiles,
            'lastFileUpload' => $lastFileUpload,
            'files' => $files,
        ]);
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        $package = $this->packageRepository->find($user->package_id);
        $totalFiles = $this->userRepository->countTotalFilesByOtherUser($user);
        $lastFileUpload = $this->userRepository->lastFileUpload($user);
        $files = $this->fileRepository->listFileOfOtherUser($user);
        return view('admin.users.edit', [
            'title' => 'User',
            'user' => $user,
            'package' => $package,
            'totalFiles' => $totalFiles,
            'lastFileUpload' => $lastFileUpload,
            'files' => $files,
        ]);
    }

    public function update(Request $request,$id){
        $data = $request->all();
        if (is_null($data['password'])){
            unset($data['password']);
        } else {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }
        try {
            $this->userRepository->update($data,$id);
            Session::flash('success','Update user success');
        } catch (\Exception $err){
            Session::flash('error','Update user fail');
            \Log::info($err->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Controller function show file for ajax
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function showFile(Request $request)
    {
        $file = $this->fileRepository->find($request->id);
        if (empty($file)) {
            return app()->make(ResponseHelper::class)->notfound();
        }
        return app()->make(ResponseHelper::class)->success($file);
    }
}
