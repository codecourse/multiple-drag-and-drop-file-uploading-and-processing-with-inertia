<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoDestroyRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoDestroyController extends Controller
{
    public function __invoke(VideoDestroyRequest $request, Video $video)
    {
        $video->delete();

        return back();
    }
}
