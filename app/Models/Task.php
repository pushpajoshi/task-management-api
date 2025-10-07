<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;



class Task extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['title','description','status','due_date','user_id'];

    public function scopeSimpleDetails($query)
    {
        return $query->select(['id', 'title', 'description', 'status','due_date','user_id']);
    }
    
   public function setDueDateAttribute($value)
    {
        if ($value) { // Check if value is not null/empty
            try {
                $date = $this->attributes['due_date'] = Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                // Optional: handle invalid format gracefully
                $this->attributes['due_date'] = null;
            }
        } else {
            $this->attributes['due_date'] = null;
        }
    }

    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
