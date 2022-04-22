<?php

namespace App\Http\Controllers\Client;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Repositories\Files\FileRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $fileRepository;
    protected $userRepository;

    /**
     * @param FileRepositoryInterface $fileRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        FileRepositoryInterface $fileRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->fileRepository = $fileRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Controller function list file
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('client.page.listFile');
    }

    /**
     * Controller function list file on static path
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listFiles(Request $request)
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
     * Controller function display file detail
     *
     * @param int $id of file
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function fileDetail($id)
    {
        $file = $this->fileRepository->find($id);
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
    public function deleteFile($id)
    {
        $file = $this->fileRepository->find($id);
        if (!$file) {
            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
        }
        if (Auth::user()->id != $this->fileRepository->find($id)->user_id) {
            return app()->make(ResponseHelper::class)->validation(trans('validation.not_permission'));
        }
//        $file_path = storage_path() . '/app/public/' . $this->userRepository->trimEmail(Auth::user()->email) . '/' . $file->name;
//        if (!file_exists($file_path)) {
//            return app()->make(ResponseHelper::class)->notFound(trans('validation.dataNotFound'));
//        }
        try {
            $this->fileRepository->delete($id);
//            unlink($file_path);
            return app()->make(ResponseHelper::class)->success(trans('validation.delete_success') . $id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
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
            if ($file_upload->getSize() >= Package::findOrFail($typeFile)->max_file_size) {
                return app()->make(ResponseHelper::class)->validation(trans('validation.exceedFileSize'));
            }
            if ($this->userRepository->countTotalFiles() >= Package::findOrFail($typeFile)->max_file_upload) {
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
}
