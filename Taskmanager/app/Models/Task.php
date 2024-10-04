<?php

namespace App\Models;
use app\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Database\Eloquent\SoftDeletes;
// app/Models/Task.php
class Task extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'description', 'completed', 'assigned_to','created_by','updated_by'];
    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();
       
        //$user->notify(new AccountActivated);
        static::creating(function($model) {
            $adminUser = Auth::user(); // Ensure this returns the admin user
            // Set the created_by field to the admin user's user_id
            if ($adminUser) {
                $model->created_by = $adminUser->user_id; // Assuming user_id is the field in the users table
            }
            $model->created_at = \Carbon\Carbon::now();
            return true;
        });
        static::updating(function($model) {
            $adminUser = Auth::user(); // Ensure this returns the admin user
            // Set the created_by field to the admin user's user_id
            
            if ($adminUser) {
            $model->updated_by = $adminUser->user_id;
            }
            $model->updated_at = \Carbon\Carbon::now();
            return true;
        });
    }

    // public function assignedTo()
    // {
    //     return $this->belongsTo(User::class, 'assigned_to');
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');  // 'user_id' is the foreign key
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
