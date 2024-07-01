<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Driver;
use App\Models\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


// require 'vendor/autoload.php'; // Ensure Composer's autoload is included

// use Google\Client;
use Google\Client as GoogleClient;
class NotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            // 'title' => 'required|string|max:255',
            // 'message' => 'required|string',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');

        // Initialize arrays for selected users and drivers
        $selectedusers = $request->user_id ?? [];
        $selecteddrivers = $request->driver_id ?? [];
        $notifications = [];
        // Handle selected users
        foreach ($selectedusers as $user_id) {
            $user = User::find($user_id);
            if ($user) {
                $FcmToken = $user->device_token;
                $this->sendNotification($FcmToken, $title, $message);
                $notification =  $this->storeNotification($user_id, null, $title, $message ,$request);
                $notifications[] = $notification;
            }
        }

        // Handle selected drivers
        foreach ($selecteddrivers as $driver_id) {
            $driver = Driver::find($driver_id);

            if ($driver) {
                $FcmToken = $driver->device_token;
                $this->sendNotification($FcmToken, $title, $message);
                $notification =  $this->storeNotification(null, $driver_id, $title, $message);
                $notifications[] = $notification;
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully!',
            'data' => $notifications,
        ]);
    }

    /**
     * Send FCM Notification.
     *
     * @param string $FcmToken
     * @param string $title
     * @param string $message
     */
    private function sendNotification($FcmToken, $title, $message)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/bigways-parent/messages:send';
        $serviceAccountPath = base_path('public/assets/service-account-file.json');
        $accessToken = $this->getAccessToken($serviceAccountPath);

        $data = [
            "message" => [
                "token" => $FcmToken,
                "notification" => [
                    "title" => $title,
                    "body" => $message,
                ]
            ]
        ];
        $encodedData = json_encode($data);
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        $result = curl_exec($ch);
        if ($result === FALSE) {
            Log::error('Curl failed: ' . curl_error($ch));
        } else {
            Log::info('FCM Response: ' . $result);
        }

        curl_close($ch);
        return $result;

    }
    /**
     * Get the device Token for FCM Notification.
     */
    private function getAccessToken($serviceAccountPath)
    {
        $client = new GoogleClient();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithAssertion();
        }
        return $client->getAccessToken()['access_token'];
    }
    /**
     * Store notification for user or driver.
     *
     * @param int|null $user_id
     * @param int|null $driver_id
     * @param string $title
     * @param string $message
     */
    private function storeNotification($user_id, $driver_id, $title, $message)
    {
            $notification = new Notification();
            $notification->user_id = $user_id;
            $notification->driver_id = $driver_id;
            $notification->title = $title;
            $notification->message = $message;
            $notification->viewed = false;
            $notification->save();
            return $notification;
    }
    /**
     * Display the User resource.
     */
    public function showUser()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest()->get();
        foreach ($notifications as $notification) {
            if (!$notification->viewed) {
                $notification->viewed = true;
                $notification->save();
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Notification Retrive.',
            'data' => $notifications,
        ]);
    }
    /**
     * Display the Driver resource.
     */
    public function showDriver()
    {
        $notifications = Notification::where('driver_id', Auth::id())->latest()->get();
        foreach ($notifications as $notification)
        {
            if (!$notification->viewed)
            {
                $notification->viewed = true;
                $notification->save();
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Notification Retrive.',
            'data' => $notifications,
        ]);
    }
    /**
     * Display the Notification Count for User.
     */
    public function NotificationCountUser()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->where('viewed', 0)
        ->count();
        return response()->json([
            'success' => true,
            'message' => 'Notification Count.',
            'data' => $notifications,
            ]);
    }
    /**
     * Display the Notification Count for Driver.
     */
    public function NotificationCountDriver()
    {
        $notifications = Notification::where('driver_id', Auth::id())
        ->where('viewed', 0)
        ->count();
        return response()->json([
        'success' => true,
        'message' => 'Notification Count.',
        'data' => $notifications,
            ]);
    }
}
