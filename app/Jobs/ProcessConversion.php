<?php

namespace App\Jobs;

use App\Events\ConversionFinished;
use App\Http\Controllers\PreviewController;
use App\Models\Conversion;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Parsedown;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Process\Process;
use ZipArchive;

class ProcessConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Conversion
     */
    protected $conversion;

    /**
     * @var string
     */
    protected $fileFullPath;

    /**
     * @var string
     */
    protected $basename;

    /**
     * @var File
     */
    protected $file;

    /**
     * Create a new job instance.
     * @param Conversion $conversion
     * @param string $fileFullPath
     * @param string $basename
     */
    public function __construct(Conversion $conversion, string $fileFullPath, string $basename)
    {
        $this->user = Auth::user();

        $this->fileFullPath = $fileFullPath;
        $this->basename = $basename;

        $this->conversion = $conversion;
        $this->conversion->status = Conversion::PROCESSING;
        $this->conversion->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $path_parts = pathinfo($this->fileFullPath);
        $sha512 = $path_parts['basename'];

        $path_parts = pathinfo($this->basename);
        $extension = strtolower($path_parts['extension']);

        $sourceFullPath = $this->linkTempFile($this->fileFullPath, "{$sha512}.{$extension}");

        $random = $this->conversion->random;
        $targetDir = Storage::path(PreviewController::strToPath($random));
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        switch ($extension) {
            //可直接显示
            case 'html':
            case 'pdf':
            case 'jpg':
            case 'jpeg':
            case 'bmp':
            case 'png':
                copy($sourceFullPath, $targetDir . DIRECTORY_SEPARATOR . "{$random}.{$extension}");
                break;

            case 'md':
                $content = file_get_contents($sourceFullPath);
                $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content));

                $parsedown = new Parsedown();
                $html = $parsedown->text($content);
                file_put_contents($targetDir . DIRECTORY_SEPARATOR . "{$random}.html", <<<HTML
<!doctype html>
<html><body>{$html}</body></html>
HTML
                );
                break;

            case 'sql':
            case 'c':
            case 'cpp':
            case 'h':
            case 'hpp':
            case 'cs':
            case 'py':
            case 'php':
            case 'java':
            case 'js':
            case 'css':
            case 'txt':
                $content = file_get_contents($sourceFullPath);
                $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content));

                file_put_contents($targetDir . DIRECTORY_SEPARATOR . "{$random}.txt", $content);
                break;

            case 'rtf':
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'ppt':
            case 'pptx':
                $jarPath = Storage::path('jodconverter/lib/jodconverter-cli-2.2.2.jar');
                $process = new Process([
                    '/usr/bin/java', '-jar', $jarPath, '--port', '2002',
                    $sourceFullPath, $targetDir . DIRECTORY_SEPARATOR . "{$random}.pdf"
                ]);

                $process->start(); //TODO: check if success
                $process->wait();
                break;

            case 'zip':
            case 'rar':
            case '7z':
            case 'bz2':
            case 'gz':
            case 'xz':
            case 'tar':
                $outDir = $targetDir . DIRECTORY_SEPARATOR . $random;
                if (!file_exists($outDir)) {
                    mkdir($outDir, 0777, true);
                }

                if ($extension === 'zip') {
                    $detect_encoding = function ($path) {
                        $zip = new ZipArchive;
                        if ($zip->open($path)) {
                            $guess = [];
                            for ($index = 0; $index < $zip->numFiles; $index++) {
                                $rawEntryName = $zip->getNameIndex($index);
                                $rawEntryName = @iconv('UTF-8', 'CP437', $rawEntryName) ?: $rawEntryName;
                                $encoding = mb_detect_encoding($rawEntryName);

                                if (!key_exists($encoding, $guess)) {
                                    $guess[$encoding] = 1;
                                } else {
                                    $guess[$encoding]++;
                                }
                            }
                            $zip->close();
                        } else {
                            throw new Exception("Zip file can't be opened.");
                        }
                        asort($guess);
                        return key(array_reverse($guess));
                    };

                    $process = new Process([
                        '/usr/bin/unar', '-f', '-D', '-q', '-e', $detect_encoding($sourceFullPath),
                        $sourceFullPath, '-o', $outDir,
                    ]);
                } else if ($extension === 'rar') {
                    $process = new Process([
                        '/usr/bin/unrar', '-o+',
                        'x', $sourceFullPath, $outDir,
                    ]);
                } else {
                    $process = new Process([
                        '/usr/bin/7z', 'x', $sourceFullPath,
                        '-aoa', '-y', "-o{$outDir}",
                    ]);
                }

                $process->start();
                $process->wait();

                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($outDir));
                foreach ($iterator as $item) {
                    chmod($item, 0777);
                }
                break;

            default:
                $this->conversion->status = Conversion::FAIL;
                $this->conversion->log = 'Extension not support.';
                $this->conversion->save();

                event(new ConversionFinished(false, $this->user));
                return;
        }
        $this->conversion->status = Conversion::SUCCESS;
        $this->conversion->finished_at = Carbon::now();
        $this->conversion->save();

        event(new ConversionFinished(true, $this->user));
    }

    /**
     * @param string $fileFullPath
     * @param string $tmpBasename
     * @return string
     */
    protected function linkTempFile(string $fileFullPath, string $tmpBasename)
    {
        $linkDir = DIRECTORY_SEPARATOR . trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . "SE";
        if (!file_exists($linkDir)) {
            mkdir($linkDir);
        }

        $link = $linkDir . DIRECTORY_SEPARATOR . $tmpBasename;
        if (!file_exists($link)) {
            symlink($fileFullPath, $link);
        }
        return $link;
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $this->conversion->log = $exception->getMessage();
        $this->conversion->status = Conversion::FAIL;
        $this->conversion->save();
        event(new ConversionFinished(false, $this->user));
    }
}
