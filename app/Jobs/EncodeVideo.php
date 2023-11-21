<?php

namespace App\Jobs;

use App\Events\EncodeVideoProgress;
use App\Events\EncodeVideoStart;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class EncodeVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    /**
     * Create a new job instance.
     */
    public function __construct(public Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new EncodeVideoStart($this->video));

        FFMpeg::fromDisk('public')
            ->open($this->video->video_path)
            ->export()
            ->toDisk('public')
            ->inFormat(new \FFMpeg\Format\Video\X264())
            ->onProgress(function ($percentage) {
                event(new EncodeVideoProgress($this->video, $percentage));
            })
            ->afterSaving(function ($exporter, Media $media) {
                Storage::disk('public')->delete($this->video->video_path);

                $this->video->update([
                    'encoded' => true,
                    'video_path' => $media->getPath()
                ]);
            })
            ->save('videos/' . Str::uuid() . '.mp4');
    }
}
