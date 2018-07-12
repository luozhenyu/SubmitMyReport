<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\File;
use App\Models\Group;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $groupId)
    {
        /** @var Group $group */
        $group = $request->user()
            ->managedGroups()->findOrFail($groupId);

        /** @var Assignment $assignments */
        $assignments = $group->assignments()
            ->orderByDesc('updated_at')
            ->paginate(6);

        return view('assignment.index', [
            'group' => $group,
            'assignments' => $assignments,
        ]);
    }

    public function create(Request $request, $groupId)
    {
        /** @var Group $group */
        $group = $request->user()
            ->managedGroups()->findOrFail($groupId);

        return view('assignment.create', [
            'group' => $group,
        ]);
    }

    public function store(Request $request, $groupId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Group $group */
        $group = $user->managedGroups()->findOrFail($groupId);

        $this->validate($request, [
            'title' => 'required|max:40|unique:assignments',
            'deadline' => 'required|date|after:now',
            'description' => 'required|max:10000',
            'sub_problem' => 'required|between:1,10',
            'attachment.*' => 'nullable|exists:files,random',
        ]);

        $attachments = (array)$request->input('attachment');
        $files = $user->files()->whereIn('random', $attachments)->get();

        /** @var Assignment $assignment */
        $assignment = $group->assignments()->create([
            'title' => $request->input('title'),
            'deadline' => $request->input('deadline'),
            'description' => clean($request->input('description')),
            'sub_problem' => $request->input('sub_problem'),
            'owner_id' => $user->id,
        ]);
        $assignment->files()->sync($files);

        return redirect("/group/{$groupId}");
    }

    public function show(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        $assignment = Assignment::findOrFail($assignmentId);

        /** @var Group $group */
        $group = $user->joinedGroups()->where('group_id', $assignment->group_id)->first();

        return view('assignment.show', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function edit(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        $assignment = Assignment::findOrFail($assignmentId);

        /** @var Group $group */
        $group = $user->managedGroups()->where('group_id', $assignment->group_id)->first();
        abort_if(empty($group), 403);

        return view('assignment.edit', [
            'group' => $group,
            'assignment' => $assignment,
        ]);
    }

    public function update(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        $assignment = Assignment::findOrFail($assignmentId);

        /** @var Group $group */
        $group = $user->managedGroups()->where('group_id', $assignment->group_id)->first();
        abort_if(empty($group), 403);

        $this->validate($request, [
            'title' => [
                'required',
                'max:40',
                Rule::unique('assignments')->ignore($assignment->id),
            ],
            'deadline' => 'required|date|after:now',
            'description' => 'required|max:10000',
            'sub_problem' => 'required|between:1,10',
            'attachment.*' => 'nullable|exists:files,random',
        ]);

        $attachments = (array)$request->input('attachment');
        $files = File::query()->whereIn('random', $attachments)->get();

        /** @var Assignment $assignment */
        $assignment->update([
            'title' => $request->input('title'),
            'deadline' => $request->input('deadline'),
            'description' => clean($request->input('description')),
            'sub_problem' => $request->input('sub_problem'),
        ]);
        $assignment->files()->sync($files);

        $assignment->touch();

        return redirect("/assignment/{$assignmentId}");
    }

    /**
     * @param Request $request
     * @param $assignmentId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportGrade(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignmentId);

        /** @var Group $group */
        $group = $user->managedGroups()->where('group_id', $assignment->group_id)->first();
        abort_if(empty($group), 403);


        $header = ['学号', '姓名', '作业名称', '提交时间', '迟交分钟数', '内容', '分数'];

        $spreadsheetBody = $assignment->submissions()->orderBy('owner_id')
            ->get()
            ->map(function (Submission $submission) {
                $lateInMinutes = Carbon::createFromTimeString($submission->assignment->deadline)
                    ->diffInMinutes($submission->created_at, false);

                return [
                    $submission->owner->student_id,
                    $submission->owner->name,
                    $submission->assignment->title,
                    $submission->created_at,
                    $lateInMinutes > 0 ? $lateInMinutes : null,
                    strip_tags($submission->content),
                    optional($submission->mark)->average_score,
                ];
            })->toArray();

        $spreadsheet = $this->fillSpreadSheet($header, $spreadsheetBody);
        return $this->spreadSheetResponse($spreadsheet, "{$assignment->title}成绩表");
    }

    public function exportFile(Request $request, $assignmentId)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Assignment $assignment */
        $assignment = Assignment::findOrFail($assignmentId);

        /** @var Group $group */
        $group = $user->managedGroups()->where('group_id', $assignment->group_id)->first();
        abort_if(empty($group), 403);

        $tempFile = tempnam(sys_get_temp_dir(), "se");
        register_shutdown_function(function ($filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }, $tempFile);

        $zip = new ZipArchive;
        if ($zip->open($tempFile)) {
            $assignment->submissions->each(function (Submission $submission) use ($zip) {
                $prefix = $submission->owner->student_id . '_' . preg_replace('/[\\/:*?"<>|]/', '', $submission->owner->name);
                $submission->files->each(function (File $file) use ($zip, $prefix) {
                    $filename = $file->filename;
                    $realpath = Storage::path(File::hashToPath($file->sha512) . DIRECTORY_SEPARATOR . $file->sha512);
                    $zip->addFile($realpath, $prefix . DIRECTORY_SEPARATOR . $filename);
                });
            });
            $zip->close();

            return response()->download($tempFile, "{$assignment->title}.zip")
                ->deleteFileAfterSend(true);
        } else {
            abort(503);
        }
    }

    /**
     * @param $header
     * @param $spreadSheetBody
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function fillSpreadSheet($header, $spreadSheetBody)
    {
        $spreadsheet = new Spreadsheet;
        $worksheet = $spreadsheet->getActiveSheet();

        //header
        $row = new Row($worksheet, 1);
        $cellIterator = $row->getCellIterator('A', Coordinate::stringFromColumnIndex(count($header)));
        foreach ($cellIterator as $cell) {
            $cell->setValueExplicit(current($header), DataType::TYPE_STRING);
            $worksheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            next($header);
        }

        $worksheet->getRowDimension(2);
        //body
        if (count($spreadSheetBody) > 0) {
            $rowIterator = $worksheet->getRowIterator(2, count($spreadSheetBody) + 1);
            foreach ($rowIterator as $row) {
                $rowData = current($spreadSheetBody);
                $cellIterator = $row->getCellIterator('A', Coordinate::stringFromColumnIndex(count($rowData)));
                foreach ($cellIterator as $cell) {
                    $cellData = current($rowData);
                    $cellType = null;
                    if (is_null($cellData)) {
                    } else if (is_bool($cellData)) {
                        $cell->setValueExplicit($cellData, DataType::TYPE_BOOL);
                    } else if (is_integer($cellData)) {
                        $cell->setValueExplicit($cellData, DataType::TYPE_NUMERIC);
                    } else if (is_float($cellData)) {
                        $cell->setValueExplicit($cellData, DataType::TYPE_NUMERIC);
                    } else if (is_string($cellData)) {
                        $cell->setValueExplicit($cellData, DataType::TYPE_STRING);
                    } else if ($cellData instanceof Carbon) {
                        if ($cellData->eq($cellData->copy()->startOfDay())) {
                            $formatCode = NumberFormat::FORMAT_DATE_YYYYMMDD2;
                        } else {
                            $formatCode = NumberFormat::FORMAT_DATE_YYYYMMDD . " " . NumberFormat::FORMAT_DATE_TIME3;
                        }

                        $cell->setValueExplicit(Date::PHPToExcel($cellData), DataType::TYPE_NUMERIC)
                            ->getStyle()->getNumberFormat()->setFormatCode($formatCode);
                    }
                    next($rowData);
                }
                next($spreadSheetBody);
            }
        }
        return $spreadsheet;
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function spreadSheetResponse(Spreadsheet $spreadsheet, string $filename)
    {
        $tempFile = tempnam(sys_get_temp_dir(), "se");
        register_shutdown_function(function ($filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }, $tempFile);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, "{$filename}.xlsx")
            ->deleteFileAfterSend(true);
    }
}
