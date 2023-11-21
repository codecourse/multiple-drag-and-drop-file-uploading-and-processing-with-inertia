<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoDestroyController extends Controller
{
    public function __invoke(Video $video)
    {
        $video->delete();

        return back();
    }
}
