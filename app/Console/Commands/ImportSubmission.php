<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\File;
use App\Models\Group;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:submission {file*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import files as submission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = (array)$this->argument('file');

        foreach ($files as $path) {
            $pathInfo = pathinfo($path);
            $basename = $pathInfo['basename'];

            if (!preg_match('/^(\d+)_(.+)_(第\d+次上机)/', $basename, $matches)) {
                $this->warn("Invaild filename: {$basename}");
                continue;
            }

            list(, $student_id, , $assignmentName) = $matches;
            if (!$user = User::where('student_id', $student_id)->first()) {
                $this->warn("Can't find student with ID: {$student_id}");
                continue;
            }

            $sha512 = hash_file("sha512", $path);
            if (!$storedFile = $user->files()->where('sha512', $sha512)->first()) {
                $targetDir = File::hashToPath($sha512);
                if (!Storage::exists($targetDir)) {
                    Storage::makeDirectory($targetDir);
                }

                $destPath = Storage::path($targetDir . DIRECTORY_SEPARATOR . $sha512);

                copy($path, $destPath);
                $storedFile = $user->files()->create([
                    'random' => Str::random(80),
                    'sha512' => $sha512,
                    'size' => filesize($path),
                    'filename' => $basename,
                ]);
            }

            //Store submission
            /** @var Assignment $assignment */
            if (!$assignment = Assignment::where('title', $assignmentName)->first()) {
                $this->warn("Can't find assignment with title: {$assignmentName}");
                continue;
            }

            /** @var Group $group */
            if(!$group = $user->joinedGroups()->find($assignment->group_id)){
                $this->warn("{$basename} not in the group, skip.");
                continue;
            }

            //不允许重复提交
            if($assignment->loginSubmissions($user)->count() > 0){
                $this->warn("{$basename} exists already, skip.");
                continue;
            }


            /** @var Submission $submission */
            $submission = $assignment->submissions()->create([
                'owner_id' => $user->id,
            ]);

            $submission->files()->attach($storedFile);

            $this->info("{$basename} imported successfully.");
        }
    }
}
