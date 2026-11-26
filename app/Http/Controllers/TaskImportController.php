<?php

namespace App\Http\Controllers;


use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskImportController extends Controller
{
    private const MAX_ROWS = 1000;

    public function create() {
        return view('tasks.import');


    }

    public function template() {
        return response()->streamDownload(function () {
            $out = ('php://output', 'w');
            fputcsv($out, ['title', 'description']);
            fputcsv(['Prepare monthly report', 'Collect numbers from the team']);
            fclose($out);
        }, 'tasks-import-template.csv', ['Content-Type' => 'text/csv']);
        )
    }

    public function store(Request $request) {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);
        $user_id = session('user_id');
        $handle = fopen($request->file('file')->getRealPath(), 'r');


        $first_line = fgets($handle);

        if ($first_line===false) {
            fclose($handle);
            return back()->withErrors(['file' => 'The file is empty.']);
        }

        $first_line = preg_replace('/^\\xEF\\xBB\\xBF/', '', $first_line);

        $delimiter = substr_count($first_line, ';') > substr_count($first_line, ',') ? ';' : ',';

        $headers = array_map(fn, ($h) =>strtolower(trim($h)),str_getcsv($firstLine, $delimiter)));

        if(!in_array('title', $headers,true)) {
            fclose($handle);
            return back()->withErrors(['file' => 'The first row must contain a column named title.']);
        }

        $imported = 0; 
        $errors = [];
        $line = 1;
        $dataRows = 0; 
        
        DB::transaction(function () use $handle, $delimiter, $headers, $user_id, &$imported, &$errors, &$line, &$dataRows) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !==false) {
                $line++;

                if ($row===[null] || $row===[]) {
                    continue;
                }
                $dataRows++;

                if($dataRows>self::MAX_ROWS) {
                    $errors[] = 'stopped after '. self.MAX_ROWS . ' rows';
                    break;
                }

                $row = array_slice(array_pad($row, count($headers), null),0,count($headers));

                $data = array_combine($headers, $row);

                $data = array_map(function ($value){
                    if (! is_string($value)) {
                        return $value;
                    }

                    if(mb_check_encoding($value, 'UTF-8')) {
                        $value = mb_convert_encoding($value,'UTF-8', 'windows-1252');

                    }
                        return (trim($value))
                }, $data

                );

                $validator = Validator::make($data, [ 'title' => 'required|string|max:255',
                    'description' => 'nullable|string|max:5000',
                ]);

                if ($validator->fails()) {
                     $errors[] = 'Line ' . $line . ': ' . $validator->errors()->first();
                    continue;
                }

                Task::create(['user_id' => $userId,
                    'title' => $data['title'],
                    'description' => ($data['description'] ?? '') !== '' ? $data['description'] : null,
                ])
                TaskCreated::dispatch($task);
                $imported++;

                fclose($handle);


                Cache::forget('user:'.$user_id. ':task-stats');

                return redirect()->route('tasks.import.create')->with('success', 'Imported ' . $imported . ' task(s).')->with('import_errors', array_slice($errors, 0, 20));
            }
        }


    }
}
