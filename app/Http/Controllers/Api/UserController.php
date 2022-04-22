<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Package;
use App\Repositories\Files\FileRepositoryInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var $userRepository
     * @var $packageRepository
     */
    protected $userRepository;
    protected $packageRepository;
    protected $fileRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param PackageRepositoryInterface $packageRepository
     */
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
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function checkPackage()
    {
        if (!$this->userRepository->checkPackage(Auth::user())) {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        } else {
            return app()->make(ResponseHelper::class)->success(trans('validation.checkPackageSuccess') . Auth::user()->package_id);
        }
    }

    /**
     * Controller selectPackage by client
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */

    public function selectPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id'=>'required',
        ]);
        if ($validator->fails()) {
            return app()->make(ResponseHelper::class)->validation($validator->errors()->toArray());
        }

        if (empty($this->packageRepository->find($request->package_id))) {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        }
        if ($this->userRepository->checkPackage(Auth::user())) {
            return app()->make(ResponseHelper::class)->success(trans('validation.checkPackageSuccess') . Auth::user()->package_id);
        }

        try {
            // Update package_id into user
            $this->userRepository->update($request->all(), Auth::user()->id);
            return app()->make(ResponseHelper::class)->success(trans('validation.checkPackageSuccess') . $request->package_id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }
}
