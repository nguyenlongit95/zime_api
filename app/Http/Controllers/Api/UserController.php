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

    /**
     * Controller function list file on static path
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listFiles()
    {
        $files = $this->fileRepository->listFileOfUser();
        if (empty($files)) {
            return app()->make(ResponseHelper::class)->notFound();
        }
        foreach ($files as $file) {
            $file->file_path = storage_path() . '/' . $this->userRepository->trimEmail(Auth::user()->email) . '/' . $file->name;
        }
        return app()->make(ResponseHelper::class)->success($files);
    }

    /**
     * Controller function upload file of user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file_upload = $request->file('file');
            // Check file ext
            if ($file_upload->getClientOriginalExtension() != 'zip') {
                return app()->make(ResponseHelper::class)->validation(trans('validation.errorExt'));
            }
            // Check package of user
            $typeFile = $this->userRepository->checkTypePackage();
            if ($file_upload->getSize() > Package::findOrFail($typeFile)->max_file_size) {
                return app()->make(ResponseHelper::class)->validation(trans('validation.exceedFileSize'));
            }
            if ($this->userRepository->countTotalFiles() > Package::findOrFail($typeFile)->max_file_upload) {
                return app()->make(ResponseHelper::class)->validation(trans('validation.exceedFileSize'));
            }

            Storage::putFileAs(
                '/public/' . $this->userRepository->trimEmail(Auth::user()->email), $file_upload, $file_upload->getClientOriginalName()
            );
            $data = [
                'user_id' => Auth::user()->id,
                'name' => $file_upload->getClientOriginalName(),
                'file_size' => $file_upload->getSize(),
            ];
            $this->fileRepository->create($data);
            return app()->make(ResponseHelper::class)->success(trans('validation.uploadFileSuccess') . $file_upload->getClientOriginalName());
        } else {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        }
    }

    /**
     * Controller function display file detail
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function fileDetail(Request $request)
    {
        $file = $this->fileRepository->find($request->id);
        if (empty($file)) {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        } else {
            return app()->make(ResponseHelper::class)->success($file);
        }
    }

    /**
     * Controller function delete file of user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function deleteFile(Request $request)
    {
        $file = $this->fileRepository->find($request->id);
        if (!$file) {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        }
        if (Auth::user()->id != $this->fileRepository->find($request->id)->user_id) {
            return app()->make(ResponseHelper::class)->validation(trans('validation.not_permission'));
        }
        $file_path = storage_path() . '/app/public/' . $this->userRepository->trimEmail(Auth::user()->email) . '/' . $file->name;
        if (!file_exists($file_path)) {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        }
        try {
            $this->fileRepository->delete($request->id);
            unlink($file_path);
            return app()->make(ResponseHelper::class)->success(trans('validation.delete_success') . $request->id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }
}
