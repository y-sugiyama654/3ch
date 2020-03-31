<?php

namespace App;

use App\Notifications\ReplyMarkedAsBestReply;
use Illuminate\Support\Facades\Auth;

class Discussion extends Model
{
    /** ベストアンサー選出時の加算ポイント */
    const BEST_ANSWER_POINT = 100;

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function watchers()
    {
        return $this->hasMany(Watcher::class);
    }

    public function bestReply()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }

    public function getBestReply()
    {
        return Reply::find($this->reply_id);
    }

    public function markAsBestReply(Reply $reply)
    {
        $this->update([
            'reply_id' => $reply->id,
        ]);

        $this->_addExperiencePoint($reply);

        if ($reply->owner->id === $this->author->id) {
            return;
        }

        $reply->owner->notify(new ReplyMarkedAsBestReply($reply->discussion));
    }

    private function _addExperiencePoint($reply)
    {
        $reply->owner->point += self::BEST_ANSWER_POINT;
        $reply->owner->save();
    }

    public function scopeFilterByChannels($builder)
    {
        if (request()->query('channel')) {
            $channel = Channel::where('slug', request()->query('channel'))->first();
            if ($channel) {
                return $builder->where('channel_id', $channel->id);
            }
            return $builder;
        }

        return $builder;
    }
    public function is_being_watched_by_auth_user()
    {
        $id = Auth::id();

        $watchersIds = array();

        foreach($this->watchers as $watcher) {
            array_push($watchersIds, $watcher->user_id);
        }

        if (in_array($id, $watchersIds)) {
            return true;
        } else {
            return false;
        }
    }

}
