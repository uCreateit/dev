<?php

namespace App\Http\Controllers;

use Redirect;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Illuminate\Contracts\Filesystem\Filesystem;

class UploadMediaController extends Controller
{	

	public function everything_in_tags($string, $tagname)
	{
	    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
	    preg_match($pattern, $string, $matches);
	    return @$matches[1];
	}

   	public function uploadFile(Request $request)
	{	
		$error = [];
		$url = '';

		if ($request->isMethod('post')) {

			$image = $request->file('media');

			$imageFileName = rand() . '_' . $image->getClientOriginalName();

			$credentials = new Credentials($request->access_key,$request->secret_key);
		    
		    $s3 = new S3Client([
		           'region' => $request->region,
		           'version' => 'latest',
		           'credentials' => $credentials
		       ]);

			$filePath = $imageFileName;
			
			try {

				$res = $s3->putObject(array(
				    'Bucket'     => $request->bucket,
				    'Key'        => $filePath,
				    'ACL'    	 => 'public-read',
				    'SourceFile' => $image->getPathName(),
				));

				$url = $res['ObjectURL'];

			} catch (S3Exception $e) {
				
				$error_msg = $e->getMessage();

				$errorCode = $this->everything_in_tags($error_msg,'Code') ?? '';
				$errormsg = $this->everything_in_tags($error_msg,'Message');
				
				switch ($errorCode) {
					case "SignatureDoesNotMatch":
						$error['secret_key'] = $errormsg;
						break;
					case "NoSuchBucket":
						$error['bucket'] = $errormsg;
						break;
					default:
						$error['error'] = $error_msg;
						break;
				}
				return Redirect::back()->withErrors($error)->withInput();
			}
		}
		return view('UploadFile',[ 'url' => $url ]);
	}
}