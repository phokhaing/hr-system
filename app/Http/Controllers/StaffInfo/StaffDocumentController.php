<?php

namespace App\Http\Controllers\StaffInfo;

use App\StaffInfoModel\StaffDocument;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class StaffDocumentController extends Controller
{
    public function edit($id)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $staffDocumentType = DB::table('staff_document_types')->pluck('name_kh', 'id');
            return view('staffs.edit-document', compact('staff', 'staffDocumentType'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            $files = $request->file('files');
            $checks = $request->input('checks');
            $not_haves = $request->input('not_have');
            $staffId = decrypt($request->input('staff_token'));
            $staff = StaffPersonalInfo::find($staffId);
            $validation = Validator::make($request->all(), [
                'staff_token' => 'required',
                'files.*' => 'nullable|max:10000|mimes:pdf,jpg,jpeg,png',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $staffDocType = DB::table('staff_document_types')->pluck('id');
            $i = 0;
            foreach ($staffDocType as $key) {
                $file = isset($files[$key]) ? $files[$key] : null;
                $check = isset($checks[$key]) ? $checks[$key] : null;
                $not_have = isset($not_haves[$key]) ? $not_haves[$key] : null;

                if ($file || $check) {
                    if ($file) {
                        $orgFileName = $file->getClientOriginalName();
                        // fist_name_last_name_id_type_id_uuid.ext
                        $ext = $file->extension();
                        $fileName = $staff->last_name_en.'_'.$staff->first_name_en.'_'.$key.'_'.Uuid::uuid4().'.'.$ext;
                        $filePath = $file->storeAs('public/staff_document', $fileName);

                        StaffDocument::updateOrCreate(
                            [
                                'staff_personal_info_id' => $staffId,
                                'staff_document_type_id' => $key,
                            ],
                            [
                                'staff_personal_info_id' => $staffId,
                                'staff_document_type_id' => $key,
                                'src' => $filePath,
                                'name' => $orgFileName,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                            ]
                        );
                    }

                    $i++;
                }

                if ($check) {
                    StaffDocument::updateOrCreate(
                        [
                            'staff_personal_info_id' => $staffId,
                            'staff_document_type_id' => $key,
                        ],
                        [
                            'staff_personal_info_id' => $staffId,
                            'staff_document_type_id' => $key,
                            'check'     => $check,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]
                    );
                }

                if ($not_have) {
                    StaffDocument::updateOrCreate(
                        [
                            'staff_personal_info_id' => $staffId,
                            'staff_document_type_id' => $key,
                        ],
                        [
                            'staff_personal_info_id' => $staffId,
                            'staff_document_type_id' => $key,
                            'not_have'  => $not_have,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]
                    );
                }
            }
            if ($i) {
                return redirect()->back()->with(['success' => 1]);
            }else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function showFile($name) {
        return response()->file(storage_path('app/public/staff_document/'.$name));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $id = decrypt($id);
            $document = StaffDocument::find($id);
            StaffDocument::destroy($id);
            if ($document->src) {
                unlink(storage_path('app/'.$document->src));
            }
            return redirect()->back()->with(['success' => 1]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

//    /**
//     * @param Request $request
//     * @return \Illuminate\Http\RedirectResponse
//     * @throws \Exception
//     */
//    public function update(Request $request)
//    {
//        DB::beginTransaction();
//        try {
//            $staff_id = decrypt($request->staff_token);
//            $input = $request->except(['num_row', 'staff_token', '_token']);
//            $chFile = $request->file('doc_file_name'); // Get file from input
//
//            if ($chFile == null) {
//                $validation = Validator::make($input, [
//                    'doc_file_name' => 'required',
//                ]);
//            } else {
//                foreach ($input['doc_type_id'] as $key => $item) {
//                    $input['extension'][] = strtolower($chFile[$key]->getClientOriginalExtension());
//                }
//
//                $validation = Validator::make($input, [
//                    'extension.*'       => 'required|in:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf',
//                    'doc_type_id.*'     => 'required',
//                    'doc_file_name.*'   => 'required'
//                ]);
//            }
//            if ($validation->fails()) {
//                return redirect()->back()->withErrors($validation)->withInput();
//            }
//
//            foreach ($input['doc_type_id'] as $key => $item) {
//                $input['doc_file_name'] = $request->file('doc_file_name');
//                $fileName = ($input['doc_file_name'][$key])->getClientOriginalName();
//                $fileExte = ($input['doc_file_name'][$key])->getClientOriginalExtension();
//
//                $renameFile = time().'_'.date('d_M_Y').'_'.Auth::id().'_.'.$fileExte;
//
//                $save = StaffDocument::create([
//                    'staff_personal_info_id'   => $staff_id,
//                    'doc_type_id'   => $input['doc_type_id'][$key],
//                    'file_name_org' => $fileName,
//                    'file_rename'   => $renameFile,
//                    'created_by'    => Auth::id(),
//                    'updated_by'    => Auth::id()
//                ]);
//
//                if ($save == true) {
//                    DB::commit();
//                    ($input['doc_file_name'][$key])->move(public_path('/document/staff/'), $renameFile);
//                } else {
//                    DB::rollBack();
//                }
//            }
//            return redirect()->back()->with(['success' => 1]);
//
//        } catch (\Exception $exception) {
//            DB::rollBack();
//            throw $exception;
//        }
//
//    }
}
