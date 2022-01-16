<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;

/**
 * @group Miscellaneous
 *
 * API methods for basic information
 */
class HomeController extends Controller
{
    /**
     * Healthcheck
     *
     * Check that the service is up. If everything is okay, you'll get a 200 OK response.
     *
     * Otherwise, the request will fail with a 400 error, and a response listing the failed services.
     *
     * @unauthenticated
     * @response 400 scenario="Service is unhealthy" {"status": false, "services": {"database": false}}
     * @responseField status The status of this API (`true` or `false`).
     * @responseField services Map of each downstream service and their status (`true` or `false`).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function healthCheck()
    {
        $isDatabaseUp = false;

        try {
            DB::connection()->getPdo();

            $isDatabaseUp = true;
        } catch (Exception $e) {
            //
        }

        $status = $isDatabaseUp;

        return response()->json([
            'status' => $status,
            'services' => [
                'database' => $isDatabaseUp,
            ],
        ]);
    }

    /**
     * Retrieve own user
     *
     * @response scenario=success {
     *  "id": 1,
     *  "name": "John Doe",
     *  "email": "john_doe@test.com",
     *  "email_verified_at": "2021-07-19T16:52:07.000000Z",
     *  "current_team_id": null,
     *  "profile_photo_path": "profile-photos/tQoWDCXYOOOK15OykHUBLnyrTvB76laGnIAwtaj8.jpg",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "profile_photo_url": "/storage/profile-photos/tQoWDCXYOOOK15OykHUBLnyrTvB76laGnIAwtaj8.jpg"
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        if (!$request->user()->tokenCan('user:read')) {
            abort(403, 'This action is unauthorized.');
        }

        return $request->user();
    }
}
