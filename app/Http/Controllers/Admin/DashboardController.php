<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Files\FileRepositoryInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * @var UserRepositoryInterface
     * @var FileRepositoryInterface
     * @var PackageRepositoryInterface
     */
    protected $userRepository;
    protected $fileRepository;
    protected $packageRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FileRepositoryInterface $fileRepository,
        PackageRepositoryInterface $packageRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->fileRepository = $fileRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * Controller function show index dashboard page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $totalUsers = $this->userRepository->totalUsers();
        $totalFiles = $this->fileRepository->totalFiles();
        $totalPackagesUsed = $this->packageRepository->totalPackagesUsed();
        return view('admin.dashboard', [
            'title'=>'Dashboard',
            'totalUsers' => $totalUsers,
            'totalFiles' => $totalFiles,
            'totalPackagesUsed' => $totalPackagesUsed,
        ]);
    }

    /**
     * Controller function show data on Donut Chart
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function donutChart()
    {
        $arr = [];
        $packages = $this->packageRepository->listAll();
        foreach ($packages as $package) {
            $arr_tmp = [
                'name' => $package->name,
                'total_user' => $this->packageRepository->totalPackageUsed($package->id)
            ];
            array_push($arr, $arr_tmp);
        }
        return app()->make(ResponseHelper::class)->success($arr);
    }

    /**
     * Controller function show data on Area Chart
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function areaChart()
    {
        $files = array();
        for ($i = 6; $i>=0; $i--) {
            $files_tmp['total_file'] = DB::table('files')->whereDate('created_at', Carbon::now()->subDays($i))->count();
            $files_tmp['date'] = Carbon::now()->subDays($i)->format('d/m');
            $files[$i] = $files_tmp;
        }
        return app()->make(ResponseHelper::class)->success($files);
    }
}
