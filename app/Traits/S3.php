<?php

namespace App\Traits;

use Log;

trait S3
{
	public function getDataFromDisk($filePath)
	{
    	$s3 = app('filesystem')->disk('s3');
  		try{
  			if ($s3->exists($filePath) === true) {
	  			$content = $s3->get($filePath);

	  			return $content;
	  		}
	  	}
	  	catch(\Exeception $e) {
	  		
	  		return false;
	  	}

  		return false;
	}

	public function diskStorage($file, $path, $filenamePrefix = "", $visibility = 'public', $source = 'normal')
    {
        $ext = $file->getClientOriginalExtension();
        $s3 = app('filesystem')->disk('s3');
        if($source == 'normal'){
            $filename = sprintf('%s%s.%s', $filenamePrefix, md5(time().uniqid(rand(), true)), $file->getClientOriginalExtension());
        }else{
            $filename = sprintf('%s%s.%s', $filenamePrefix, md5(time().uniqid(rand(), true)), '.png');
        }
        
        $filePath = sprintf('%s%s', $path, $filename);

        try{
            if ($s3->put($filePath, file_get_contents($file->getRealPath()), $visibility)) {

                return ["success" => true, "filename" => $filename, "fullPath" => $filePath];
            }
        }
        catch(\Exeception $e) {
            return ["success" => false, "error" => $e->getMessage()];           
        }

        return ["success" => false, "error" => "Error storing file"];
    }

	public function diskStorageFromExternal($remotePath, $path, $filenamePrefix, $ext)
	{
    	$s3 = app('filesystem')->disk('s3');
    	if (empty($filenamePrefix)) {
    		$filenamePrefix = md5(time().uniqid(rand(), true));
    	}
    	$filename = sprintf('%s.%s', $filenamePrefix, $ext);
  		$filePath = sprintf('%s%s', $path, $filename);

  		try{
	  		if ($x = $s3->put($filePath, file_get_contents($remotePath), "public")) {
	  			return ["filename" => $filename, "fullPath" => $filePath, 'url' => $s3->url($filePath)];
	  		}
	  	}
	  	catch(\Exeception $e) {
	  		return ["error" => $e->getMessage()];	  		
	  	}

  		return ["error" => "error storing file"];
	}

	public function getAwsTemporaryUrl($path, $expiration, $options = [], $method = "get")
    {
    	$adapter = app('filesystem')->disk('s3')->getAdapter();
        $client = $adapter->getClient();

        $cmd = "GetObject";
        if ($method === 'post') {
        	$cmd = "PutObject";
        }

        $command = $client->getCommand($cmd, array_merge([
            'Bucket' => $adapter->getBucket(),
            'Key' => $adapter->getPathPrefix() . $path,
        ], $options));

        return (string) $client->createPresignedRequest(
            $command, $expiration
        )->getUri();
    }
}