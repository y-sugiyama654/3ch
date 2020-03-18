<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class Reply extends Model
{
    /**
     * リプライにLIKEを付けたか判定
     *
     * @return bool true:Likeを付けた false:Likeを付けていない
     */
    public function is_liked_by_auth_user()
    {
        $id = Auth::id();

        $likers = array();

        foreach($this->likes as $like) {
            array_push($likers, $like->user_id);
        }

        if (in_array($id, $likers)) {
            return true;
        } else {
            return false;
        }
    }

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
