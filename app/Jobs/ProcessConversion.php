<?php

namespace App\Jobs;

use App\Http\Controllers\PreviewController;
use App\Models\Conversion;
use App\Models\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Parsedown;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Process\Process;

class ProcessConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

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

        $sourceFullPath = $this->link2tmpfile($this->fileFullPath, "{$sha512}.{$extension}");

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
                $parsedown = new Parsedown();
                $content = file_get_contents($sourceFullPath);
                $html = $parsedown->text($content);
                file_put_contents($targetDir . DIRECTORY_SEPARATOR . "{$random}.html", <<<HTML
<!doctype html>
<html><body>{$html}</body></html>
HTML
                );
                break;

            case 'txt':
                $content = file_get_contents($sourceFullPath);
                $encoding = mb_detect_encoding($content, ['GB2312', 'GBK', 'UTF-16', 'UCS-2', 'UTF-8', 'BIG5', 'ASCII']);
                file_put_contents(
                    $targetDir . DIRECTORY_SEPARATOR . "{$random}.txt",
                    mb_convert_encoding($content, 'UTF-8', $encoding)
                );
                break;

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
                    $process = new Process([
                        '/usr/bin/unar', '-f', '-D', '-q',
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
                        '-aoa', '-y', '-o', $outDir,
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
                return;
        }
        $this->conversion->status = Conversion::SUCCESS;
        $this->conversion->finished_at = Carbon::now();
        $this->conversion->save();
    }

    /**
     * @param string $fileFullPath
     * @param string $tmpBasename
     * @return string
     */
    protected function link2tmpfile(string $fileFullPath, string $tmpBasename)
    {
        $linkDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "SE";
        if (!file_exists($linkDir)) {
            mkdir($linkDir);
        }

        $link = $linkDir . DIRECTORY_SEPARATOR . $tmpBasename;
        if (file_exists($link)) {
            unlink($link);
        }
        symlink($fileFullPath, $link);
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
    }
}
