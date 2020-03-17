<?php

namespace App;

class Reply extends Model
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class, 'discussion_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'reply_id');
    }
}
