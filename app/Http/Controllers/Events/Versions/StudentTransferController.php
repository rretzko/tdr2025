<?php

namespace App\Http\Controllers\Events\Versions;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Version;
use App\Models\UserConfig;
use App\Services\CanTransferStudentService;
use Illuminate\Http\Request;

class StudentTransferController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $versionId = UserConfig::getValue('versionId');

        if (!$this->can($versionId)) {
            abort(403, 'Unauthorized.');
        }

        $data = new ViewDataFactory(__METHOD__, $versionId);

        $dto = $data->getDto();

        $id = $versionId;

        return view($dto['pageName'], compact('dto', 'id'));
    }

    /**
     * Evaluate permissions
     * @param  int  $versionId
     * @return bool
     */
    private function can(int $versionId): bool
    {
        $service = new CanTransferStudentService($versionId);

        return $service->canTransferStudent();
    }
}
