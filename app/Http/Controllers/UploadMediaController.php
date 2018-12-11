<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\Credentials;
use Redirect;

class UploadMediaController extends Controller
{
   	public function uploadFile(Request $request)
	{	
		$error = '';
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

			$filePath = 'test/' . $imageFileName;
			
			try {

				$res = $s3->putObject(array(
				    'Bucket'     => $request->bucket,
				    'Key'        => $filePath,
				    'ACL'    	 => 'public-read',
				    'SourceFile' => $image->getPathName(),
				));

				$url = $res['ObjectURL'];

			} catch (S3Exception $e) {
				
				$error = $e->getMessage();
			}
		}

		return view('UploadFile',['error' => $error, 'url' => $url ]);
	}

}
