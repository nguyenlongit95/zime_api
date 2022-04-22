<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Files\FileRepositoryInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpseclib3\Crypt\Hash;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     * @var PackageRepositoryInterface
     * @var FileRepositoryInterface
     */
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

    /**
     * Controller function show list user
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->getUserByRelationship();
        $packages = $this->packageRepository->listAll();
        $email = $request->get('email');
        $phone = $request->get('phone');
        $package = $request->get('package_id');
        if ($package) {
            $users = User::with('package')->orderBy('id', 'desc')->where('package_id', $package)->paginate(10);
        }
        if ($email) {
            $users = User::with('package')->orderBy('id', 'desc')->where('email', 'like', '%' . $email . '%')->paginate(10);
        }
        if ($phone) {
            $users = User::with('package')->orderBy('id', 'desc')->where('phone', 'like', '%' . $phone . '%')->paginate(10);
        }
        return view('admin.users.index', [
            'title' => 'Users',
            'users' => $users,
            'packages' => $packages,
            'param' => $request->all()
        ]);
    }

    /**
     * Controller function show information of user, package of user and list files were uploaded by this user
     *
     * @param int $id of user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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

    /**
     * Controller function show edit user information page
     *
     * @param int $id of user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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

    /**
     * Controller function edit user's information progress
     *
     * @param Request $request
     * @param int $id of user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$id)
    {
        $data = $request->all();
        if (is_null($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }
        try {
            $this->userRepository->update($data,$id);
            Session::flash('success','Update user success');
        } catch (\Exception $err) {
            Session::flash('error','Update user fail');
            \Log::info($err->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Controller function show detail file for ajax
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
