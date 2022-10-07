<?php

namespace App\NotificationService;

use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageEvent;

class NotificationService
{
    public static $table = 'notifications';

    /**
     * Adds notification to user
     *
     * @param $userID
     * @param $notification
     * @param $jsonData
     * @return bool
     */
    public static function addNotification($userID, $notification, $jsonData = null): bool
    {
        try {
            $notificaiton = new Notifications;
            $notificaiton->user_id = $userID;
            $notificaiton->notification = $notification;
            $notificaiton->data = $jsonData;
            $notificaiton->is_read = 0;
            $notificaiton->save();

            event(new MessageEvent($userID));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function notify($notification, $jsonData = null): bool
    {
        return self::addNotification(Auth::id(), $notification, $jsonData);
    }

    /**
     * Returns notificaiton of user
     *
     * @param $userID
     * @param $type -> 2 all, 0 read, 1 not read
     * @return array
     */
    public static function getNotifications($userID, $type = 0): \Illuminate\Database\Eloquent\Collection
    {
        $list = Notifications::where('user_id', $userID)
            ->orderBy('is_read')
            ->limit(10)
            ->get();
        return $list;
    }

    /**
     * @param $userID
     * @return int
     */
    public static function getTotalNewNotifications($userID): int
    {
        return Notifications::where('user_id', $userID)->where('is_read', 0)->count();
    }

    /**
     * Table name
     *
     * @return string
     */
    public static function tableName(): string
    {
        return self::$table;
    }

    /**
     * Table name
     *
     * @return string
     */
    public static function runNotification()
    {
        event(new \App\Events\MessageEvent(Auth::id()));
    }

}
