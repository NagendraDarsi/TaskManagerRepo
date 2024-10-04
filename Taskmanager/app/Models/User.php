<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth; 
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'email',
        'password',
        'email_verified_at',
        'mobile',
        'role',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot() {
        parent::boot();
       
        //$user->notify(new AccountActivated);
        static::creating(function($model) {
            $adminUser = Auth::user(); // Ensure this returns the admin user
            // Set the created_by field to the admin user's user_id
            if ($adminUser) {
                $model->created_by = $adminUser->user_id; // Assuming user_id is the field in the users table
            }
            $model->user_id = self::getUUId();
            //password_hash( $secret, PASSWORD_DEFAULT);
            //$secret = str_random(8);
            //$model->password = password_hash( $secret, PASSWORD_DEFAULT);
            $model->created_at = \Carbon\Carbon::now();
            return true;
        });
        static::updating(function($model) {
            $adminUser = Auth::user(); // Ensure this returns the admin user
            // Set the created_by field to the admin user's user_id
            if ($adminUser) {
            //$model->updated_by = app('\App\Helpers\Utilities')->getUserDetailId();
                $model->created_by = $adminUser->user_id; // Assuming user_id is the field in the users table
            }
            // $model->updated_by = app('\App\Helpers\Utilities')->getUserDetailId();
            $model->updated_at = \Carbon\Carbon::now();
            return true;
        });


    }

    public static function getUUId() {
        $id =  sprintf( '%04x%04x1%04x1%04x1%04x1%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );

        return substr($id,0,15);
    }

     public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
}
