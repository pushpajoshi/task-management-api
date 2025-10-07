<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['task_id','user_id','comment','attachment_path'];

    public function scopeSimpleDetails($query)
    {
        return $query->select(['id', 'task_id', 'user_id', 'comment','attachment_path']);
    }

    public function task(){ 
        return $this->belongsTo(Task::class); 
    }
    public function user(){ 
        return $this->belongsTo(User::class); 
    }

    public function getAttachmentPathAttribute($val){
        return $val ? url($val) : null;    
    }

}
