<?php
use Illuminate\Http\Request;
use App\Infrastructure\Auth\Authentication;
use Illuminate\Support\Facades\Auth; 

/**
 * Utilities
 */
class Utilities {

	protected $auth;

	public function __construct(Request $request, Authentication $auth) {
        $this->auth = $auth;
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

    public function getUserId() {
        return $this->auth->user()->user_id;
    }

    public function getUserDetailId() {
        return 'sdfsdfsdf';
        return $this->auth->user()->userDetails->user_detail_id;
    }
 
    public function moveFileToPublic($filename) {
        $size = Storage::size($filename);
        $storage_file = \Storage::disk('local')->get($filename);
        $branch = $this->auth->branch();
        $hostelId = $this->getHostelId();
        $directoryPath='/uploads/'.$hostelId;
        $isMoved = \Storage::disk('s3')->put($directoryPath.'/'.$filename, $storage_file);        
        if($isMoved) {
            if(isset($branch))
            {
                $file = $branch->fileTrack()->save(new FileTrack(['name' => $filename, 'size' => $size, 'path' => $directoryPath]));
            }
            else
            {
                $branch = Branch::where('branch_id',$this->getBranchId())->first();
                $file = $branch->fileTrack()->save(new FileTrack(['name' => $filename, 'size' => $size, 'path' => $directoryPath]));
            }
            Storage::delete($filename);
            return $file->file_id;
        }
        else
            Storage::delete($filename);
            throw new \App\Infrastructure\Exceptions\NotFound('File Upload Failed, please try again');
    }
}