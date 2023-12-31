<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoFileStoreRequest;
use App\Jobs\EncodeVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VideoFileStoreController extends Controller
{
    public function __invoke(VideoFileStoreRequest $request, Video $video)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class,
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->storeAndAttachFile($save->getFile(), $video);
        }

        $save->handler();
    }

    protected function storeAndAttachFile(UploadedFile $file, Video $video)
    {
        $video->update([
            'video_path' => $file->storeAs('videos', Str::uuid(), 'public')
        ]);

        EncodeVideo::dispatch($video);
    }
}
