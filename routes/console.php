<?php

use App\Models\Tag;
use App\Models\TagQueue;
use App\Models\Topic;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('tag:test', function () {
    $name = 'test:2 for one';
    $topic = Topic::where('name', 'PROMOTION')->firstOrFail();
    $enablesAt = \Illuminate\Support\Carbon::now()->toDateTimeString();
    $expiresAt = \Illuminate\Support\Carbon::now()->addMinutes(2)->toDateTimeString();
    $aTag = ['topic_id' => $topic->id, 'visible' => 'YES', 'name' => $name, 'enables_at' => $enablesAt, 'expires_at' => $expiresAt];
    $tagQueue = TagQueue::where('name', $name)
        ->where('topic_id', $topic->id)
        ->get();
    if( sizeof( $tagQueue) == 0){
        $existingTag = Tag::where('name', $name)->where('topic_id', $topic->id)->first();
        if( empty( $existingTag) ){
            TagQueue::create($aTag);
            $this->comment('New tag queued');
        }else{
            $this->comment('Tag has previously been queued');
        }
    }else{
        $this->comment('Nothing in tag_queue');
    }
})->purpose('Queued tag for scheduling that expires after 2 minutes');

Artisan::command('tag:install', function () {
    $tagQueue = TagQueue::all()->toArray();
    $this->comment(print_r(json_encode($tagQueue), true));
    foreach($tagQueue as $newTag){
        $this->comment('Selecting tag: ' . $newTag['name']);
        $tag = Tag::where('name', $newTag['name'])
            ->where('topic_id', $newTag['topic_id'])
            ->where('visible', $newTag['visible'])
            ->where('expires_at', $newTag['expires_at'])
            ->first();
        if( empty( $tag ) ){
            $this->comment('Installing new tag: ' . $newTag['name']);
            DB::transaction(function () use ($newTag) {
                Tag::create($newTag);
                TagQueue::destroy($newTag['id']);
                $this->comment('Tag installed: ' . $newTag['name']);
            });
        }
    }
    $this->comment('Finished tag:install task');
})->purpose('Install queued Tags.')->everyMinute();

Artisan::command('tag:expire', function () {
    $expiresAt = \Illuminate\Support\Carbon::now()->toDateTimeString();
    Tag::where('expires_at','<=', $expiresAt)->delete();
    $this->comment('Tag installed: finished');
})->purpose('Cleanup expired Tags.')->everyMinute();

Schedule::call(function () {
})->everyMinute();
